<?php
require_once("model/DB.php");

class Customer extends DB{
/////////////////////////////////
// 参照
/////////////////////////////////
  // 入力メーカーの取扱い店舗参照
  public function findByMakerShop($arr){
    if(!$arr == null){
      $sql = 'SELECT DISTINCT s.shop ';
      $sql.= 'FROM shops s JOIN shops_products sp ON s.id = sp.shop_id JOIN products p ON sp.product_id = p.id JOIN makers m ON p.maker_id = m.id ';
      $sql.= 'WHERE m.maker LIKE :maker ';
      $sql.= 'ORDER BY s.shop';
      $stmt = $this->connect->prepare($sql);
      $params = array(':maker'=>"%".$arr['maker']."%");
      $stmt->execute($params);
      $resultMS = $stmt->fetchAll();
      return $resultMS;
    }
  }

  // メーカーのみ検索
  public function findByMakerProduct($arr){
    $sql = 'SELECT m.maker,p.id,p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE m.maker LIKE :maker ';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':maker'=>"%".$arr['maker']."%");
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }
  // メーカー＆商品名検索
  public function findByMnP($arr){
    $sql = 'SELECT m.maker, p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE m.maker LIKE :maker AND p.product LIKE :product ';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':maker'=>"%".$arr['maker']."%",
                    ':product'=>"%".$arr['product']."%");
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }
  // メーカー＆商品＆価格検索
  public function findByMnPnP($arr){
    $min = mb_strimwidth($arr['price'],0,4); //料金下限値
    $max = mb_strimwidth($arr['price'],5,7); //料金上限値

    $sql = 'SELECT m.maker,p.id,p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE m.maker LIKE :maker AND p.product LIKE :product AND p.price BETWEEN :min AND :max';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':maker'=>"%".$arr['maker']."%",
                    ':product'=>"%".$arr['product']."%",
                    ':min'=>$min,
                    ':max'=>$max);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }
  // メーカー＆価格検索
  public function findByMakerPrice($arr){
    $min = mb_strimwidth($arr['price'],0,4); //料金下限値
    $max = mb_strimwidth($arr['price'],5,7); //料金上限値

    $sql = 'SELECT m.maker,p.id,p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE m.maker LIKE :maker AND p.price BETWEEN :min AND :max';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':maker'=>"%".$arr['maker']."%",
                    ':min'=>$min,
                    ':max'=>$max);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }
  // 商品のみ検索
  public function findByProduct($arr){
    $sql = 'SELECT m.maker, p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE p.product LIKE :product ';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':product'=>"%".$arr['product']."%");
    $stmt->execute($params);
    $resultProduct = $stmt->fetchAll();
    return $resultProduct;
  }
  // 商品＆価格検索
  public function findByProductPrice($arr){
    $min = mb_strimwidth($arr['price'],0,4); //料金下限値
    $max = mb_strimwidth($arr['price'],5,7); //料金上限値

    $sql = 'SELECT m.maker,p.id,p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE p.product LIKE :product AND p.price BETWEEN :min AND :max ';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':product'=>"%".$arr['product']."%",
                    ':min'=>$min,
                    ':max'=>$max);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }
  // 価格検索
  public function findByPrice($arr){
    $min = mb_strimwidth($arr['price'],0,4); //料金下限値
    $max = mb_strimwidth($arr['price'],5,7); //料金上限値

    $sql = 'SELECT m.maker, p.product, p.price, p.image, s.shop,sp.stock ';
    $sql.= 'FROM makers m JOIN products p ON m.id = p.maker_id JOIN shops_products sp ON p.id = sp.product_id JOIN shops s ON sp.shop_id = s.id ';
    $sql.= 'WHERE p.price BETWEEN :min AND :max ';
    $sql.= 'ORDER BY m.maker,s.shop,p.price,p.product ';
    $stmt = $this->connect->prepare($sql);
    $params = array(':min'=>$min,
                    ':max'=>$max);
    $stmt->execute($params);
    $resultPrice = $stmt->fetchAll();
    return $resultPrice;
  }

/////////////////////////////////
// 入力チェック
/////////////////////////////////
  public function validate($arr){
    $message = array();
    // 未入力
    if(empty($arr['maker']) && empty($arr['product']) &&
       empty($arr['price']) && empty($arr['keyword'])){
         $message['required'] = "いずれか１つを入力又は選択してください";
       }
       return $message;
  }

}
