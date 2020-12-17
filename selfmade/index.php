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

}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/input.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/header.php") ?>

    <div class = "wrapper">

      <div class = "center">
        <ul>
          <li><a href = "event.php">催事一覧</a></li>
          <li><a href = "login.php">管理者ページ</a></li>
        </ul>
      </div>

      <h1>商品検索</h1>

      <!-- エラーメッセージ -->
      <div class = "error center">
      <?php if(!empty($_SESSION['error3'])):?>
        <?php foreach($_SESSION['error3'] as $value):?>
          <?= $value."<br>"?>
        <?php endforeach;?>
      <?php endif;?>
      </div>

      <form action = "products.php" method = "POST">
      <table>
        <tr>
          <th>メーカー</th>
          <td><input type = "text" name = "maker" class = "textbox"></td>
        </tr>
        <tr>
          <th>商品</th>
          <td><input type = "text" name = "product" class = "textbox"></td>
        </tr>
        <tr>
          <th>価格</th>
          <td>
            <select name = "price" class = "textbox">
                <option value = "" hidden>価格帯を選択して下さい
                <option value = "0000 2000">〜2000円</option>
                <option value = "2001 3000">2001円〜3000円</option>
                <option value = "3001 4000">3001円〜4000円</option>
                <option value = "4001 5000">4001円〜5000円</option>
                <option value = "5001 9999999">5001円〜</option>
              </select>
            </td>
        </tr>
        </table>

        <div class = "center">
          <input type = "submit" value = "検索" class = "btn btn-primary">
        </div>

        </form>

    </div>

  <?php require("require/footer.php"); ?>
  </body>
</html>
