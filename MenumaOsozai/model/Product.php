<?php
//接続情報extendsで継承
require_once("DB.php");

//DBの結合を行うので接続するクラスは同じDB
class Product extends DB {

  //製品参照 select
  public function getData() {
    $sql = 'SELECT pid, pname, image, price FROM products ORDER BY pid ASC';//長くなったときに編集しやすくするため変数に格納
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if(isset($result)){
      return $result;
    }
  }

  //購入商品表示 select
  public function findByPid($arr) {
    $sql = 'SELECT pid, pname, image, price FROM products WHERE pid = :pid';
    $stmt = $this->connect->prepare($sql);
    $params = array(':pid'=>$arr['pid']);
    // return $params;
    $stmt->execute($params);
    //fetch　⇒　テーブルのレコード(行)を指定。fetch関数を1度実行すると、1行下へシフトし、その行の値を取り出す。
    $result = $stmt->fetch();
    //index.phpの編集処理へ返してやって$resultで受け取る。
    return $result;
  }


  //商品購入 insert
  public function buyProduct($arr){
    $sql = "INSERT INTO userlist_products(user_id, product_id, num, created) VALUES(:user_id, :product_id, :num, :created)";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':user_id'=>$arr['user_id'],
      ':product_id'=>$arr['product_id'],
      ':num'=>$arr['num'],
      ':created'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
    // echo '<pre>';
    // print_r($params);
    // echo '</pre>';
  }

  //購入商品確認 select
  public function findById($arr) {
    $sql = 'SELECT pid, pname, image, price FROM products WHERE pid = :pid';
    $stmt = $this->connect->prepare($sql);
    $params = array(':pid'=>$arr['product_id']);
    // return $params;
    $stmt->execute($params);
    //fetch　⇒　テーブルのレコード(行)を指定。fetch関数を1度実行すると、1行下へシフトし、その行の値を取り出す。
    $result = $stmt->fetch();
    //index.phpの編集処理へ返してやって$resultで受け取る。
    return $result;
  }

}
