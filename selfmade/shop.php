<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Products.php");

// ログアウト
if(isset($_GET['logout'])){
  // セッションの破壊
  $_SESSION = array();
  session_destroy();
}
// ログイン画面経由を確認
if(!isset($_SESSION['User'])){
  header('Location:/selfmade/login.php');
  exit;
}

try{
  $pro = new Products($host,$dbname,$user,$pass);
  $pro->connectDB();

  if($_POST){
    // 選択商品取扱店舗参照
    $resultMaker['Shop'] = $pro->findByProductShop($_POST);
    foreach($resultMaker['Shop'] as $row){
      $maker = $row['maker'];
      $product = $row['product'];
      $id = $row['id'];
    }
  }


  // 店舗参照
  $resultShop = $pro->findAllShop();

  if(isset($_GET['shopdel'])){
    if(isset($_GET['id'])){
      // 取扱店舗削除
      $pro->deleteSP($_GET['shopdel']);
      // 選択商品取扱店舗参照
      $resultMaker['Shop'] = $pro->findByProductShop($_GET);
      foreach($resultMaker['Shop'] as $row){
        $maker = $row['maker'];
        $product = $row['product'];
        $id = $row['id'];
      }
    }
  }elseif(isset($_GET['shop'])){
    if(isset($_GET['id'])){
      // 取扱店舗追加
      $pro->addShop($_GET);

      // 選択商品取扱店舗参照
      $resultMaker['Shop'] = $pro->findByProductShop($_GET);
      foreach($resultMaker['Shop'] as $row){
        $maker = $row['maker'];
        $product = $row['product'];
        $id = $row['id'];
      }
    }
  }

}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>取扱店舗｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/result.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
    <script type = "text/javascript" src = "js/jquery.js"></script>
  </head>
  <body>
    <?php require("require/myheader.php") ?>
  
    <div class = "wrapper">
      <div id = "menubar">
        <img src = "img/hmbg.png" alt = "メニュー" id = "username">
        <h1 class = "menu"><?=$_SESSION['User']['shop']?></h1>
      </div>

      <div id = "prof">
        <p><a href = "profile.php">登録情報</a></p>
        <p><a href = "?logout=1">ログアウト</a></p>
      </div>
      <?php require("require/menu.php") ?>

      <div class = "edit">
      <h1>
        <?php if(!empty($maker) && !empty($product)):?>
          <?= $maker.":".$product?>取扱店舗一覧
        <?php endif;?>
      </h1>

      <table>
        <?php foreach($resultMaker['Shop'] as $row):?>
          <tr>
            <td><?=$row['shop']?></td>
            <td>
              <form action = "" method = "GET">
                <input type = "submit" value = "削除" class = "delete">
                <!-- 隠しフォーム -->
                <!-- sp.id -->
                <input type = "hidden" name = "shopdel" value = "<?=$row[0]?>">
                <!-- p.id -->
                <input type = "hidden" name = "id" value = "<?=$row['id']?>">
              </form>
            </td>
          </tr>
        <?php endforeach;?>
      </table>

      <h1>取扱店舗追加</h1>
      <table>
        <?php foreach($resultShop as $row):?>
          <tr>
            <form action = "" method = "GET">
            <td><?= $row['shop']?></td>
            <td><input type = "submit" value = "追加"></td>
            <!-- 隠しフォーム -->
            <!-- s.id -->
            <input type = "hidden" name = "shop" value = "<?=$row['id']?>">
            <!-- p.id -->
            <input type = "hidden" name = "id" value = "<?=$id?>">
            </form>
          </tr>
        <?php endforeach;?>
      </table>

      </div>


    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
