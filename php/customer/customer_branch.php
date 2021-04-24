<?php

session_start();
session_regenerate_id(true); //セッションハイジャック対策
// $_SESSION に値が空の場合、ログイン時画面を表示しない
if (isset($_SESSION['login']) == false) {

    print 'ログインされていません。<br />';
    print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
    exit();
}

//各々のページに遷移する分岐
if (isset($_POST['buy']) == true) {
    if (isset($_POST['member_code']) == false) {

        header('Location:customer_ng.php');
        exit();
    }
    $member_code = $_POST['member_code'];
    //URLにスタッフコードを含めて、ページ遷移
    header('Location:buying_history.php?member_code=' . $member_code);
    exit();
}
