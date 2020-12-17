<?php
class DB{
  //プロパティ
  private $host;
  private $dbname;
  private $user;
  private $pass;
  protected $connect;

  //コンストラクタ
  function __construct($host,$dbname,$user,$pass) {
    $this->host = $host;
    $this->dbname = $dbname;
    $this->user = $user;
    $this->pass = $pass;
  }

  // DB接続メソッド
  public function connectDb(){
    $this->connect = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,$this->user,$this->pass);

    if(!$this->connect ){
      echo "データベースに接続できませんでした。";
      die();
    }
  }
}
