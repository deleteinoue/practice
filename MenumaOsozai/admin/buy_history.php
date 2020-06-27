<?php
//セッション＝サーバーが持っている保存領域
session_start();

require_once("../config/config.php");
require_once("../model/User.php");

//ログアウト処理
if(isset($_GET['logout'])) {
  //セッション情報を破棄する
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか確認する
if(!isset($_SESSION['User'])) {
  header('Location: /MenumaOsozai/login/login.php');
  //画面を遷移したら必ずexit()で一旦処理を止めること。
  exit;
}

try{
  //接続処理
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDb();

  if($_GET['id']) {
    $result = $user->findBuyHistory($_GET['id']);
    // print_r($result);
  }
}
catch (PDOException $e) {
  echo "エラー: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>購入履歴| まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
</head>
<body>
  <div id="outerFrame">
    <?php
    require("../common/header_link.php");
    ?>
    <div id ="innerFrame">
      <div id="centerFrame">
        <section class="user">
          <h2><?=$_SESSION['User']['name']?>さんの購入履歴</h2>
          <table border="1">
            <tr>
              <th>商品ID</th>
              <th>商品名</th>
              <th>購入個数</th>
              <th>購入日時</th>
            </tr>
            <?php foreach ($result as $row): ?>
              <tr>
                <td><?=$row['product_id']?></td>
                <td><?=$row['pname']?></td>
                <td><?=$row['num']?></td>
                <td><?=$row['created']?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <p><a href="../products/index.php">メインページへ戻る</a></p>
        </section>
      </div><!-- centerFrame -->
    </div><!-- innerFrame -->
    <?php
    require("../common/footer.php");
    ?>
  </div><!-- outerFrame -->
</body>
</html>
