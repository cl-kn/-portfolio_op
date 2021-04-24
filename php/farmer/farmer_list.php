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
    <title></title>
    <link rel="stylesheet" href="../css/style_pro.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.4.min.js"></script>
    <script src="../js/tr_background_color.js"></script>
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
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // 農場の登録情報を mst_farmer から取得
                $sql = 'SELECT id_farmer,name_farmer,name_product,quantity,postal1,postal2,address,mail,tel,fax,comment From mst_farmer WHERE 1';

                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                $dbh = null;

                print '農場一覧<br>';
                print '<form method="POST" action="">';
                print '<table id="tbl1" align="center" border="1">';
                print '<tr class="font_center"><td></td>
        <td>No</td>
        <td>農場名</td>
        <td>商品</td>
        <td>個数</td>
        <td>郵便番号</td>
        <td>住所</td>
        <td>メール</td>
        <td>電話番号</td>
        <td>FAX</td>
        <td>備考</td>
        </tr><br />';

                while (true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコード取り出す
                    if ($rec == false) { //データがなければbreak
                        break;
                    }
                    print '<tr class="font_center"><th class="radio">';
                    print '<input type="radio" name="id_farmer" value="' . $rec['id_farmer'] . '"></th></td>';
                    print '<td>' . $rec['id_farmer'] . '</td>';
                    print '<td>' . $rec['name_farmer'] . '</td><td>' . $rec['name_product'] . '</td><td>' . $rec['quantity'] . '</td><td>' . $rec['postal1'] . ' - ' . $rec['postal2'] . '</td><td>' . $rec['address'] . '</td><td>' . $rec['mail'] . '</td><td>' . $rec['tel'] . '</td><td>' . $rec['fax'] . '</td><td>' . $rec['comment'] . '</td>';
                    print '</tr>';
                }
                print '</table><br />';
                print '</form>';
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                echo 'mysqli_sql_exception' . $e->getMessage(); //エラー表示用コード
                exit();
            }
            ?>
            <a href="../staff_login/product_top.php">戻る</a><br />
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>