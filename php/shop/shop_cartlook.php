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

                //[2/4] P221 カートが空の場合の処理、is_countable()追加
                if (isset($_SESSION['cart'])  == true) {
                    $cart = $_SESSION['cart']; //P198 保管済みのカートの中身を戻す
                    $number = $_SESSION['number']; // [2/4] P206 追加
                    $max = count($cart);
                } else {
                    $max = 0;
                }

                // if (is_countable($cart)) {
                //     $max = count($cart);
                // }else {
                //     $max = 0;
                // }

                //[2/4] P219 空のカート画面修正
                if ($max == 0) {
                    print 'カートに商品が入っていません。<br />';
                    print '<br />';
                    print '<a href="shop_list.php">商品一覧へ戻る</a>';
                    exit();
                }

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                foreach ($cart as $key => $val) {
                    $sql = 'SELECT code, name, price, image FROM mst_product WHERE code=?';
                    $stmt = $dbh->prepare($sql);
                    $data[0] = $val;
                    $stmt->execute($data);

                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                    //配列にDBの要素を挿入
                    $pro_name[] = $rec['name'];
                    $pro_price[] = $rec['price'];
                    if ($rec['image'] == '') {
                        $pro_image[] = '';
                    } else {
                        $pro_image[] = '<img src="../product/image/' . $rec['image'] . '">';
                    }
                    $dbn = null;
                }
            } catch (Exception $e) {
                print 'ただいま障害により大変ご迷惑をお掛けしております。';
                exit();
            }

            ?>

            カートの中身 <br />
            <br />
            <form method="POST" action="number_change.php">

                <!-- 2/4 P227 テーブル追加 -->
                <table align="center" border="1">
                    <tr>
                        <td class="font_center"><b>商品</b></td>
                        <td class="font_center"><b>商品画像</b></td>
                        <td class="font_center"><b>価格</b></td>
                        <td class="font_center"><b>数量</b></td>
                        <td class="font_center"><b>小計</b></td>
                        <td class="font_center"><b>削除</b></td>
                    </tr>
                    <?php for ($i = 0; $i < $max; $i++) { ?>

                        <tr>
                            <td><?php print $pro_name[$i]; ?></td>
                            <td><?php print $pro_image[$i]; ?></td>
                            <td><?php print $pro_price[$i]; ?>円</td>
                            <td><input type="number" style="width:50px" name="number<?php print $i; ?>" value="<?php print $number[$i]; ?>">個</td>
                            <td><?php print $pro_price[$i] * $number[$i]; ?>円</td>
                            <td><input type="checkbox" name="delete<?php print $i; ?>"></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>

                <input type="hidden" name="max" value="<?php print $max; ?>"><br />
                <!-- 2/4 0204 戻る、を直接shop_list.php に戻れるように -->
                <input type="button" onclick="history.back()" value="戻る">&emsp;
                <input type="submit" value="数量変更">

            </form>
            <a href="shop_form.html" class="btn-square">ご購入手続きへ進む</a><br /><br />

            <!-- P306 会員かんたん注文へのリンク追加 -->
            <?php
            if (isset($_SESSION["member_login"]) == true) {
                print '<a href="shop_kantan_check.php" class="btn-square">会員かんたん注文へ進む</a><br />';
            }

            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>