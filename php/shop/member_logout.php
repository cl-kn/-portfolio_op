<?php
session_start();
$_SESSION = array(); //セッション変数を空にする
if (isset($_COOKIE[session_name()]) == true) {
    //セッションIDをクッキーから削除
    setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy(); //セッションを破棄
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title></title>
    <link rel="stylesheet" href="../css/style_pro_shop.css">
</head>

<body>
    <div class="inner">
        ログアウトしました。<br />
        <br />
        <a href="shop_list.php">商品一覧へ</a>
    </div>
</body>

</html>