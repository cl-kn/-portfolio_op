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
if (isset($_POST['disp']) == true) {
    if (isset($_POST['staffcode']) == false) {

        header('Location:staff_ng.php');
        exit();
    }
    $staff_code = $_POST['staffcode'];
    //URLにスタッフコードを含めて、ページ遷移
    header('Location:staff_disp.php?staffcode=' . $staff_code);
    exit();
}

if (isset($_POST['add']) == true) {
    header('Location:staff_add.php');
    exit();
}

if (isset($_POST['edit']) == true) {

    if (isset($_POST['staffcode']) == false) {
        header('Location:staff_ng.php');
        exit();
    }

    $staff_code = $_POST['staffcode'];
    header('Location:staff_edit.php?staffcode=' . $staff_code);
    exit();
}

if (isset($_POST['delete']) == true) {

    if (isset($_POST['staffcode']) == false) {
        header('Location:staff_ng.php');
        exit();
    }

    $staff_code = $_POST['staffcode'];
    header('Location:staff_delete.php?staffcode=' . $staff_code);
    exit();
}
