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
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="../css/style_pro.css">
    <link rel="stylesheet" href="../css/normalize.css">
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.4.min.js"></script>
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
        <header class="header">
        <ul class="global-nav">
                <li><a href="../staff_login/staff_top.php">トップ</a></li>
                <li><a class="" href="../staff/staff_list.php">スタッフ管理</a></li>
                <li><a href="../staff_login/product_top.php">商品管理</a></li>
                <li><a href="../customer/customer_list.php">お客様管理</a></li>
                <li><a href="../order/order_download.php">注文ダウンロード</a></li>
                <li><a href="staff_logout.php">ログアウト</a></li>
            </ul>
        </header>
        <main class="main">
            <?php
            try {
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //スタッフの名前を要求するSQL文
                $sql = 'SELECT code,name From mst_staff WHERE 1';   //スタッフコードも取得する
                $stmt = $dbh->prepare($sql);
                $stmt->execute(); //この命令が終了した時点で＄stmtの中にはすべてのデータが入っている。

                $dbh = null;

                print 'スタッフ一覧';
                print '<form method="POST" action="staff_branch.php">'; //分岐画面に飛ぶ

                //スタッフ名を＄stmtから１レコードずつ取り出し、表示。データがなくなれば脱出するwhile文
                print '<br />';
                print '<table id="tbl1" align="center" border="1">';
                while (true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコード取り出す
                    if ($rec == false) { //データがなければbreak
                        break;
                    }
                    print '<tr><th class="radio">';
                    // print '<td>';
                    print '<input type="radio" name="staffcode" value="' . $rec['code'] . '">'; //どのスタッフを選んだか、リンク先で分かるようにスタッフコードを渡している
                    print '</th>';
                    print '<td>';
                    print $rec['name'];
                    print '</td></tr>';
                }
                print '</table>';
                print '<br />';

                print '<input type="submit" name="disp" value="参照">&emsp;';
                print '<input type="submit" name="add" value="追加">&emsp;';
                print '<input type="submit" name="edit" value="修正">&emsp;';
                print '<input type="submit" name="delete" value="削除">';
                print '</form>';
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            <!-- <a href="../staff_login/staff_top.php">トップメニューへ</a><br /> -->
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