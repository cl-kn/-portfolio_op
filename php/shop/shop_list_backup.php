<?php
session_start();
session_regenerate_id(true);

if (isset($_SESSION['member_login']) == false) {

    print 'ようこそゲスト様';
    print '<a href="member_login.html"> 会員ログイン </a><br />';
    print '<br />';
} else {
    print 'ようこそ';
    print "[{$_SESSION['member_name']}]";
    print ' 様';
    print '<a href="member_logout.php">ログアウト</a><br />';
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

        //スタッフ名を＄stmtから１レコードずつ取り出し、表示。データがなくなれば脱出するwhile文
        while (true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコード取り出す
            if ($rec == false) { //データがなければbreak
                break;
            }
            // URLに商品コードを挿入し、商品名クリックで -- に飛ぶ
            print '<a href="shop_product.php?procode=' . $rec['code'] . '">';
            print $rec['name'] . '---';
            print $rec['price'] . '円';
            print '</a>';
            print '<br />';
        }

        print '<br />';
        print '<a href="shop_cartlook.php">カートを見る</a><br />';
    } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }
    ?>

</body>

</html>