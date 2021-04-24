<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="../css/style_pro.css">
</head>

<body>
    <div class="inner">
        <?php

        print "〇×様<br />ご注文ありがとうございました。<br/ >";
        print "xxx@xxx.com にメールを送りましたのでご確認ください。<br/>";
        print '<br/>商品は以下の住所に発送させていただきます。<br/>';
        print '<br /><table align="center" border="1">';
        print "<tr><td>郵便番号</td><td>000 - 0000</td></tr>";
        print "<tr><td>住所</td><td>あああああ</td></tr>";
        print "<tr><td>電話番号</td><td>000-0000-0000</td></tr>";
        print '</table><br />';

        // P299 会員登録済み向けメッセージ表示
        print '会員登録が完了いたしました。<br />';
        print '次回からメールアドレスとでログインしてください。<br />';
        print 'ご注文が簡単にできるようになります。<br />';
        print '<br />';

        ?>
        <br />
        <a href="shop_list.php">商品画面へ</a>
    </div>
</body>

</html>