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
</head>

<body>
    <div class="inner">
        <?php
        require_once('../common/common.php');
        ?>
        <!-- P271 日付選択プルダウンメニュー -->
        ダウンロードしたい注文日を選んでください。<br /><br />
        <form method="POST" action="order_download_done.php">
            <?php pulldown_year(); ?>年
            <?php pulldown_month(); ?>月
            <?php pulldown_day(); ?>日
            <br />
            <br />
            <input type="submit" value="ダウンロードへ">
        </form>
    </div>
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