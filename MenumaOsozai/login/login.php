<?php
session_start();

require_once("../config/config.php");
require_once("../model/User.php");

try {
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDb();

  if($_POST){
    $result = $user->login($_POST);
    if(!empty($result)) {
      $_SESSION['User'] = $result;
      header('Location: /MenumaOsozai/products/index.php');
      exit;
    }
    else{
      $message = "ログインできませんでした";
    }
  }
}
catch (PDOException $e) {
  echo "エラー！: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログインページ | まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/login_style.css">
</head>
<body>
  <div id="wrapper">
    <?php
		require("../common/header_nolink.php");
		?>
    <div id="form">
      <h1>ログインフォーム</h1>
      <p>ログインするには以下にユーザとパスワードを入力してください。</p>
      <?php if(isset($message)) echo "<p class='error'>".$message."</p>" ?>
    </div>

    <div id="login_form">
    <form  action="" method="post">
      <table>
        <tr>
          <th>ユーザ名</th>
          <td><input type="text" name="login_id" size="20"></td>
        </tr>
        <tr>
          <th>パスワード</th>
          <td><input type="password" name="password" size="20"></td>
        </tr>
      </table>
      <p><input type="submit" value="送　信"></p>
    </form>
    <p><a href="../users/user_add.php">新規登録はこちらから</a></p>
  </div>
  <?php
  require("../common/footer.php");
  ?>

  </div><!-- wrapper -->
</body>
</html>
