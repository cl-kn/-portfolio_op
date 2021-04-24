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

      try {
        $post = sanitize($_POST);

        $pro_code = $post['code'];
        $pro_name = $post['name'];
        $pro_price = $post['price'];
        $pro_image_name_old = $post['image_name_old'];
        $pro_image_name = $post['image_name'];

        //入力データの安全性チェック
        // $pro_code = htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8');
        // $pro_name = htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8');
        // $pro_price = htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8');

        //データベースに接続
        //※DB接続1行目の文中にはスペース禁止
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //SQL文を使いレコードを追加
        $sql = 'UPDATE mst_product SET name=? ,price=? , image=?  WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $pro_name;
        $data[] = $pro_price;
        $data[] = $pro_image_name;
        $data[] = $pro_code;
        $stmt->execute($data);

        //データベースから切断 「接続→指令→切断」
        $dbh = null;

        // 古い画像があるとき削除
        if ($pro_image_name_old != $pro_image_name) {
          if ($pro_image_name_old != '') {
            unlink('./image/' . $pro_image_name_old);
          }
        }

        //画面に「〇〇さんを修正しました」と表示
        print '修正しました。<br /><br />';

        //DBに障害発生した場合、catch以下の処理がなされる
      } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit(); //強制終了
      }

      ?>

      <a href="pro_list.php">戻る</a>
    </main>
  </div>
  <script src="./js/script.js"></script>
</body>

</html>

<!--
このスクリプトには2種類の脆弱性がある。
１：任意のPHPスクリプトをアップロードできてしまうことによる「スクリプト実行脆弱性」

２：任意のHTMLをアップロードできてしまうことによる「JavaScript実行」
＝XSSと同等

 -->