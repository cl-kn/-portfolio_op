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
</head>

<body>
    <div class="container">
        <header class="item header">
            <ul>
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
                $pro_code = $_GET['procode']; //受け取った商品コードの商品データを修正していく

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL文で商品コードのデータをDBから取得
                $sql = 'SELECT name, image FROM mst_product WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[] = $pro_code;
                $stmt->execute($data);

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $pro_name = $rec['name'];
                $pro_image_name = $rec['image'];

                $dbh = null;

                if ($pro_image_name == '') {
                    $disp_image = '';
                } else {
                    $disp_image = '<img src="./image/' . $pro_image_name . '">';
                }
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            商品削除
            <table align="center" border="1">
                <tr>
                    <td>商品コード</td>
                    <td><?php print $pro_code; ?></td>
                    <br />
                <tr>
                    <td>商品名<br /></td>
                    <td><?php print $pro_name; ?></td>
                <tr>
                    <td>画像</td>
                    <td><?php
                        if (!empty($disp_image)) {
                            print $disp_image;
                        } else {
                            print '※画像はありません※';
                        }
                        ?></td>
                </tr>
                <br />
            </table>
            <br />この商品を削除してよろしいですか？<br />
            <br />
            <form method="POST" action="pro_delete_done.php">
                <input type="hidden" name="code" value="<?php print $pro_code; ?>">
                <input type="hidden" name="image_name" value="<?php print $pro_image_name; ?>">

                <input type="button" onclick="history.back()" value="戻る">&emsp;
                <input type="submit" value="削除する">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>

<!--
入力済み商品名
value="<?php print $staff_name; ?>"



 -->