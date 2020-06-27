<?php
session_start();

require_once("../config/config.php");
require_once("../model/Product.php");

//ログアウト処理
if(isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか確認する
if(!isset($_SESSION['User'])) {
  header('Location: /MenumaOsozai/products/cart.php');
  exit;
}

try{
  //接続処理
  $product = new Product($host, $dbname, $user, $pass);
  $product->connectDb();

  //以下要編集

  if($_POST) {
    foreach($_POST as $key => $num){
      if($num > 0){
        $data = array('user_id'=>$_SESSION['User']['id'], 'product_id'=>$key, 'num'=>$num);
        $product->buyProduct($data);
      }
    }
  }

  $result = $product->getData();




}
catch (PDOException $e) {
  echo "エラー: " . $e->getMessage();
}

?>

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>購入完了 | まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/complete.css">
</head>
<div id="outerFrame">
  <?php
  require("../common/header_link.php");
  ?>
  <div id ="innerFrame">
    <div id="centerFrame">
      <section class="user">
        <h1>購　入　確　認</h1>
        <p>ご購入完了しました！！これからそちらへお届けに参ります！<br>
          当店をご利用いただきまして、誠にありがとうございました！<br>
          またのご利用お待ちしております！</p>
          <a href = "../admin/buy_history.php?id=<?=$_SESSION['User']["id"]?>"><?=$_SESSION['User']['name'] ?>さんの購入履歴を確認する</a>
          <a href="index.php">商品一覧へ戻る</a>
        </section>
      </div><!-- centerFrame -->
    </div><!-- innerFrame -->
    <?php
    require("../common/footer.php");
    ?>
  </div><!-- outerFrame -->
  <form action="" method="post">
    <input type="hidden" name="user_id" value="<?php echo $_POST["user_id"];?>">
    <input type="hidden" name="product_id" value="<?php echo $_POST["product_id"];?>">
    <input type="hidden" name="num" value="<?php echo $_POST["num"];?>">
  </form>
</body>
</html>
