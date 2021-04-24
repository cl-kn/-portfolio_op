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
            商品追加
            <form method="POST" action="pro_add_check.php" enctype="multipart/form-data">
                <br />
                <table align="center" border="1">
                    <tr>
                        <td><label>商品名を入力してください。</label></td>
                        <td class="font_right"><input type="text" name="name" class="tr" style="width:200px"></td>
                    </tr>
                    <tr>
                        <td>価格を入力して下さい。</td>
                        <td class="font_right"><input type="number" name="price" class="tr" style="width:50px"> 円<br /></td>
                    </tr>
                    <tr>
                        <td>
                            <p>画像を選んでください。</p><input id="target" type="file" accept="image/*" name="image" style="width:250px">
                        <td class="tc"><img id="myImage">
                            <script src="../js/rokumaru.js"></script>
                        </td>
                        </td>
                    </tr>
                    <!-- 1/30 accept="image/*" 追加 -->

                </table>
                <br />
                <input type="button" onclick="history.back()" value="戻る">
                &emsp;
                <input type="submit" value="確認">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>