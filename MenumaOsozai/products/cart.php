<?php
session_start();

require_once("../config/config.php");
require_once("../model/Product.php");

//ログアウト処理
if(isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
}

// 商品一覧画面を経由しているか確認する
if(!isset($_SESSION['cart'])) {
  header('Location: /MenumaOsozai/products/index.php');
  exit;
}

// print_r($_SESSION);

try{
  //接続処理
  $product = new Product($host, $dbname, $user, $pass);
  $product->connectDb();

  //商品選択の有無チェック
  if(isset($_SESSION['cart'])){
    $hoge = $_SESSION['cart'];

  }else{
    echo "<script>alert('まだお買い物かごは空です。買いたい商品を選択してください！')</script>";
    echo "<script>window.location = 'index.php'</script>";
  }


  //商品購入
  if($_POST) {
    foreach($_POST as $key => $num){
      if($num > 0){
        $data = array('user_id'=>$_SESSION['User']['id'], 'product_id'=>$key, 'num'=>$num);
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        $product->buyProduct($data);

      }
    }
  }

  //カートから商品を取り除く(カリキュラム終了後着手)
  // if (isset($_POST['remove'])){
  //   if ($_GET['action'] == 'remove'){
  //     foreach ($_SESSION['cart'] as $key => $value){
  //       if($value["pid"] == $_GET['id']){
  //         unset($_SESSION['cart'][$key]);
  //         echo "<script>alert('商品はもうお買い物かごの中にありません!OKをクリックして画面を更新してください！')</script>";
  //         echo "<script>window.location = 'cart.php'</script>";
  //       }
  //     }
  //   }
  // }

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
  <title>お買い物かご | まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/cart.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

  <!-- Bootstrap CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<div id="outerFrame">
  <?php
  require("../common/header_nolink.php");
  ?>
  <div id ="innerFrame">
    <div id="centerFrame">
      <h1>お買い物かご（購入するものを確認）</h1>
      <div class="container-fluid">
        <div class="px-5">
          <div>
            <div class="shopping-cart">
              <hr>
              <form action="complete.php" method="post" class="cart-items">
                <?php foreach ($hoge as $row): ?>
                  <div>
                    <div class="row bg-white border">
                      <input type="hidden" value="<?=$row['id']?>">
                      <div class="col-md-3 pl-0">
                        <img src=../img/<?=$row['image']?> alt="おそうざい" class="menu_img">
                      </div>
                      <div class="col-md-6">
                        <h5 class="pt-2"><?=$row['pname']?></h5>
                        <small class="text-secondary">配送料: 無料</small>
                        <h5 class="pt-2">￥<?=$row['price']?></h5>
                        <select name="<?=$row['pid']?>">
                          <option value="1">１つ</option>
                          <option value="2">２つ</option>
                          <option value="3">３つ</option>
                          <option value="4">４つ</option>
                          <option value="5">５つ</option>
                          <option value="6">６つ</option>
                          <option value="7">７つ</option>
                          <option value="8">８つ</option>
                          <option value="9">９つ</option>
                        </select>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                <input type="submit" class="btn btn-warning my-3" value="購　入　す　る">
              </form>
            </div>
          </div>


          <!-- お会計表示はカリキュラム終了後着手 -->


        </div>
      </div>
    </div><!-- centerFrame -->
    <a href="index.php">商品一覧へ戻る</a>
  </div><!-- innerFrame -->
  <?php
  require("../common/footer.php");
  ?>
</div><!-- outerFrame -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
