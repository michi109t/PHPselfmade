<?php
require_once("model/DB.php");

class User extends DB{
/////////////////////////////////
// 参照
/////////////////////////////////
  // ログイン
  public function login($arr){
    // print_r($arr);
    $sql = 'SELECT * FROM users u JOIN shops s ON u.id = s.user_id WHERE user = :user';
    $stmt = $this->connect->prepare($sql);
    $params = array(':user' => $arr['user']);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  // パスワードリセット申請
  public function pass($arr){
    $sql = 'SELECT * FROM users WHERE mail = :mail';
    $stmt = $this->connect->prepare($sql);
    $params = array(':mail'=>$arr['mail']);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }
  // 有効期限
  public function token($token){
    $sql = 'SELECT * FROM passreset WHERE token = :token';
    $stmt = $this->connect->prepare($sql);
    $params = array(':token'=>$token);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }
  // パスワードリセット用ユーザー情報
  public function passReset($token){
    $sql = 'SELECT u.* FROM users u JOIN passreset p ON u.id = p.user_id WHERE p.token = :token';
    $stmt = $this->connect->prepare($sql);
    $params = array(':token'=>$token);
    $stmt->execute($params);
    $passReset = $stmt->fetch();
    return $passReset;
  }
  // ユーザ全参照
  public function findAll(){
    $result = $this->connect->query('SELECT * FROM users WHERE role = 0 AND deleteflg = 0');
    return $result;
  }

  // ユーザ1件参照
  public function findById($id){
    $sql = 'SELECT u.*, s.shop FROM users u JOIN shops s ON u.id = s.user_id WHERE u.id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  // ログイン店舗の商品参照
  public function findByUser($id){
    $sql = 'SELECT m.maker,p.product,p.price,sp.stock,s.shop ';
    $sql.= 'FROM users u JOIN shops s ON u.id = s.user_id JOIN shops_products sp ON s.id = sp.shop_id JOIN products p ON sp.product_id = p.id JOIN makers m ON p.maker_id = m.id ';
    $sql.= 'WHERE u.id = :id ';
    $sql.= 'ORDER BY m.maker, p.product';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  // パスワードリセット用ユーザー1件参照
  public function resetUser($id){
    $sql = 'SELECT * FROM users WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }
/////////////////////////////////
// 登録
/////////////////////////////////
  public function add($arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H:i:s');

    // usersテーブル
    $sql = 'INSERT INTO users(user,password,mail,role,created)';
    $sql .= 'VALUES(:user,:password,:mail,:role,:created)';
    $stmt = $this->connect->prepare($sql);
    $params = array(':user' => $arr['user'],
                    ':password' => password_hash($arr['password'], PASSWORD_DEFAULT),
                    ':mail' => $arr['mail'],
                    ':role' => $arr['role'],
                    ':created' => $date);
    $stmt->execute($params);
    //shopsテーブル
    if(isset($arr['shop'])){
      $sql1 = 'INSERT INTO shops SET shop = :shop, user_id = LAST_INSERT_ID(),created = :created';
      $stmt1 = $this->connect->prepare($sql1);
      $params1 = array(':shop'=>$arr['shop'],
                       ':created' => $date);
      $stmt1->execute($params1);
    }
  }
  // passresetテーブル
  public function reset($mail,$token,$resetdate){
    $sql = 'INSERT INTO passreset(user_id,token,reset_date)SELECT id, :token, :reset_date FROM users WHERE mail = :mail';
    $stmt = $this->connect->prepare($sql);
    $params =array(':token'=>$token,
                   ':reset_date'=>$resetdate,
                   ':mail'=>$mail);
    $stmt->execute($params);
  }
/////////////////////////////////
// 更新
/////////////////////////////////
  public function edit($arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H:i:s');

    $sql = 'UPDATE shops s JOIN users u ON s.user_id = u.id ';
    $sql .= ' SET u.user = :user, u.password = :password, u.mail = :mail, u.role = :role, s.shop = :shop ,u.updated = :Uupdated, s.updated = :Supdated ';
    $sql .= ' WHERE u.id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$arr['id'],
                    ':user'=>$arr['user'],
                    ':password'=>password_hash($arr['password'], PASSWORD_DEFAULT),
                    ':mail'=>$arr['mail'],
                    ':role'=>$arr['role'],
                    ':shop'=>$arr['shop'],
                    ':Uupdated'=>$date,
                    ':Supdated'=>$date);
    $stmt->execute($params);
  }

  // 売切登録
  public function editStock($arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H:i:s');

    $sql = 'UPDATE shops_products SET stock = :stock, updated = :updated ';
    $sql.= 'WHERE shop_id = (SELECT id FROM shops WHERE shop = :shop) ';
    $sql.= 'AND product_id = (SELECT id FROM products WHERE product = :product)';
    $stmt = $this->connect->prepare($sql);
    $params = array(':stock'=>$arr['stock'],
                    ':shop'=>$arr['shop'],
                    ':product'=>$arr['product'],
                    ':updated'=>$date);
    $stmt->execute($params);
  }

  // パスワード再登録
  public function editPass($token,$arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H:i:s');

    $sql = 'UPDATE users u JOIN passreset p ON u.id = p.user_id ';
    $sql.= 'SET u.password = :password, u.updated = :updated ';
    $sql.= 'WHERE u.mail = :mail AND p.token = :token';
    $stmt = $this->connect->prepare($sql);
    $params = array(':password'=>password_hash($arr['password'],PASSWORD_DEFAULT),
          ':updated'=>$date,
          ':mail'=>$arr['mail'],
          ':token'=>$token);
    $stmt->execute($params);
  }
/////////////////////////////////
// 入力チェック
/////////////////////////////////
  public function validate($arr){
    $message = array();
    // 必須項目
    if(empty($arr['user']) ||
       empty($arr['password']) ||
       empty($arr['mail']) ||
       empty($arr['shop'])){
      $message['required'] = "必須項目を入力して下さい";
    }
    // 半角英数字（ユーザIDとパスワード）
    if(!preg_match("/^[0-9a-zA-Z]*$/",$arr['user']) ||
       !preg_match("/^[0-9a-zA-Z]*$/",$arr['password'])){
      $message['user_error'] = "ユーザIDとパスワードは半角英数字で入力して下さい";
    }

    // メールアドレス
    if(!filter_var($arr['mail'],FILTER_VALIDATE_EMAIL)){
      $message['mail_error'] = "メールアドレスを正しく入力して下さい";
    }
    return $message;
  }

/////////////////////////////////
// 削除
/////////////////////////////////
  // 論理削除（usersテーブル＆shopsテーブル）
  public function delete($id = null){
    if(isset($id)){
      $sql = 'UPDATE users u JOIN shops s ON u.id = s.user_id ';
      $sql.= 'SET u.deleteflg = 1, s.deleteflg = 1 ';
      $sql.= 'WHERE u.id = :id';
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);
    }
  }
}
