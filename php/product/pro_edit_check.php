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
</head>

<body>
    <div class="container">
        <header class="item header">
            <ul>
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
            require_once('../common/common.php');

            $post = sanitize($_POST);
            $pro_code = $post['code'];
            $pro_name = $post['name'];
            $pro_price = $post['price'];
            $pro_image_name_old = $post['image_name_old'];
            $pro_image = $_FILES['image'];

            $pro_image_name_old_filename = "/image/{$pro_image_name_old}";

            // $pro_code = htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8');
            // $pro_name = htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8');
            // $pro_price = htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8');

            //入力チェック
            // if ($pro_name == '') {
            //     print '商品名が未入力です。<br />';
            // } else {
            //     print '商品名：';
            //     print $pro_name;
            //     print '<br />';
            // }
            print '<table align="center" border="1">';

            if (!($pro_name == '')) {
                print '<tr><td>商品名：</td>';
                print '<td class="font_center">' . $pro_name . '</td>';
            } else {
                print '<tr><td>商品名が未入力です。</tr></td>';
            }

            // 画像サイズチェック
            if ($pro_image['size'] > 0) {
                if ($pro_image['size'] > 1000000) {
                    print '<tr>画像が大き過ぎます</tr>';
                } else {
                    // 0219追加：確認時に画像も表示する

                    //0221 画像を変更しない場合の処理（未完成）
                    if (isset($pro_image) && (!file_exists($pro_image_name_old_filename))) {
                        move_uploaded_file($pro_image['tmp_name'], './image/' . $pro_image['name']);
                        $disp_image = '<img src="./image/' . $pro_image['name'] . '">';
                    } elseif (file_exists($pro_image_name_old_filename)) {
                        $disp_image = '<img src="./image/' . $post['image_name_old'] . '">';
                        print "aaaaa";
                    }

                    // $disp_image = '<img src="./image/' . $pro_image['name'] . '">';
                    print '<tr><td>選択画像：</td><td>' . $disp_image . '</td></tr>';
                }
            }

            if (preg_match('/^[0-9]+$/', $pro_price) == 0 || $pro_image['size'] > 1000000) {
                print '<tr><td>価格を正確に入力してください。</td></tr>';
            } else {
                print '<tr><td>価格：</td>';
                print '<td class="font_center">' . $pro_price;
                print '円</td></tr>';
            }

            print '</table><br />';

            if ($pro_name == '' || preg_match('/^[0-9]+$/', $pro_price) == 0) {
                print '<form>';
                print '<input type="button" onclick="history.back()" value="戻る">';
                print '</form>';
            } else {
                print '上記のように変更します。<br />';
                print '<form method="post" action="pro_edit_done.php">';
                print '<input type="hidden" name="code" value="' . $pro_code . '">';
                print '<input type="hidden" name="name" value="' . $pro_name . '">';
                print '<input type="hidden" name="price" value="' . $pro_price . '">';
                print '<input type="hidden" name="image_name_old" value="' . $pro_image_name_old . '">';
                print '<input type="hidden" name="image_name" value="' . $pro_image['name'] . '">';
                print '<br />';
                print '<input type="button" onclick="history.back()" value="戻る">&emsp;';
                print '<input type="submit" value="OK">';
                print '<form />';
            }
            ?>
        </main>
    </div>
    <script src="./js/script.js"></script>
</body>

</html>