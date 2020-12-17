<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Products.php");

try{
  $pro = new Products($host,$dbname,$user,$pass);
  $pro->connectDB();

  // 更新処理
  if($_POST){
    $pro->edit($_POST);
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
    <title>商品更新完了｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/myheader.php") ?>

    <div class = "wrapper">
      <h1>商品を更新しました！</h1>

      <div class = "center">
        <a href = "mypage.php">マイページ</a>
        
      </div>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
