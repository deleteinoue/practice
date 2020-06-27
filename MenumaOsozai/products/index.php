<?php
//start session
session_start();
// var_dump($_SESSION);

require_once("../config/config.php");
require_once("../model/Product.php");

//ログアウト処理
if(isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか確認する
if(!isset($_SESSION['User'])) {
  header('Location: /MenumaOsozai/login/login.php');
  exit;
}



try{
  //接続処理
  $product = new Product($host, $dbname, $user, $pass);
  $product->connectDb();

  //購入商品表示
  if($_POST) {
    // if($_POST['pid'] != 0){
    $buying = []; // 配列を宣言しておく
    // print_r($_POST);
    foreach($_POST as $key => $num){
      if($num > 0){
        $data = array('product_id'=>$key);
        // print_r($data);
        $result = $product->findById($data);
        $buying[] = $result;
      }
    }
  }

  // print_r($buying);
  if(isset($_POST['1st_button'])){

    $_SESSION['cart'] = $buying;
    $hoge = $_SESSION['cart'];
    // print_r($hoge[0]['pid']);

    foreach ($hoge as $pid){
      $data = $pid['pname'];
    }
  }

  //製品表表示
  $result = $product->getData();


}
catch (PDOException $e) {
  echo "エラー: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

  <!-- Bootstrap CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
  <div id="outerFrame">
    <?php
    require("../common/header_link.php");
    ?>
    <div id ="innerFrame">
      <div id="centerFrame">
        <h1>ようこそ<?=$_SESSION['User']['name'] ?>さん！</h1>
        <a href = "../admin/buy_history.php?id=<?=$_SESSION['User']["id"]?>"><?=$_SESSION['User']['name'] ?>さんの購入履歴を確認する</a>
        <div id = "banner">
          <div id = "banner_wrapper">
            <img class="center" alt="Banner" src="../img/banner.jpg">
          </div>
        </div><!-- banner -->
        <section class="user">
          <h1>商品一覧</h1>
          <?php if($_SESSION['User']['role'] != 0) :?>
            <p><a href="../admin/user_management.php">ユーザ管理</a></p>
            <!-- ↓商品の追加、削除はカリキュラム終了後着手 -->
            <!-- <p><a href="../admin/user_management.php">ショップ管理</a></p> -->
          <?php endif; ?>
          <div class="container">
            <!-- 注）py＝padding-y my = margin--->

            <div class = "product">
              <form action="index.php" method="post">
                <table border="1">
                  <tr>
                    <th>商品ナンバー</th>
                    <th>商品名</th>
                    <th>商品イメージ</th>
                    <th>価格</th>
                    <th></th>
                  </tr>
                  <?php foreach ($result as $row): ?>
                    <tr>
                      <td><?=$row['pid']?></td>
                      <td><?=$row['pname']?></td>
                      <td><img src="../img/<?=$row['image']?>" alt="おそうざい" class="menu_img"></td>
                      <td width="15%"><?=$row['price']?> 円</td>
                      <td>
                        <select name="<?=$row['pid']?>">
                          <option value="0">買わない</option>
                          <option value="1">買　う！</option>
                        </select>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
                <div class="center">
                  <input type="submit" name="1st_button" class="btn btn-warning my-3" value="◎購入したい　おそうざいの右端プルダウンを　”買　う！”に変更して　この黄色いボタンを　クリックしてください！">
                </div>
              </form>

              <?php
              if(isset($_POST['1st_button'])){
                if(!empty($data)){
                  ?>

                  <form action="cart.php" method="post">
                    <p>
                      <?php foreach ($hoge as $pid){
                        $data = $pid['pname'];
                        // print_r($key);
                        // print_r($pid['pid']);
                        echo $data;
                      }?>
                      をお買い求めですね？<br>
                      よろしければ、下記『お買い物かごへ』ボタンを押してください。
                    </p>
                    <input type="submit" class="btn btn-warning my-3" value="お買い物かごへ">
                  </form>

                  <?php
                }else{
                  echo "<script>alert('商品を１つ以上選んでからお買い物かごをクリックしてください。あと、商品を選んだあとの２度押しもNGです。もう一度商品を選択し直してください。')</script>";
                  echo "<script>window.location = 'index.php'</script>";
                }
              }
              ?>

            </div>
          </div>

        </section>

      </div><!-- centerFrame -->
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
