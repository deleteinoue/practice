<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録 | まちのおそうざいやさん</title>
  <link rel="stylesheet" type="text/css" href="../css/base.css">
  <link rel="stylesheet" type="text/css" href="../css/user_management.css">
</head>
<div id="outerFrame">
  <?php
  require("../common/header_nolink.php");
  ?>
<div id ="innerFrame">
  <div id="centerFrame">
    <section class="user_join">
      <h1>新　規　登　録</h1>
      <h3>下記の項目をご記入の上、登録ボタンを押してください。</h3>
      <p>送信頂いた個人情報につきましては、当店より折り返しご連絡を差し上げます。<br>
        なお、ご登録までに、お時間を頂く場合もございますので予めご了承ください。<br>
      <span style="color:red">*</span>は必須項目となります。</p>
      <!-- ↓必須項目を記入し、エラーが発生したら入力内容が赤文字で出る。 -->
      <form action="user_conform.php" method="post">
        <table border="1" class="tbl">
          <tbody>
            <tr>
              <th>氏名<span style="color:red">*</span></th>
              <td><input type="text" name="name" required></td>
            </tr>
            <tr>
              <th>フリガナ<span style="color:red">*</span></th>
              <td><input type="text" name="furigana" required></td>
            </tr>
            <tr>
              <th>電話番号<span style="color:red">*</span></th>
              <td><input type="text" name="tel" required></td>
            </tr>
            <tr>
              <th>メールアドレス</th>
              <td><input type="text" name="email"></td>
            </tr>
            <tr>
              <th>(ログイン用)ユーザID<span style="color:red">*</span></th>
              <td><input type="text" name="login_id" required></td>
            </tr>
            <tr>
              <th>パスワード<span style="color:red">*</span></th>
              <td><input type="password" name="password" required></td>
            </tr>
          </tbody>
        </table>
        <input type="submit" name="register" value="登　録" id="submit_btn">
      </form>
      <p><a href="../login/login.php">ログインページへ戻る</a></p>
    </section>
  </div><!-- contact box -->
</div><!-- contact -->
<?php
require("../common/footer.php");
?>
</div><!-- wrapper -->
</body>
</html>
