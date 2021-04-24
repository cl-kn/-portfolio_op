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
                $pro_code = $_GET['procode']; //受け取った商品コードのスタッフデータを修正していく

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL文でスタッフコードのデータをDBから取得
                $sql = 'SELECT name,price,image FROM mst_product WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[] = $pro_code;
                $stmt->execute($data);

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $pro_name = $rec['name'];
                $pro_price = $rec['price'];
                $pro_image_name = $rec['image'];

                $dbh = null;

                if ($pro_image_name == '') {
                    $disp_image = '※画像はありません※';
                } else {
                    $disp_image = '<img src="./image/' . $pro_image_name . '">';
                }
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            商品情報参照 <br />
            <br />
            <table align="center" border="1">
                <tr>
                    <td><b>商品コード</b></td>
                    <td class="font_right"><?php print $pro_code; ?></td>
                </tr>
                <tr>
                    <td><b>商品名</b></td>
                    <td class="font_right"><?php print $pro_name; ?></td>
                </tr>
                <tr>
                    <td><b>価格</b></td>
                    <td class="font_right"><?php print $pro_price; ?>円</td>
                </tr>
                <tr>
                    <td><b>画像</b><br /><br /><?php print $pro_image_name; ?></td>
                    <td><?php print $disp_image; ?></td>
                </tr>
            </table>
            <br />
            <br />
            <form>
                <input type="button" onclick="history.back()" value="戻る">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>