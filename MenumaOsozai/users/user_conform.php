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
  <title>登録確認 | まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
</head>
<div id="outerFrame">
  <?php
  require("../common/header_nolink.php");
  ?>
  <div id ="innerFrame">
    <div id="centerFrame">
        <section class="user">
          <h1>登　録　内　容　確　認</h1>
          <h2>下記の内容で登録してよろしいでしょうか？</h2>
          <form action="user_complete.php" method="post">
            <table class="tbl" border="1">
              <tbody>
              <tr>
                <th>氏　名</th>
                <th>フリガナ</th>
                <th>電話番号</th>
                <th>メールアドレス</th>
                <th>(ログイン用)ユーザID</th>
                <th>パスワード</th>
              </tr>
              <tr>
                <td><?php echo $_POST["name"]; ?></td>
                <td><?php echo $_POST["furigana"]; ?></td>
                <td><?php echo $_POST["tel"]; ?></td>
                <td><?php echo $_POST["email"]; ?></td>
                <td><?php echo $_POST["login_id"]; ?></td>
                <td><?php echo $_POST["password"]; ?></td>
              </tr>
              </tbody>
            </table>
            <input type="hidden" name="name" value="<?php echo $_POST["name"];?>">
            <input type="hidden" name="furigana" value="<?php echo $_POST["furigana"];?>">
            <input type="hidden" name="tel" value="<?php echo $_POST["tel"];?>">
            <input type="hidden" name="email" value="<?php echo $_POST["email"];?>">
            <input type="hidden" name="login_id" value="<?php echo $_POST["login_id"];?>">
            <input type="hidden" name="password" value="<?php echo $_POST["password"];?>">
            <input type="submit" name="submit" value="登　録　す　る">
          </form>
          <a href="../login/login.php">ログインページへ戻る</a>
        </section>
      </div><!-- centerFrame -->
    </div><!-- innerFrame -->
    <?php
    require("../common/footer.php");
    ?>
  </div><!-- outerFrame -->
</body>
</html>
