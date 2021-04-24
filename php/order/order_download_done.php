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
error_reporting(E_ALL & ~E_NOTICE); // 0227 Noticeだけ非表示
$fileName = sha1(uniqid(mt_rand(), true)); // 0227 csvファイル名を乱数に
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
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
                // 2/11 P284 追加部分
                $year = $_POST['year'];
                $month = $_POST['month'];
                $day = $_POST['day'];

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // 2/11 P284 DBから値を取得するSQL文
                $sql = 'SELECT
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
        FROM
            dat_sales, dat_sales_product, mst_product
        WHERE
            dat_sales.code=dat_sales_product.code_sales
            AND dat_sales_product.code_product=mst_product.code
            AND substr(dat_sales.date,1,4)=?
            AND substr(dat_sales.date,6,2)=?
            AND substr(dat_sales.date,9,2)=?
        ';
                $stmt = $dbh->prepare($sql);

                $data[] = $year;
                $data[] = $month;
                $data[] = $day;
                $stmt->execute($data); //この命令が終了した時点で＄stmtの中にはすべてのデータが入っている。
                $dbh = null;

                // Excelの列タイトル
                $csv = '注文コード,注文日時,会員番号,お名前,メール,郵便番号,住所,TEL,商品コード,商品名,価格,数量';
                $csv .= "\n";

                while (true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($rec == false) {
                        break;
                    }
                    $csv .= $rec['code'];
                    $csv .= ',';
                    $csv .= $rec['date'];
                    $csv .= ',';
                    $csv .= $rec['code_member'];
                    $csv .= ',';
                    $csv .= $rec['dat_sales_name'];
                    $csv .= ',';
                    $csv .= $rec['email'];
                    $csv .= ',';
                    $csv .= $rec['postal1'] . '-' . $rec['postal2'];
                    $csv .= ',';
                    $csv .= $rec['address'];
                    $csv .= ',';
                    $csv .= $rec['tel'];
                    $csv .= ',';
                    $csv .= $rec['code_product'];
                    $csv .= ',';
                    $csv .= $rec['mst_product_name'];
                    $csv .= ',';
                    $csv .= $rec['price'];
                    $csv .= ',';
                    $csv .= $rec['quantity'];
                    $csv .= "\n";
                }
                // print nl2br($csv); // 確認用

                $file = fopen('./' . $fileName . '.csv', 'w');
                // $file = fopen('./order.csv', 'w');
                $csv = mb_convert_encoding($csv, 'SJIS', 'UTF-8');
                fputs($file, $csv);
                fclose($file);
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>
            <table align="center" border="1">
                <tr>
                    <td class="font_center">選択日時</td>
                    <td class="font_center">年</td>
                    <td class="font_center">月</td>
                    <td class="font_center">日</td>
                </tr>
                <tr>
                    <?php
                    if (!empty($year) && !empty($month) && !empty($day)) {
                        // print '<td><a href="order.csv">ダウンロード</a></td>';
                        print '<td><a href="' . $fileName . '.csv">ダウンロード</a></td>';
                    } else {
                        print '<td>日付が未選択です。</td>';
                    }
                    ?>
                    <td><?php print $year; ?></td>
                    <td><?php print $month; ?></td>
                    <td><?php print $day; ?></td>
                </tr>
            </table>
            <br />
            <br />
            <a href="order_download.php">日付選択へ</a><br />
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>

<!-- SELECT文

'SELECT name From mst_staff WHERE 1'

SELECT name     データをくれ
From mst_staff  どのテーブルから？[mst_staff]てーぶるから
WHERE1          どんなふうに？ [1]は全て、の意


---分岐画面 staff_branch.php に飛ばす---
・分岐画面に飛ばすためにリンク先を変更
print '<form method="POST" action="staff_branch.php">';

・name="edit", name="delete" を追加し、リンク先の staff_branch.php でどのボタンが押されたか区別する
-->