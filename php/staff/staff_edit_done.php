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

      try { //try は <?php のすぐ下に書く
        //クエリを変数にコピー
        $post = sanitize($_POST);
        $staff_code = $post['code'];
        $staff_name = $post['name'];
        $staff_pass = $post['pass'];

        //入力データの安全性チェック
        // $staff_name = htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8');
        // $staff_pass = htmlspecialchars($staff_pass, ENT_QUOTES, 'UTF-8');

        //データベースに接続
        //※DB接続1行目の文中にはスペース禁止！！
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //すでにそんざいしているレコードの内容を上書き修正するSQL文
        $sql = 'UPDATE mst_staff SET name=?,password=? WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $staff_name;
        $data[] = $staff_pass;
        $data[] = $staff_code;
        $stmt->execute($data);

        //データベースから切断 「接続→指令→切断」
        $dbh = null;

        //DBに障害発生した場合、catch以下の処理がなされる
      } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit(); //強制終了
      }
      ?>

      修正しました。<br />
      <br />
      <a href="staff_list.php">戻る</a>
    </main>
  </div>
  <script src="./js/script.js"></script>
</body>

</html>

<!-- staff_add_done.php -->
<!--
１．スタッフ名とパスワードを受け取り、安全策を施す
２．データベースに接続
３．SQL文を使い、データベースにデータを追加
４．データベースから切断
５．判り易くする為に「〇〇さんを追加しました。」と表示
６．データベースがダウンした際のために安全策としてtry~catchを使用
７．この先に作成するスタッフ一覧画面 staff_list.php へリンクを張る

「データの操作方法４つ」
１．データの参照  ←1件のデータの詳細を画面に表示する、など
２．データの追加
３．データの修正  ←このPG
４．データの削除

対象となるデータを、「レコード」という。

「データベースの基本」
接続→指令→切断
 -->