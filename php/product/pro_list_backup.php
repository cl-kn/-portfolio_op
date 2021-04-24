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
</head>

<body>
    <?php
    try {
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //商品の名前、価格を要求するSQL文
        $sql = 'SELECT code,name,price From mst_product WHERE 1';   //商品コードも取得する
        $stmt = $dbh->prepare($sql);
        $stmt->execute(); //この命令が終了した時点で$stmtの中にはすべてのデータが入っている。

        $dbh = null;

        print '商品一覧 <br /><br />';

        print '<form method="POST" action="pro_branch.php">'; //分岐画面に飛ぶ

        //スタッフ名を＄stmtから１レコードずつ取り出し、表示。データがなくなれば脱出するwhile文
        while (true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコード取り出す
            if ($rec == false) { //データがなければbreak
                break;
            }

            //テーブルに入れるには？

            print '<input type="radio" name="procode" value="' . $rec['code'] . '">'; //どのリンクを選んだか、リンク先で分かるようにスタッフコードを渡している
            print $rec['name'] . '---';
            print $rec['price'] . '円';
            print '<br />';
        }

        print '<input type="submit" name="disp" value="参照">';
        print '<input type="submit" name="add" value="追加">';
        print '<input type="submit" name="edit" value="修正">';
        print '<input type="submit" name="delete" value="削除">';
        print '</form>';
    } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }
    ?>

    <br />
    <a href="../staff_login/staff_top.php">トップメニューへ</a><br />

</body>

</html>

<!-- SELECT文

'SELECT name From mst_staff WHERE 1'

SELECT name     データ選択
From mst_staff  どのテーブルから？[mst_staff]テーブルから
WHERE1          どんなふうに？ [1]は全て、の意


---分岐画面 staff_branch.php に飛ばす---
・分岐画面に飛ばすためにリンク先を変更
print '<form method="POST" action="staff_branch.php">';

・name="edit", name="delete" を追加し、リンク先の staff_branch.php でどのボタンが押されたか区別する
-->