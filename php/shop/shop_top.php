<?php
session_start();
session_regenerate_id(true);

if (isset($_SESSION['member_login']) == false) {

    print 'ようこそゲスト様';
    print '<a href="member_login.html"> 会員ログイン </a><br />';
    print '<br />';
} else {
    print 'ようこそ ';
    print "[{$_SESSION['member_name']}]";
    print ' 様&emsp;';
    print '<a href="member_logout.php">ログアウト</a><br />';
    print '<br />';
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="../css/style_pro_shop.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('.global-nav li a').each(function() {
                var target = $(this).attr('href');
                if (location.href.match(target)) {
                    $(this).parent().addClass('current');
                } else {
                    $(this).parent().removeClass('current');
                }
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <header class="item header">
            <ul class="global-nav">
                <li><a href="../shop/shop_top.php">トップ</a></li>
                <li><a href="../shop/shop_list.php">商品一覧</a></li>
                <li><a href="../shop/shop_cartlook.php">カート</a></li>
                <!-- <li><a href="staff_logout.php">会員ログイン</a></li> -->
            </ul>
        </header>
        <main class="main">
            <p>～～ろくまる農園～～</p>
            <!-- <p><img src="../yasai/_back.jpg" width="600"></p> -->
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>


<!-- <div class="inner"> -->

<!-- <br />
        <a href="../staff/staff_list.php">スタッフ管理</a><br />
        <br />
        <a href="../staff_login/product_top.php">商品管理</a><br />
        <br />
        <a href="../customer/customer_list.php">お客様管理</a><br />
        <br />
        <a href="../order/order_download.php">注文ダウンロード</a><br />
        <br />
        <a href="staff_logout.php">ログアウト</a><br /> -->
<!-- </div> -->