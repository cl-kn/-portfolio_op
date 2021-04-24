<?php
session_start();
session_regenerate_id(true); //セッションハイジャック対策
// $_SESSION に値が空の場合、ログイン時画面を表示しない
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
                $pro_code = $_GET['procode'];

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = 'SELECT name,price,image FROM mst_product WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[] = $pro_code;
                $stmt->execute($data);

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $pro_name = $rec['name'];
                $pro_price = $rec['price'];
                $pro_image_name = $rec['image'];

                $dbh = null;

                if ($pro_image_name == '') {
                    $disp_image = '画像はありません';
                } else {
                    $disp_image = '<img src="../product/image/' . $pro_image_name . '">';
                }
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }
            ?>

            商品詳細
            <br />
            <table align="center" border="1">
                <tr>
                    <td>商品コード</td>
                    <td class="font_right"><?php print $pro_code; ?></td>
                    <br />
                </tr>
                <tr>
                    <td>商品名</td>
                    <td class="font_right"><?php print $pro_name; ?></td>
                </tr>
                <tr>
                    <td>価格</td>
                    <td class="font_right"><?php print $pro_price; ?>円</td>
                </tr>
                <tr>
                    <td>商品画像</td>
                    <td><?php print $disp_image; ?></td>
                </tr>
            </table>
            <br />
            <?php
            print '<a href="shop_cartin.php?procode=' . $pro_code . '" class="btn-square">カートに入れる</a><br /><br />';
            ?>
            <form>
                <input type="button" onclick="history.back()" value="戻る">
            </form>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>