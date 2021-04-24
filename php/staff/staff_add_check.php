<?php
session_start();
session_regenerate_id(true); //セッションハイジャック対策
// $_SESSION に値が空の場合、ログイン時画面を表示しない
if (isset($_SESSION['login']) == false) {
    print 'ログインされていません。<br />';
    print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
    exit();
} else {
    print "[{$_SESSION['staff_name']}]";
    print ' さんログイン中 <br />';
    print '<br />';
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="../css/style_pro.css">
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
                <li><a href="../staff_login/staff_top.php">トップ</a></li>
                <li><a href="../staff/staff_list.php">スタッフ管理</a></li>
                <li><a href="../staff_login/product_top.php">商品管理</a></li>
                <li><a href="../customer/customer_list.php">お客様管理</a></li>
                <li><a href="../order/order_download.php">注文ダウンロード</a></li>
                <li><a href="staff_logout.php">ログアウト</a></li>
            </ul>
        </header>
        <main class="main">
            <?php
            require_once('../common/common.php');

            // 関数sanitize()で一括チェック
            $post = sanitize($_POST);
            $staff_name = $post['name'];
            $staff_pass = $post['pass'];
            $staff_pass2 = $post['pass2'];

            // $staff_name = htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8');
            // $staff_pass = htmlspecialchars($staff_pass, ENT_QUOTES, 'UTF-8');
            // $staff_pass2 = htmlspecialchars($staff_pass2, ENT_QUOTES, 'UTF-8');

            print '<table align="center" border="1">';
            //入力チェック
            if (!($staff_name == '')) {
                print '<tr><td>スタッフ名：</td>';
                print '<td>' . $staff_name . '</td></tr>';
            } else {
                print 'スタッフ名が未入力です。<br />';
            }

            if ($staff_pass == '') {
                print 'パスワードが未入力です。<br />';
            }

            if ($staff_pass != $staff_pass2) {
                print 'パスワードが一致しません。<br />';
            }

            print '</table>';

            if ($staff_name == '' || $staff_pass == '' || $staff_pass != $staff_pass2) {
                print '<form>';
                print '<input type="button" onclick="history.back()" value="戻る">';
                print '</form>';
            } else {
                // md5()  変数の暗号化  password_hash()
                $staff_pass = md5($staff_pass);
                print '<form method="POST" action="staff_add_done.php">';
                print '<input type="hidden" name="name" value="' . $staff_name . '">';
                print '<input type="hidden" name="pass" value="' . $staff_pass . '">';
                print '<br />';
                print '<input type="button" onclick="history.back()" value="戻る">&emsp;';
                print '<input type="submit" value="OK">';
                print '</form>';
            }
            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>