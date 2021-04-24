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

      try { //try は <?php のすぐ下に書く
        //クエリを変数にコピー
        $pro_code = $_POST['code'];
        $pro_image_name = $_POST['image_name'];

        //データベースに接続
        //※DB接続1行目の文中にはスペース禁止！！
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //すでに存在しているレコードの内容を削除するSQL文
        $sql = 'DELETE FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $pro_code;
        $stmt->execute($data);

        //データベースから切断 「接続→指令→切断」
        $dbh = null;

        if ($pro_image_name != '') {
          unlink('./image/' . $pro_image_name);
        }

        //DBに障害発生した場合、catch以下の処理がなされる
      } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit(); //強制終了
      }
      ?>

      削除しました。<br />
      <br />
      <a href="pro_list.php">戻る</a>
    </main>
  </div>
  <script src="./js/script.js"></script>
</body>

</html>

<!-- pro_delete_done.php -->
<!--
DELETE FROM mst_pro WHERE code=1

既に存在するレコードを削除するSQL文

※このテキストの商品削除機能には、
CSRF（クロスサイト・リクエスト・フォージェリ）脆弱性あり。
 -->