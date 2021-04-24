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
        <?php
        try {
            $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //商品の名前、価格を要求するSQL文
            $sql = 'SELECT code,name,image,price From mst_product WHERE 1';   //商品コードも取得する
            $stmt = $dbh->prepare($sql);
            $stmt->execute(); //この命令が終了した時点で$stmtの中にはすべてのデータが入っている。

            $dbh = null;
            print '商品一覧<br /><br />';
            print '<table align="center" border="1">';
            print '<tr><td class="font_center"><b>商品名</b></td><td class="font_center"><b>商品画像</b></td><td class="font_center"><b>商品価格</b></td></tr>';



            //スタッフ名を＄stmtから１レコードずつ取り出し、表示。データがなくなれば脱出するwhile文
            while (true) {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコード取り出す
                $pro_image_name = $rec['image'];
                if ($rec == false) { //データがなければbreak
                    break;
                }
                // URLに商品コードを挿入し、商品名クリックで -- に飛ぶ
                print '<tr>';
                print '<td>';
                print '<a href="shop_product.php?procode=' . $rec['code'] . '">';
                print $rec['name'];
                print '</td>';
                print '<td>';
                if ($pro_image_name == '') {
                    print 'no img';
                } else {
                    print '<img src="../product/image/' . $pro_image_name . '">';
                }
                print '</td>';
                print '<td class="font_right">';
                print $rec['price'] . '円';
                print '</td>';
                print '</a>';
            }
            print '</table>';
        } catch (Exception $e) {
            print 'ただいま障害により大変ご迷惑をお掛けしております。';
            exit();
        }
        ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>