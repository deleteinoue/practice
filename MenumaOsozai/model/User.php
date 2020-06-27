<?php
//接続情報extendsで継承
require_once("DB.php");

class User extends DB {

  //ログイン認証
  public function login($arr) {
    $sql = 'SELECT * FROM userlist WHERE login_id = :login_id AND password = :password';
    $stmt = $this->connect->prepare($sql);
    //バインド(:user_name)をパラメータ($arr['name'])に置き換える
    $params = array(':login_id'=>$arr['login_id'], ':password'=>$arr['password']);
    $stmt->execute($params);
    //↓一致したデータがあるかどうか確認。(一致なら1件、不一致なら0件。)
    // $result = $stmt->rowCount();
    $result = $stmt->fetch();
    return $result;
  }

  //参照 select
  public function findAll() {
    $sql = 'SELECT * FROM userlist';//長くなったときに編集しやすくするため変数に格納
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
  }

  //参照（条件付き。主に編集時） select
  public function findById($id) {
    $sql = 'SELECT * FROM userlist WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    //fetch　⇒　テーブルのレコード(行)を指定。fetch関数を1度実行すると、1行下へシフトし、その行の値を取り出す。
    $result = $stmt->fetch();
    //index.phpの編集処理へ返してやって$resultで受け取る。
    return $result;
  }

  //登録 insert
  public function add($arr){
    //入力内容の配列が送られたかどうか確認。
    // print_r($arr);
    $sql = "INSERT INTO userlist(name, furigana, tel, email, login_id, password, role, created) VALUES(:name, :furigana, :tel, :email, :login_id, :password, :role, :created)";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'=>$arr['name'],
      ':furigana'=>$arr['furigana'],
      ':tel'=>$arr['tel'],
      ':email'=>$arr['email'],
      //値をmd5()で囲ってやると値が暗号化される。↓ならmd5($arr['password'])
      ':login_id'=>$arr['login_id'],
      ':password'=>$arr['password'],
      ':role'=>0,
      ':created'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
    echo '<pre>';
    print_r($stmt);
    echo '</pre>';
    // $result = $stmt->fetch();
    // return $result;
  }

  //編集 update
  public function edit($arr){
    $sql = "UPDATE userlist SET name = :name, furigana = :furigana, tel = :tel, email = :email,  login_id = :login_id, password = :password, created = :created WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':id'=>$arr['id'],
      ':name'=>$arr['name'],
      ':furigana'=>$arr['furigana'],
      ':tel'=>$arr['tel'],
      ':email'=>$arr['email'],
      ':login_id'=>$arr['login_id'],
      ':password'=>$arr['password'],
      ':created'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //削除 delete
  //delete($id = null)、if(issst($id)で値が無かった時にデリートしないよう保険をかける)
  public function delete($id = null){
    if(isset($id)) {
      $sql = "DELETE FROM userlist WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);
    }
  }

  //購入履歴表示 select
  public function findBuyHistory($id) {
    $sql = "SELECT ";
    $sql .= "userlist.id as user_id,";
    $sql .= "userlist.name,";
    $sql .= "products.pid as product_id,";
    $sql .= "products.pname,";
    $sql .= "userlist_products.num,";
    $sql .= "userlist_products.created ";
    $sql .= "FROM userlist_products ";
    $sql .= "JOIN userlist ON userlist.id = userlist_products.user_id ";
    $sql .= "JOIN products ON products.pid = userlist_products.product_id ";
    $sql .= "WHERE userlist.id = :id";

    $stmt = $this->connect->prepare($sql);
    // print_r($stmt);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    //fetch　⇒　テーブルのレコード(行)を指定。fetch関数を1度実行すると、1行下へシフトし、その行の値を取り出す。
    $result = $stmt->fetchAll();
    //index.phpの編集処理へ返してやって$resultで受け取る。
    return $result;
  }


  //入力（内容正否）チェック validate
  public function validate($arr) {
    $message = array();

    //以下『php 正規表現　○○』にて文法を検索
    //ユーザ名
    if(empty($arr['name'])) {
      $message['name'] = '【エラー】ユーザ名を入力してください。';
    }

    //フリガナ
    if(empty($arr['furigana'])) {
      $message['furigana'] = '【エラー】フリガナを入力してください。';
    }

    //電話番号
    if(empty($arr['tel'])) {
      $message['tel'] = '【エラー】電話番号を入力してください。';
    }

    //ログインID
    if(empty($arr['login_id'])) {
      $message['login_id'] = '【エラー】(ログイン用)ユーザIDを入力してください。';
    }

    //パスワード
    if(empty($arr['password'])) {
      $message['password'] = '【エラー】パスワードを入力してください。';
    }

    return $message;
  }
}
?>
