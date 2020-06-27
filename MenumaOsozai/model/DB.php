<?php
class DB {
  //プロパティ (クラス内で実行する$connectはprotected)
  private $host;
  private $dbname;
  private $user;
  private $pass;
  protected $connect;

  //コンストラクタ(初期値セット)
  function __construct($host, $dbname, $user, $pass) {
    $this->host = $host;
    $this->dbname = $dbname;
    $this->user = $user;
    $this->pass = $pass;
  }

  //メソッド
  public function connectDb() {
    $this->connect = new PDO ('mysql:host='.$this->host.';dbname='.$this->dbname, $this->user, $this->pass);
    if(!$this->connect) {
      echo 'DB接続失敗';
      die();
    }
  }

  public function getData() {
    $sql = 'SELECT pid, pname, image, price FROM products ORDER BY pid ASC';//長くなったときに編集しやすくするため変数に格納
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if(isset($result)){
    return $result;
   }
 }
}
