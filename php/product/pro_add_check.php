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
    print ' さんログイン中';
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
            require_once('../common/common.php');

            $post = sanitize($_POST);
            $pro_name = $post['name'];
            $pro_price = $post['price'];
            //受け取った画像ファイルの情報を取り出す
            $pro_image = $_FILES['image'];

            // $pro_name = htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8');
            // $pro_price = htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8');

            print '<table align="center" border="1"><br />';

            //入力チェック
            if (!($pro_name == '')) {
                print '<tr><td>商品名：</td>';
                print '<td>' . $pro_name . '</td>';
            } else {
                print '<tr><td>商品名：</td><td><font color="#DC143C">※商品名が未入力です。※</font></tr></td>';
            }

            if (preg_match('/^[0-9]+$/', $pro_price) == 0) {
                print '<tr><td>価格：</td><td><font color="#DC143C">※価格を正確に入力してください。※</font></td></tr>';
            } else {
                print '<tr><td>価格：</td>';
                print '<td>' . $pro_price;
                print '円</td></tr>';
            }

            //画像チェック追加部分
            if ($pro_image['size'] > 0) { //画像サイズが0より大きい＝true
                if ($pro_image['size'] > 1000000) {
                    print '<tr><td>画像</td><td>画像サイズが大き過ぎます。</td></tr>';
                } else {
                    move_uploaded_file($pro_image['tmp_name'], './image/' . $pro_image['name']); //move_uploaded_file(移動元,移動先)
                    print '<tr><td>画像</td><td class="font_center"><img src="./image/' . $pro_image['name'] . '"></td></tr>';
                }
            }

            print '</table><br />';

            if ($pro_name == '' || preg_match('/^[0-9]+$/', $pro_price) == 0 || $pro_image['size'] > 1000000) {
                print '<form>';
                print '<input type="button" onclick="history.back()" value="戻る">';
                print '</form>';
            } else {
                print '<br />上記の商品を追加します。<br />';
                print '<form method="post" action="pro_add_done.php">';
                print '<input type="hidden" name="name" value="' . $pro_name . '">';
                print '<input type="hidden" name="price" value="' . $pro_price . '">';
                //画像名を次の画面に渡す ※['name']にXSS有り
                print '<input type="hidden" name="image_name" value="' . $pro_image['name'] . '">';
                print '<br />';
                print '<input type="button" onclick="history.back()" value="戻る">&emsp;';
                print '<input type="submit" value="追加する">';
                print '<form />';
            }
            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>