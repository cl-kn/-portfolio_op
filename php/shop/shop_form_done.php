<?php
//セッションハイジャック対策
session_start();
session_regenerate_id(true);
error_reporting(0); //警告非表示
ini_set("max_execution_time", 180); //時間制限設定
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="../css/style_pro_shop.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('.global-nav li a').each(function() {
                var target = $(this).attr('href');
                if (location.href.match(target)) {
                    $(this).parent().addClass('current');
                } else {
                    $(this).parent().removeClass('current');
                }
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <header class="item header">
            <ul class="global-nav">
                <li><a href="../shop/shop_top.php">トップ</a></li>
                <!-- <li><a href="../shop/shop_list.php">商品一覧</a></li> -->
                <!-- <li><a href="../shop/shop_cartlook.php">カート</a></li> -->
                <!-- <li><a href="staff_logout.php">会員ログイン</a></li> -->
            </ul>
        </header>
        <main class="main">
            <?php
            try {
                //セキュリティ関数処理
                require_once('../common/common.php');
                $post = sanitize($_POST);
                // P242 入力データを変数に格納
                $user_name = $post['user_name'];
                $user_email = $post['user_email'];
                $postal1 = $post['postal1'];
                $postal2 = $post['postal2'];
                $user_address = $post['user_address'];
                $user_tel = $post['user_tel'];
                // P297 追加部分
                $order = $post['order'];
                $pass = $post['pass'];
                $sex = $post['sex'];
                $birth = $post['birth'];

                print "[{$user_name}] 様<br />ご注文ありがとうございます。<br/ >";
                print "以下の通りに会員登録が完了致しました。<br />";
                print "{$user_email} にメールを送りましたのでご確認下さい。<br/>";
                print '<br /><table align="center" border="1">';
                print "<tr><td>お名前</td><td>{$user_name}</td><tr>";
                print "<tr><td>メールアドレス</td><td>{$user_email}</td><tr>";
                print "<tr><td>郵便番号</td><td>{$postal1} - {$postal2}</td><tr>";
                print "<tr><td>住所</td><td>{$user_address}</td><tr>";
                print "<tr><td>電話番号</td><td>{$user_tel}</td><tr>";
                print '</table><br />';

                $text = '';
                $text .= $user_name . "様\n\nこのたびはご注文ありがとうございました。\n";
                $text .= "\n";
                $text .= "ご注文商品\n";
                $text .= "--------------------\n";

                $cart = $_SESSION['cart'];
                $number = $_SESSION['number'];
                $max = count($cart);

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                for ($i = 0; $i < $max; $i++) {
                    $sql = 'SELECT name,price FROM mst_product WHERE code=?';
                    $stmt = $dbh->prepare($sql);
                    $data[0] = $cart[$i];
                    $stmt->execute($data);

                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    //P245 変数名変更済み
                    $name = $rec['name'];
                    $price = $rec['price'];
                    $priceArray[] = $price; // P257 価格を配列変数$priceArray に保存
                    $quantity = $number[$i];
                    $sum = $price * $quantity;

                    $text .= $name . ' ';
                    $text .= $price . '円 x ';
                    $text .= $quantity . '個 = ';
                    $text .= $sum . "円\n";
                }
                //P262 テーブルロック
                $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE,dat_member WRITE';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                $lastmembercode = 0;
                if ($order == 'order_register') {
                    $sql = 'INSERT INTO dat_member (password,name,email,postal1,postal2,address,tel,sex,born) VALUES (?,?,?,?,?,?,?,?,?)';
                    $stmt = $dbh->prepare($sql);
                    $data = array();
                    $data[] = md5($pass);
                    $data[] = $user_name;
                    $data[] = $user_email;
                    $data[] = $postal1;
                    $data[] = $postal2;
                    $data[] = $user_address;
                    $data[] = $user_tel;
                    if ($sex == 'female') {
                        $data[] = 1;
                    } else {
                        $data[] = 2;
                    }
                    $data[] = $birth;
                    $stmt->execute($data);

                    $sql = 'SELECT LAST_INSERT_ID()';
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    $lastmembercode = $rec['LAST_INSERT_ID()'];
                }
                //P256 注文データを追加するPG
                $sql = 'INSERT INTO dat_sales (code_member,name,email,postal1,postal2,address,tel) VALUES (?,?,?,?,?,?,?)';
                $stmt = $dbh->prepare($sql);
                $data = array(); //配列初期化
                $data[] = $lastmembercode; //会員コード
                $data[] = $user_name;
                $data[] = $user_email;
                $data[] = $postal1;
                $data[] = $postal2;
                $data[] = $user_address;
                $data[] = $user_tel;
                $stmt->execute($data);

                // P257
                // SELSCT LAST_INSERT_ID()
                // AUTO_INCREMENTで直近に発番された番号を取得
                $sql = 'SELECT LAST_INSERT_ID()';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastcode = $rec['LAST_INSERT_ID()'];

                // P257 商品明細を追加
                for ($i = 0; $i < $max; $i++) {
                    $sql = 'INSERT INTO dat_sales_product (code_sales,code_product,price,quantity) VALUES (?,?,?,?)';
                    $stmt = $dbh->prepare($sql);
                    $data = array();
                    $data[] = $lastcode;
                    $data[] = $cart[$i];
                    $data[] = $priceArray[$i];
                    $data[] = $number[$i];
                    $stmt->execute($data);
                }

                //テーブルロック解除
                $sql = 'UNLOCK TABLES';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                $dbh = null;

                // P299 会員登録済み向けメッセージ表示
                if ($order == 'order_register') {
                    print '会員登録が完了いたしました。<br />';
                    print '次回からメールアドレスとでログインしてください。<br />';
                    print 'ご注文が簡単にできるようになります。<br />';
                    print '<br />';
                }

                //振込先の案内、店舗の情報
                $text .= "送料は無料です。\n";
                $text .= "--------------------\n";
                $text .= "\n";
                $text .= "代金は以下の口座にお振込ください。\n";
                $text .= "ろくまる銀行 やさい支店 普通口座 １２３４５６７\n";
                $text .= "入金確認が取れ次第、梱包、発送させていただきます。\n";
                $text .= "\n";

                // P299 会員登録済み向けメールメッセージ表示
                if ($order == 'order_register') {
                    $text .= "会員登録が完了いたしました。\n";
                    $text .= "次回からメールアドレスとでログインしてください。\n";
                    $text .= "ご注文が簡単にできるようになります。\n";
                    $text .= "\n";
                }

                $text .= "□□□□□□□□□□□□□□\n";
                $text .= "～安心野菜のろくまる農園～\n";
                $text .= "\n";
                $text .= "○○県六丸郡六丸村123-4\n";
                $text .= "電話 090-6060-xxxx\n";
                $text .= "メール info@rokumarunouen.co.jp\n";
                $text .= "□□□□□□□□□□□□□□\n";

                //P248 客への自動返信メール
                $title = 'ご注文ありがとうございます。';
                $header = 'From:info@rokumarunouen.co.jp';
                $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
                mb_language('Japanese');
                mb_internal_encoding('UTF-8');
                mb_send_mail($user_email, $title, $text, $header);

                $title = 'お客様からご注文がありました。';

                // 2/18 $user_emailに対するメールヘッダインジェクション
                if (!preg_match(('/^\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i'), $user_email)) {
                    die('不正なメールアドレスです。');
                }
                $header = 'From:' . $user_email;
                $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
                mb_language('Japanese');
                mb_internal_encoding('UTF-8');
                mb_send_mail('info@rokumarunouen.co.jp', $title, $text, $header);
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>
            <!-- <a href="shop_list.php">商品画面へ</a> -->
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>