<?php
session_start();
session_regenerate_id(true);
// P308 会員ログインしてない場合、商品一覧へ戻る
if (isset($_SESSION['member_login']) == false) {
    print 'ログインされていません。<br />';
    print '<a href="shop_list.php">商品一覧へ</a>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
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
            // P308 DBから会員情報を取り出す
            $code = $_SESSION['member_code'];
            $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT name,email,postal1,postal2,address,tel FROM dat_member WHERE code=?';
            $stmt = $dbh->prepare($sql);
            $data[] = $code;
            $stmt->execute($data);
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);

            $dbh = null;

            // P308追加部分
            // DBから抽出したデータを変数に代入
            $name = $rec['name'];
            $email = $rec['email'];
            $postal1 = $rec['postal1'];
            $postal2 = $rec['postal2'];
            $address = $rec['address'];
            $tel = $rec['tel'];

            print '<table align="center" border="1">';
            print '<tr><td>';
            print 'お名前</td>';
            print '<td>' . $name . ' 様' . '</td></tr>';

            print '<tr><td>メールアドレス</td>';
            print '<td>' . $email . '</td></tr>';

            print '<tr><td>郵便番号</td>';
            print '<td>' . $postal1;
            print '-';
            print $postal2 . '</td></tr>';

            print '<tr><td>住所</td>';
            print '<td>' . $address . '</td></tr>';

            print '<tr><td>電話番号</td>';
            print '<td>' . $tel . '</td></tr>';

            print '</table><br />';

            print '<form method="POST" action="shop_kantan_done.php">';
            print '<input type="hidden" name="user_name" value="' . $name . '">';
            print '<input type="hidden" name="user_email" value="' . $email . '">';
            print '<input type="hidden" name="postal1" value="' . $postal1 . '">';
            print '<input type="hidden" name="postal2" value="' . $postal2 . '">';
            print '<input type="hidden" name="user_address" value="' . $address . '">';
            print '<input type="hidden" name="user_tel" value="' . $tel . '">';
            // 2.11 追加部分
            print '<input type="button" onclick="history.back()" value="戻る">&emsp;';
            print '<input type="submit" value="注文">';
            print '</form>';

            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>