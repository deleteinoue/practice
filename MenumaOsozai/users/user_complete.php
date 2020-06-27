<?php
require_once("../config/config.php");
require_once("../model/User.php");

try{
  //接続処理
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDb();

    //登録処理
    if(isset($_POST['submit'])) {
      $user->add($_POST);
      // echo "<p class='error' style='color:red;　font-size:30px;'>"."登録完了致しました！"."</p>";
      $result = $user->findAll();
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
  <title>登録完了 | まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/complete.css">
</head>
<div id="outerFrame">
  <?php
  require("../common/header_nolink.php");
  ?>
  <div id ="innerFrame">
    <div id="centerFrame">
        <section class="user">
          <h1>会　員　登　録　完　了</h1>
          <p>ご登録いただきありがとうございます！<br>
          下記リンクより当ページにログインすることが出来ます！</p>
            <a href="../login/login.php">ログインページへ戻る</a>
        </section>
      </div><!-- centerFrame -->
    </div><!-- innerFrame -->
    <?php
    require("../common/footer.php");
    ?>
  </div><!-- outerFrame -->
  <form action="" method="post">
    <input type="hidden" name="name" value="<?php echo $_POST["name"];?>">
    <input type="hidden" name="furigana" value="<?php echo $_POST["furigana"];?>">
    <input type="hidden" name="tel" value="<?php echo $_POST["tel"];?>">
    <input type="hidden" name="email" value="<?php echo $_POST["email"];?>">
    <input type="hidden" name"login_id" value="<?php echo $_POST["login_id"];?>">
    <input type="hidden" name"password" value="<?php echo md5($_POST["password"]);?>">
  </form>
</body>
</html>
