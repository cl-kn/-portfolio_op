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
    <link rel="stylesheet" href="../css/style_pro.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>

<body>
    <div class="container">
        <header class="item header">
            <ul>
                <li><a href="../staff_login/staff_login.html">ログイン画面へ</a></li>
            </ul>
        </header>
        <main class="main">
            <p>ログアウトしました。</p>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>