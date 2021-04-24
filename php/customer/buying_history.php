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
            <?php
            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $member_code = $_GET['member_code'];

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // 0302 追加部分
                // SQL文で注文明細等、購入履歴のデータをDBから取得
                $sql = 'SELECT
            -- code を、 dat_sales_code と命名
            dat_sales.code AS dat_sales_code,
            dat_sales.date,
            dat_sales_product.code_product,
            mst_product.name AS mst_product_name,
            dat_sales_product.price,
            dat_sales_product.quantity
        FROM
            dat_sales, dat_sales_product, mst_product
        WHERE
            dat_sales.code_member=' . $member_code . ' AND dat_sales.code=dat_sales_product.code_sales AND dat_sales_product.code_product=mst_product.code
        ';
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $dbh = null;

                print 'お客様購入履歴 <br /><br />';
                print '<table align="center" border="1">';
                print '<tr class="font_center">';
                print '<td>注文日コード</td>';
                print '<td>注文日時</td>';
                print '<td>商品コード</td>';
                print '<td>商品名</td>';
                print '<td>価格</td>';
                print '<td>数量</td>';
                print '</tr>';

                while (true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($rec == false) {
                        break;
                    }
                    print '<tr>';
                    print '<td>' . $rec['dat_sales_code'] . '</td>';
                    print '<td>' . $rec['date'] . '</td>';
                    print '<td>' . $rec['code_product'] . '</td>';
                    print '<td>' . $rec['mst_product_name'] . '</td>';
                    print '<td>' . $rec['price'] . '</td>';
                    print '<td>' . $rec['quantity'] . '</td>';
                    print '</tr>';
                }
                print '</table>';
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。<br />';
                echo 'mysqli_sql_exception' . $e->getMessage();
                exit();
            }
            ?>
            <br>
            <form>
                <input type="button" onclick="history.back()" value="戻る">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>