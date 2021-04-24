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
</head>

<body>
    <div class="inner">
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
                $disp_image = '画像はありません';
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
        商品コード <br />
        <?php print $pro_code; ?>
        <br />
        商品名<br />
        <?php print $pro_name; ?>
        <br />
        価格<br />
        <?php print $pro_price; ?>円
        <br />
        <?php print $disp_image; ?>
        <br />
        <br />
        <form>
            <input type="button" onclick="history.back()" value="戻る">
        </form>
    </div>
</body>

</html>