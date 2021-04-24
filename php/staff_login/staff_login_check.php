<?php
require_once('../common/common.php');

try {
    $post = sanitize($_POST);

    $staff_code = $post['code'];
    $staff_pass = $post['pass'];

    // $staff_code = htmlspecialchars($staff_code,ENT_QUOTES,'UTF-8');
    // $staff_pass = htmlspecialchars($staff_pass,ENT_QUOTES,'UTF-8');

    //パスワード暗号化
    $staff_pass = md5($staff_pass);

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT name FROM mst_staff WHERE code=? AND password=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $staff_code;
    $data[] = $staff_pass;
    $stmt->execute($data);

    $dbh = null;

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rec == false) {
        print '<div class="inner">' . 'スタッフコードか、パスワードが間違いです。<br /><br />';
        print '<a href="staff_login.html">戻る</a></div>';
    } else {
        //セッションスタート
        session_start();
        $_SESSION['login'] = 1; // ログイン成功時（値は不問）
        $_SESSION['staff_code'] = $staff_code;
        $_SESSION['staff_name'] = $rec['name'];
        header('Location:staff_top.php');
        exit();
    }
} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title></title>
    <link rel="stylesheet" href="../css/style_pro.css">
</head>

<body>

</body>

</html>