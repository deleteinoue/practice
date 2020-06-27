<?php
session_start();

require_once("../config/config.php");
require_once("../model/User.php");

//ログアウト処理
if(isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか確認する
if(!isset($_SESSION['User'])) {
  header('Location: /MenumaOsozai/products/index.php');
  exit;
}

try{
  $user = new User($host, $dbname, $user, $pass);
  $user->connectDb();

  //編集処理
  if(isset($_GET['edit'])) {

    //未入力赤文字アラート
    if($_POST) {
      $message = $user->validate($_POST);
      if(empty($message['name']) && empty($message['furigana']) && empty($message['tel']) && empty($message['login_id']) && empty($message['password'])){
        $user->edit($_POST);
      }
    }

    //編集対象参照処理
    $result['User'] = $user->findById($_GET['edit']);
    // print_r($result['User']);
    //送信ボタン押したら対象レコードだけでなく全レコード表示
    if(isset($_POST['submit'])) {
      $result = $user->findAll();
    }
  }

  //削除処理
  elseif(isset($_GET['del'])) {
    //(2)roleが0でなく、かつ(1)$_GET['del']＝idが一致していない場合⇒別のユーザの場合削除可能に。
    //↑つまり(1)ログインした本人がurlで"del1"と入力しちゃうと管理者アカウント等、レコードが削除できてしまうので、それを防ぐ。
    //かつ、そもそも(2)で管理人しか削除できないようにする。
    if($_SESSION['User']['role'] != 0) {
      if($_SESSION['User']['role'] != $_GET['del']) {
        //↑ifを２つ重ねる　＝　delete()出来るようにするための条件が２つある。
        $user->delete($_GET['del']);
      }

      //参照処理(更新後、画面則更新)
      $result = $user->findAll();
    }
  }

  //編集処理ではなく登録処理
  else{
    //登録処理
    if($_POST) {
      $message = $user->validate($_POST);
      //入力フォームが空だったら赤文字のエラーが出る
      if(empty($message['name']) && empty($message['furigana']) && empty($message['tel']) && empty($message['login_id']) && empty($message['password'])){
        $user->add($_POST);
      }
    }
    //管理者は一覧が見れる
    if($_SESSION['User']['role'] != 0) {
      //参照処理(更新後、画面則更新)
      $result = $user->findAll();
    }
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
      <?php if(isset($_GET['edit'])) :?>
        <section class="user_join">
          <h1>会員情報編集</h1>
          <h2>下記の表右端にある”編集”リンクをクリックの上、下記記入欄にて入力してください。</h2>
          <p><span style="color:red">*</span>は必須項目なので、空欄にしないこと。</p>
          <p><a href="../admin/user_management.php">ユーザ一覧へ戻る</a></p>
          <?php if(isset($message['name'])) echo "<p class='error'style='color:red;>".$message['name']."</p>" ?>
            <?php if(isset($message['furigana'])) echo "<p class='error'style='color:red;>".$message['furigana']."</p>" ?>
              <?php if(isset($message['tel'])) echo "<p class='error' style='color:red;'>".$message['tel']."</p>" ?>
                <?php if(isset($message['login_id'])) echo "<p class='error' style='color:red;>".$message['login_id']."</p>" ?>
                  <?php if(isset($message['password'])) echo "<p class='error' style='color:red;>".$message['password']."</p>" ?>
                    <form action="" method="post" name="form1">
                      <table class="tbl">
                        <tbody>
                          <tr>
                            <th>氏名<span style="color:red">*</span></th>
                            <td><input type="text" name="name" value="<?php if(isset($result['User'])) echo $result['User']['name']; ?>" required></td>
                          </tr>
                          <tr>
                            <th>フリガナ<span style="color:red">*</span></th>
                            <td><input type="text" name="furigana" value="<?php if(isset($result['User'])) echo $result['User']['furigana']; ?>" required></td>
                          </tr>
                          <tr>
                            <th>電話番号<span style="color:red">*</span></th>
                            <td><input type="text" name="tel" value="<?php if(isset($result['User'])) echo $result['User']['tel']; ?>" required></td>
                          </tr>
                          <tr>
                            <th>メールアドレス</th>
                            <td><input type="text" name="email" value="<?php if(isset($result['User'])) echo $result['User']['email']; ?>"></td>
                          </tr>
                          <tr>
                            <th>(ログイン用)ユーザID<span style="color:red">*</span></th>
                            <td><input type="text" name="login_id" value="<?php if(isset($result['User'])) echo $result['User']['login_id']; ?>" required></td>
                          </tr>
                          <tr>
                            <th>パスワード<span style="color:red">*</span></th>
                            <td><input type="password" name="password" value="<?php if(isset($result['User'])) echo $result['User']['password']; ?>" required></td>
                          </tr>
                          <tr>
                            <th>
                              <input type="hidden" name="id" value="<?php if(isset($result['User'])) echo $result['User']['id']; ?>">
                              <input type="submit" name="submit" value="更　新" id="submit_btn">
                            </th>
                          </tr>
                        </tbody>
                      </table>
                    </form>
                  </section>
                <?php endif; ?>
                <section class="user_management">
                  <h1>ユーザー管理</h1>
                  <table border='1'>
                    <tr>
                      <td>ID</td>
                      <td>氏　名</td>
                      <td>フリガナ</td>
                      <td>電話番号</td>
                      <td>メールアドレス</td>
                      <td>ユーザID</td>
                      <td>パスワード</td>
                      <td>権限</td>
                      <td>加入日時</td>
                      <td></td>
                    </tr>
                    <?php foreach ($result as $row): ?>
                      <tr>
                        <td><?= $row["id"]; ?></td>
                        <td><?= $row["name"]; ?></td>
                        <td><?= $row["furigana"]; ?></td>
                        <td><?= $row["tel"]; ?></td>
                        <td><a href="<?= $row["email"]; ?>"><?= $row["email"]; ?></a></td>
                        <td><?= $row["login_id"]; ?></td>
                        <td><?= $row["password"]; ?></td>
                        <td>
                          <?php if($row['role']==='1'): ?>
                            管理者
                          <?php else: ?>
                            一般ユーザ
                          <?php endif; ?>
                        </td>
                        <td><?= $row["created"]; ?></td>
                        <td>
                          <?php if($_SESSION['User']['role'] !=0): ?>
                            <a href="?edit=<?=$row["id"];?>">編集</a> /
                            <?php if($_SESSION['User']['role'] != $row['id']): ?>
                              <a href="?del=<?=$row["id"];?>" onClick="if(!confirm('ID:<?=$row['id']?>を削除しますが大丈夫ですか？')) return false;">削除</a>
                            <?php endif; ?>
                          </td>
                        <?php endif; ?>
                      </tr>
                    <?php	endforeach;?>
                  </table>
                  <p><a href="../products/index.php">メインページへ戻る</a></p>
                </section>
              </div><!-- contact box -->
            </div><!-- contact -->
            <?php
            require("../common/footer.php");
            ?>
          </div><!-- wrapper -->
        </body>
        </html>
