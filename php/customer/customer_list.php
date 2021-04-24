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
    <script src="../js/tr_background_color.js"></script>
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
            // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // エラー表示用コード
            try {
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // お客様の登録情報を dat_member から取得
                $sql = 'SELECT code,name,email,postal1,postal2,address,tel,sex,born From dat_member WHERE 1';

                $stmt = $dbh->prepare($sql);
                $stmt->execute();

                $dbh = null;

                print 'お客様一覧';
                print '<form method="POST" action="customer_branch.php">';
                print '<table id="tbl1" align="center" border="1">';
                print '<tr class="font_center"><td></td>
            <td>会員コード</td>
            <td>氏名</td>
            <td>メールアドレス</td>
            <td>郵便番号</td>
            <td>住所</td>
            <td>電話番号</td>
            <td>性別</td>
            <td>生まれ年</td>
            </tr><br />';

                while (true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコード取り出す
                    if ($rec == false) { //データがなければbreak
                        break;
                    }
                    print '<tr class="font_center"><th class="radio">';
                    print '<input type="radio" name="member_code" value="' . $rec['code'] . '"></th></td>';
                    print '<td>' . $rec['code'] . '</td>';

                    print '<td>' . $rec['name'] . '</td><td>' . $rec['email'] . '</td><td>' . $rec['postal1'] . ' - ' . $rec['postal2'] . '</td><td>' . $rec['address'] . '</td><td>' . $rec['tel'] . '</td>';

                    if ($rec['sex'] == 1) {
                        $sex = '女';
                    } else {
                        $sex = '男';
                    }

                    print '<td>' . $sex . '</td>';
                    print '<td>' . $rec['born'] . '</td>';
                    print '</tr>';
                }
                print '</table><br />';
                print '<input type="submit" name="buy" value="購入履歴">';
                print '</form>';
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                // echo 'mysqli_sql_exception' . $e->getMessage(); //エラー表示用コード
                exit();
            }
            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>