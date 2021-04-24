<!DOCTYPE html>
<html lang="ja">

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
                <!-- <li><a href="../shop/shop_top.php">トップ</a></li> -->
                <!-- <li><a href="../shop/shop_list.php">商品一覧</a></li> -->
                <!-- <li><a href="../shop/shop_cartlook.php">カート</a></li> -->
                <!-- <li><a href="staff_logout.php">会員ログイン</a></li> -->
            </ul>
        </header>
        <main class="main">
            <?php
            //セキュリティ関数処理
            require_once('../common/common.php');
            $post = sanitize($_POST);

            // P235 入力データを変数に格納
            $user_name = $post['user_name']; //お客様：氏名
            $user_email = $post['user_email']; //お客様：メール
            $postal1 = $post['postal1']; //お客様：郵便番号：前
            $postal2 = $post['postal2']; //お客様：郵便番号：後
            $user_address = $post['user_address']; //お客様：住所
            $user_tel = $post['user_tel']; //お客様：電話番号
            // 2.11 P295 フォーム値を代入
            $order = $post['order']; //注文
            $pass = $post['pass']; //パスワード
            $pass2 = $post['pass2']; //パスワード確認用
            $sex = $post['sex']; //性別
            $birth = $post['birth']; //生年月日

            // P239 フラグ設置
            $ok_flg = true;

            print '<table align="center" border="1">';
            // P235 入力データチェック
            if (!($user_name == '')) {
                print "<tr><td>[お名前]</td><td>{$user_name} 様</td></tr>";
            } else {
                print '<tr><td>[お名前]</td><td><font color="DC143C">※お名前が入力されていません。※</font></td></tr>';
                $ok_flg = false;
            }

            if (!(preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $user_email) == 0)) {
                print "<tr><td>[メールアドレス]</td><td>{$user_email}</td></tr>";
            } else {
                print '<tr><td>[メールアドレス]</td><td><font color="DC143C">※メールアドレスを正確に入力してください。※</font></td></tr>';
                $ok_flg = false;
            }

            if (!(preg_match('/\A[0-9]+\z/', $postal1) == 0) && !(preg_match('/\A[0-9]+\z/', $postal2) == 0)) {
                print "<tr><td>[郵便番号]</td><td>{$postal1} - {$postal2}</tr></td>";
            } else {
                print '<tr><td>[郵便番号]</td><td><font color="DC143C">※郵便番号は半角数字で入力してください。※</font></td></tr>';
                $ok_flg = false;
            }

            // if (preg_match('/\A[0-9]+\z/', $postal2) == 0) {
            //     print '<tr><td>[郵便番号]</td><td><font color="DC143C">※郵便番号は半角数字で入力してください。※</font></td></tr>';
            //     $ok_flg = false;
            // }

            if (!($user_address == '')) {
                print "<tr><td>[住所]</td><td>{$user_address}</td></tr>";
            } else {
                print '<tr><td>[住所]</td><td><font color="DC143C">※住所が入力されていません。※</font></td>';
                $ok_flg = false;
            }

            if (!(preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $user_tel) == 0)) {
                print "<tr><td>[電話番号]</td><td>{$user_tel}</td></tr>";
            } else {
                print '<tr><td>[電話番号]</td><td><font color="DC143C">※電話番号を正確に入力してください。※</font></td></tr>';
                $ok_flg = false;
            }

            //P295 会員登録のチェック機能
            if ($order == 'order_register') {
                if ($pass == '') {
                    print '<tr><td>[パスワード]</td><td><font color="DC143C">※パスワードが入力されていません。※</font></td></tr>';
                    $ok_flg = false;
                }
                if ($pass != $pass2) {
                    print '<tr><td>[パスワード]</td><td><font color="DC143C">※パスワードが一致しません※</font></td></tr>';
                    $ok_flg = false;
                }
                print '<tr><td>[性別]</td>';
                if ($sex == 'female') {
                    print '<td>女性</td></tr>';
                } else {
                    print '<td>男性</td></tr>';
                }
                print '<tr><td>[生まれ年]</td>';
                print '<td>' . $birth;
                print '年代</td></tr>';
            }

            print '</table>';

            // P240 フラグチェック
            // $ok_flg == true の時のみ、OKボタンを表示する
            if ($ok_flg == true) {
                print '<form method="POST" action="shop_form_done.php">';
                print '<input type="hidden" name="user_name" value="' . $user_name . '">';
                print '<input type="hidden" name="user_email" value="' . $user_email . '">';
                print '<input type="hidden" name="postal1" value="' . $postal1 . '">';
                print '<input type="hidden" name="postal2" value="' . $postal2 . '">';
                print '<input type="hidden" name="user_address" value="' . $user_address . '">';
                print '<input type="hidden" name="user_tel" value="' . $user_tel . '">';
                // 2.11 追加部分
                print '<input type="hidden" name="order" value="' . $order . '">';
                print '<input type="hidden" name="pass" value="' . $pass . '">';
                print '<input type="hidden" name="sex" value="' . $sex . '">';
                print '<input type="hidden" name="birth" value="' . $birth . '">';
                print '<br /><input type="button" onclick="history.back()" value="戻る">&emsp;';
                print '<input type="submit" value="OK"><br />';
                print '</form>';
            } else {
                print '<form>';
                print '<br /><input type="button" onclick="history.back()" value="戻る">';
                print '</form>';
            }

            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>