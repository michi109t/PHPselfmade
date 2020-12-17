<?php
require_once("model/DB.php");

class Products extends DB{
/////////////////////////////////
// 参照
/////////////////////////////////
  // 該当メーカー商品参照
  public function findByMaker($arr){
    $sql = 'SELECT m.maker, p.id, p.product, p.price,p.image ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id ';
    $sql.= 'WHERE maker LIKE :maker AND deleteflg = 0';
    $stmt = $this->connect->prepare($sql);
    $params = array(':maker'=>'%'.$arr['maker'].'%');
    $stmt->execute($params);
    $resultMaker = $stmt->fetchAll();
    return $resultMaker;
  }
  // 該当商品1件参照
  public function findByProduct($id){
    $sql = 'SELECT p.id,m.maker,p.id,p.product,p.price,p.image ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id ';
    $sql.= 'WHERE p.id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    $resultMaker = $stmt->fetch();
    return $resultMaker;
  }
  // 選択商品の取扱店舗参照
  public function findByProductShop($arr){
    $sql = 'SELECT sp.id,s.shop, m.maker,p.id,p.product ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE p.id = :id ';
    $sql.= 'ORDER BY s.id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$arr['id']);
    $stmt->execute($params);
    $resultMaker = $stmt->fetchAll();
    return $resultMaker;

  }

  // 店舗参照
  public function findAllShop(){
    $sql = 'SELECT s.shop, s.id FROM shops s JOIN users u ON s.user_id = u.id WHERE u.role = 0 AND s.deleteflg = 0 ';
    $resultShop = $this->connect->query($sql);
    return $resultShop;
  }
  // キーワード参照
  // public function findAllKeyword(){
  //   $sql = 'SELECT * FROM keywords';
  //   $resultKey = $this->connect->query($sql);
  //   return $resultKey;
  // }

/////////////////////////////////
// 入力チェック
/////////////////////////////////
  // 商品登録
  public function validate($arr){
    $message1 = array();
    // 必須項目
    if(empty($arr['maker']) ||
       empty($arr['product']) ||
       empty($arr['price'])){
      $message1['required'] = "必須項目を入力してください";
    }
    // 価格（半角数字）
    if(!preg_match("/^[0-9]+$/" , $arr['price'])){
      $message1['price_error'] = "価格は半角数字で入力してください";
    }
    return $message1;
  }

  // 商品検索
  public function validateSearch($arr){
    $message2 = array();
    if(empty($arr['maker'])){
      $message2['required'] = "メーカー名を入力してください";
    }
    return $message2;
  }
/////////////////////////////////
// 登録
/////////////////////////////////
  // 新規商品登録
  public function add($arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H-i-s');

    // makersテーブル
    $sql = 'INSERT INTO makers (id,maker,created) SELECT MAX(id)+1, :maker,:created FROM makers';
    $stmt = $this->connect->prepare($sql);
    $params = array(':maker'=>$arr['maker'],
                    ':created'=>$date);
    $stmt->execute($params);

    // productsテーブル
    $sql1 = 'INSERT INTO products(id,product,price,image,maker_id,created)';
    $sql1.= 'SELECT MAX(p.id)+1,:product,:price,:image,m.id,:created FROM products p,makers m WHERE maker = :maker';
    $stmt1 = $this->connect->prepare($sql1);
    $params1 = array(':product'=>$arr['product'],
                     ':price'=>$arr['price'],
                     ':image'=>$arr['image'],
                     ':created'=>$date,
                     ':maker'=>$arr['maker']);
    $stmt1->execute($params1);

    // keywordsテーブル
    // if(isset($arr['other'])){
    //   $sql2 = 'INSERT INTO keywords (keyword,created)VALUES(:keyword,:created)';
    //   $stmt2 = $this->connect->prepare($sql2);
    //   $params2 = array(':keyword'=>$arr['other'],
    //                    ':created'=>$date);
    //   $stmt2->execute($params2);
    // }

    // shops_productsテーブル
    $count = count($arr['shop']);
    for($i = 0;$i < $count; $i++){
      $sql3 = 'INSERT INTO shops_products(shop_id,product_id,created) ';
      $sql3.= 'SELECT s.id, p.id,:created FROM shops s,products p ';
      $sql3.= 'WHERE s.shop = :shop AND p.product = :product';
      $stmt3 = $this->connect->prepare($sql3);
      $params3 = array(':created'=>$date,
                       ':shop'=>$arr['shop'][$i],
                       ':product'=>$arr['product']);
      $stmt3->execute($params3);
    }

    // products_keywordsテーブル
    // if(isset($arr['keyword'])){
    //   $countKey = count($arr['keyword']);
    //   for($x = 0;$x < $countKey; $x++){
    //     $sql4 = 'INSERT INTO products_keywords(product_id,keyword_id,created) ';
    //     $sql4.= 'SELECT p.id, k.id, :created FROM products p, keywords k ';
    //     $sql4.= 'WHERE p.product = :product AND k.keyword = :keyword';
    //     $stmt4 = $this->connect->prepare($sql4);
    //     $params4 = array('created'=>$date,
    //                      ':product'=>$arr['product'],
    //                      ':keyword'=>$arr['keyword'][$x]);
    //     $stmt4->execute($params4);
    //   }
    // }
  }

  // 取扱店舗追加
  public function addShop($arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H-i-s');
    $sql = 'INSERT INTO shops_products (id,shop_id,product_id,created) ';
    $sql.= 'SELECT MAX(id)+1, :shop_id, :product_id, :created FROM shops_products';
    $stmt = $this->connect->prepare($sql);
    $params = array(':shop_id'=>$arr['shop'],
                    ':product_id'=>$arr['id'],
                    ':created'=>$date);
    $stmt->execute($params);
  }
/////////////////////////////////
// 更新
/////////////////////////////////
  public function edit($arr){
    date_default_timezone_set("Asia/Tokyo");
    $date = date('Y-m-d H-i-s');

    // makersテーブル＆productsテーブル
    $sql = 'UPDATE makers m JOIN products p ON m.id = p.maker_id ';
    $sql.= 'SET m.maker = :maker, p.product = :product, p.price = :price, p.image = :image, m.updated = :Mupdated, p.updated = :Pupdated ';
    $sql.= 'WHERE p.id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$arr['id'],
                    ':maker'=>$arr['maker'],
                    ':product'=>$arr['product'],
                    ':price'=>$arr['price'],
                    ':image'=>$arr['image'],
                    ':Mupdated'=>$date,
                    ':Pupdated'=>$date);
    $stmt->execute($params);
  }

/////////////////////////////////
// 削除
/////////////////////////////////
  public function delete($id = null){
    if(isset($id)){
      // productsテーブル
      $sql = 'UPDATE products SET deleteflg = 1 WHERE id = :id';
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);

      // shops_productsテーブル
      $sql1 = 'DELETE FROM shops_products WHERE product_id = :id';
      $stmt1 = $this->connect->prepare($sql1);
      $params1 = array(':id'=>$id);
      $stmt1->execute($params1);

    }
  }

  // shops_productsテーブル
  public function deleteSP($id = null){
    if(isset($id)){
      $sql = 'DELETE FROM shops_products WHERE id = :id';
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=>$id);
      $stmt->execute($params);
    }
  }
}
