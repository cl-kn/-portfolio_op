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
            スタッフ追加
            <form method="POST" action="staff_add_check.php">
                <table align="center" border="1">
                    <br />
                    <tr>
                        <td><label>スタッフ名を入力してください。</label></td>
                        <td class="font_right"><input type="text" name="name" class="tr" style="width:200px"></td>
                    </tr>

                    <tr>
                        <td>パスワードを入力して下さい。</td>
                        <td class="font_right"><input type="password" name="pass" class="tr" style="width:100px"></td>
                    </tr>

                    <tr>
                        <td>パスワードを再入力して下さい。</td>
                        <td class="font_right"><input type="password" name="pass2" class="tr" style="width:100px"></td>
                    </tr>
                </table>

                <p><input type="button" onclick="history.back()" value="戻る">&emsp;

                    <input type="submit" value="確認">
                </p>
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>