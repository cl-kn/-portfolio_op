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
            try {
                $staff_code = $_GET['staffcode']; //受け取ったスタッフコードのスタッフデータを修正していく

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL文でスタッフコードのデータをDBから取得
                $sql = 'SELECT name FROM mst_staff WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[] = $staff_code;
                $stmt->execute($data);

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $staff_name = $rec['name'];

                $dbh = null;
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            スタッフ修正 <br />
            <br />
            <table align="center" border="1">
                <tr>
                    <td>スタッフコード</td>
                    <td class="font_right"><?php print $staff_code; ?></td>
                </tr>
                <form method="POST" action="staff_edit_check.php">
                    <input type="hidden" name="code" value="<?php print $staff_code; ?>">
                    <tr>
                        <td>スタッフ名</td>
                        <td class="font_right"><input type="text" name="name" class="tr" style="width:200px" value="<?php print $staff_name; ?>"></td>
                    </tr>
                    <tr>
                        <td>パスワードを入力して下さい</td>
                        <td class="font_right"><input type="password" name="pass" class="tr" style="width:100px"></td>
                    </tr>
                    <tr>
                        <td>パスワードをもう一度入力して下さい。 </td>
                        <td class="font_right"><input type="password" name="pass2" class="tr" style="width:100px"></td>
                    </tr>
            </table>

            <br />
            <input type="button" onclick="history.back()" value="戻る">&emsp;
            <input type="submit" value="確認">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>


    <div class="inner">

    </div>
</body>

</html>

<!--
入力済みスタッフ名
value="<?php print $staff_name; ?>"



 -->