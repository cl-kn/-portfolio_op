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
    <link rel="stylesheet" href="../css/style_pro_number.css">
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

                // SQL文で商品コードのデータをDBから取得
                $sql = 'SELECT name,price,image FROM mst_product WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[] = $pro_code;
                $stmt->execute($data);

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $pro_name = $rec['name'];
                $pro_price = $rec['price'];
                $pro_image_name_old = $rec['image'];

                $dbh = null;

                //画像を登録している時のみ、$disp_image（画像表示用）に挿入
                if ($pro_image_name_old == '') {
                    $disp_image = '';
                } else {
                    $disp_image = '<img src="./image/' . $pro_image_name_old . '">';
                }
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            商品修正 <br />
            <br />
            <table align="center" border="1">
                <tr>
                    <td><b>商品コード</b></td>
                    <td class="font_right"><?php print $pro_code; ?></td>
                </tr>
                <form method="post" action="pro_edit_check.php" enctype="multipart/form-data">
                    <input type="hidden" name="code" value="<?php print $pro_code; ?>">
                    <tr><input type="hidden" name="image_name_old" value="<?php print $pro_image_name_old; ?>">

                        <td><b>商品名</b><br /></td>
                        <td class="font_right"><input type="text" name="name" class="tr" style="width:200px" value="<?php print $pro_name; ?>"></td>
                    <tr>
                        <td><b>価格</b><br /></td>
                        <td class="font_right"><input type="number" name="price" class="tr" style="width:50px" value="<?php print $pro_price; ?>"> 円</td>
                    <tr>
                        <!-- 1/30 accept="image/*" 追加 -->
                        <td><b>現在の登録画像</b><br /><br /><?php print $pro_image_name_old; ?></td>
                        <td class="tc"><?php
                                        if ($pro_image_name_old == '') {
                                            print '※画像は登録されていません※';
                                        } else {
                                            print $disp_image;
                                        } ?></td>
                    <tr>
                        <td>変更する画像を選んで下さい。<br /><br /><input id="target" type="file" accept="image/*" name="image" style="width:250px">
                        <td class="tc"><img id="myImage">
                            <script src="../js/rokumaru.js"></script>
                        </td>
                    </tr>
            </table>
            <br />
            <input type="button" onclick="history.back()" value="戻る">&emsp;
            <input type="submit" value="修正する">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>

<!--
入力済みスタッフ名
value="<?php print $staff_name; ?>"
 -->