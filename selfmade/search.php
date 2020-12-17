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

}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>商品検索｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/inputmini.css">
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
      <h1>商品検索</h1>

      <!-- エラーメッセージ -->
      <div class = "error center">
      <?php if(!empty($_SESSION['error2'])):?>
        <?php foreach($_SESSION['error2'] as $value):?>
          <?= $value."<br>"?>
        <?php endforeach;?>
      <?php endif;?>
      </div>

      <form action = "result.php" method = "POST">
        <table>
          <tr>
            <th>メーカー<span>*</span></th>
            <td><input type = "text" name = "maker" class = "input"></td>
          </tr>
          <!-- <tr>
            <th>商品名</th>
            <td><input type = "text" name = "product" class = "input"></td>
          </tr> -->
        </table>

        <div class = "center">
          <input type = "submit" value = "検索" class = "btn btn-primary">
        </div>

      </form>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
