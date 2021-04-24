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
    <link rel="stylesheet" href="../css/style_pro.css">
</head>

<body>
    <div class="inner">
        <?php
        try {
            $pro_code = $_GET['procode']; //受け取った商品コードのスタッフデータを修正していく

            //商品コードをカート配列に入れる
            if (isset($_SESSION['cart']) == true) {
                $cart = $_SESSION['cart']; //P193 現在のカート内容を$cartにコピー
                $number = $_SESSION['number'];

                // [2/4] P223 商品重複チェック
                if (in_array($pro_code, $cart) == true) {
                    print 'その商品はすでにカートに入っています。<br />';
                    print '<br />';
                    print '<a href="shop_list.php">商品一覧に戻る</a>';
                    exit();
                }
            }

            $cart[] = $pro_code; // [2/4] P190 カートに商品を追加
            $number[] = 1; //[2/4] P205 数量「１」を追加
            $_SESSION['cart'] = $cart; //[2/4] P190 SESSIONにカートを保存
            $_SESSION['number'] = $number; //[2/4] P205 後に取り出すように保管

        } catch (Exception $e) {
            print 'ただいま障害により大変ご迷惑をお掛けしております。';
            exit();
        }
        ?>

        カートに追加しました。<br />
        <br />
        <a href="shop_list.php">商品一覧に戻る</a>
    </div>
</body>

</html>