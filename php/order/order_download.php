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
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="../css/style_pro.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
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
            ?>
            <!-- P271 日付選択プルダウンメニュー -->
            <script>
                jQuery(function() {
                    // ここにコードを記述
                    function formSetDay() {
                        var month = Number($('.js-changeMonth').val());
                        var day = Number($('.js-changeDay').val());
                        // var lastday = formSetLastDay($('.js-changeYear').val(), $('.js-changeMonth').val());
                        var lastday = formSetLastDay($('.js-changeYear').val(), month);
                        var option = '';
                        for (var i = 1; i <= lastday; i++) {
                            // if (i === $('.js-changeDay').val()) {
                            if (i === day) {
                                option += '<option value="' + i + '" selected="selected">' + i + '</option>\n';
                            } else {
                                option += '<option value="' + i + '">' + i + '</option>\n';
                            }
                        }
                        $('.js-changeDay').html(option);
                    }

                    function formSetLastDay(year, month) {
                        var lastday = new Array('', 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                        if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0) {
                            lastday[2] = 29;
                        }
                        return lastday[month];
                    }

                    $('.js-changeYear, .js-changeMonth').change(function() {
                        formSetDay();
                    });
                });
            </script>
            <form method="POST" action="order_download_done.php">
                ダウンロードしたい注文日を選んでください。<br /><br />
                <?php pulldown_year() ?>年
                <?php pulldown_month() ?>月
                <?php pulldown_day() ?>日
                <input type="submit" value="ダウンロードへ">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>

<!--

SELECT
dat_sales.code,
dat_sales.date,
dat_sales.code_member,
dat_sales.name AS dat_sales_name,
dat_sales.email,
dat_sales.postal1,
dat_sales.postal2,
dat_sales.address,
dat_sales.tel,
dat_sales_product.code_product,
mst_product.name AS mst_product_name,
dat_sales_product.price,
dat_sales_product.quantity

FROM dat_sales,dat_sales_product,mst_product
WHERE
dat_sales.code=dat_sales_product.code_sales
AND dat_sales_product.code_product=mst_product.code
AND substr(dat_sales.date,1,4) = "2020"
AND substr(dat_sales.date,6,2) = "02"
AND substr(dat_sales.date,9,2) = "10"

CSV連結

$csv = '';
$csv .= "\n";
$csv .= '1001';
$csv .= ',';
$csv .= 'ポチ';
$csv .= ',';
$csv .= '柴犬部';
$csv .= "\n";
$csv .= '1002';
$csv .= ',';
$csv .= '田中次郎';
$csv .= ',';
$csv .= '経理部';
$csv .= "\n";

 -->