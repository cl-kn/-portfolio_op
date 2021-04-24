<?php
session_start();
session_regenerate_id(true); //セッションハイジャック対策
// $_SESSION に値が空の場合、ログイン時画面を表示しない
if (isset($_SESSION['member_login']) == false) {

    print 'ようこそゲスト様';
    print '<a href="member_login.html"> 会員ログイン </a><br />';
    print '<br />';
} else {
    print 'ようこそ ';
    print "[{$_SESSION['member_name']}]";
    print ' 様&emsp;';
    print '<a href="member_logout.php">ログアウト</a><br />';
    print '<br />';
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
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
                <li><a href="../shop/shop_list.php">商品一覧</a></li>
                <li><a href="../shop/shop_cartlook.php">カート</a></li>
                <!-- <li><a href="staff_logout.php">会員ログイン</a></li> -->
            </ul>
        </header>
        <main class="main">
            <?php
            try {
                $pro_code = $_GET['procode']; //受け取った商品コードのスタッフデータを修正していく

                // $member_name = $_SESSION['member_name'];

                //会員ログインしている場合

                // print $member_name;

                // 0226 カートをＤＢに追加
                // mst_productから、$pro_codeの商品情報を抽出
                // $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                // $user = 'root';
                // $password = '';
                // $dbh = new PDO($dsn, $user, $password);
                // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // ログイン中会員を特定し、カートに追加 ※途中
                // $sql = 'SELECT  dat_member.code,dat_member.name, mst_product.code, mst_product.name,mst_product.price, FROM mst_product JOIN dat_member WHERE code=$pro_code AND dat_member.name=$member_name ';

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // if (!empty($member_name)) {
                //     $sql = "SELECT dat_member.code,dat_member.name, mst_product.code, mst_product.name,mst_product.price, FROM dat_member JOIN  mst_product WHERE code='$pro_code' AND dat_member.name=$member_name ";
                //     $stmt = $dbh->prepare($sql);
                //     $stmt->execute();

                //     $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                //     $member_code = $rec['code'];
                //     $member_name = $rec['name'];
                //     $pro_code = $rec['code'];
                //     $pro_name = $rec['name'];
                //     $pro_price = $rec['price'];

                //     $sql = 'INSERT INTO dat_cart(member_code,member_name,code_product,pro_name,pro_price) VALUES (?,?,?,?,?)';
                //     $stmt = $dbh->prepare($sql);
                //     $data[] = $member_code;
                //     $data[] = $member_name;
                //     $data[] = $pro_code;
                //     $data[] = $pro_name;
                //     $data[] = $pro_price;
                //     $stmt->execute($data);
                //     $dbh = null;
                // } else {
                //     // 会員の区別なくカートに追加
                //     // mst_productからカートに追加した商品のデータを抽出
                //     $sql = "SELECT code, name, price FROM mst_product WHERE code='$pro_code'";
                //     $stmt = $dbh->prepare($sql);
                //     $stmt->execute();

                //     $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                //     $pro_code = $rec['code'];
                //     $pro_name = $rec['name'];
                //     $pro_price = $rec['price'];

                //     $sql = 'INSERT INTO dat_cart(member_name,code_product,pro_name,pro_price) VALUES (?,?,?,?)';
                //     $stmt = $dbh->prepare($sql);
                //     $data[] = $member_name;
                //     $data[] = $pro_code;
                //     $data[] = $pro_name;
                //     $data[] = $pro_price;
                //     $stmt->execute($data);
                //     $dbh = null;
                // }
                // 会員の区別なくカートに追加
                // mst_productからカートに追加した商品のデータを抽出
                $sql = "SELECT code, name, price FROM mst_product WHERE code='$pro_code'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $pro_code = $rec['code'];
                $pro_name = $rec['name'];
                $pro_price = $rec['price'];

                $sql = 'INSERT INTO dat_cart(code_product,pro_name,pro_price) VALUES (?,?,?)';
                $stmt = $dbh->prepare($sql);
                $data[] = $pro_code;
                $data[] = $pro_name;
                $data[] = $pro_price;
                $stmt->execute($data);
                $dbh = null;

                //商品コードをカート配列に入れる
                if (isset($_SESSION['cart']) == true) {
                    $cart = $_SESSION['cart']; //P193 現在のカート内容を$cartにコピー
                    $number = $_SESSION['number'];

                    // [2/4] P223 商品重複チェック
                    if (in_array($pro_code, $cart) == true) {
                        print 'その商品はすでにカートに入っています。<br />';
                        print '<br />';
                        print '<a href="shop_list.php">商品一覧に戻る</a>';
                        exit();
                    }
                }

                $cart[] = $pro_code; // [2/4] P190 カートに商品を追加
                $number[] = 1; //[2/4] P205 数量「１」を追加
                $_SESSION['cart'] = $cart; //[2/4] P190 SESSIONにカートを保存
                $_SESSION['number'] = $number; //[2/4] P205 後に取り出すように保管

            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            カートに追加しました。<br />
            <br />
            <a href="shop_list.php">商品一覧に戻る</a>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>