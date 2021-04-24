<?php
session_start();
session_regenerate_id(true);

require_once('../common/common.php');

$post = sanitize($_POST);


// 0226 カート内削除とDB連動
try {
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

$max = $post['max'];
for ($i = 0; $i < $max; $i++) {
    //[2/4] P225 数値の入力値に対して、正規表現チェック
    if (preg_match("/\A[0-9]+\z/", $post['number' . $i]) == 0) {
        print '<div class="inner">数量に誤りがあります。';
        print '<a href="shop_cartlook.php">カートに戻る</a></div>';
        exit();
    }

    //[2/4] P226 注文個数チェック
    if ($post['number' . $i] < 1 || 10 < $post['number' . $i]) {
        print '<div class="inner">注文個数は1個～10個までです。';
        print '<a href="shop_cartlook.php">カートに戻る</a></div>';
        exit();
    }
    $number[] = $post['number' . $i];
}
//[2/4] P216 カート内商品数を削除する機能
$cart = $_SESSION['cart'];
for ($i = $max; 0 <= $i; $i--) {
    if (isset($_POST['delete' . $i]) == true) {
        array_splice($cart, $i, 1);
        array_splice($number, $i, 1);
    }
}

$_SESSION['cart'] = $cart;
$_SESSION['number'] = $number;

header('Location:shop_cartlook.php');
exit();
