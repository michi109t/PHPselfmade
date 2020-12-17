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

  // 店舗参照
  $resultShop = $pro->findAllShop();

}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>新規商品登録｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/input.css">
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

      <h1>新規商品登録</h1>

      <!-- エラーメッセージ -->
      <div class = "error">
        <?php if(!empty($_SESSION['error1'])):?>
          <?php foreach($_SESSION['error1'] as $value):?>
            <?= $value."<br>"?>
          <?php endforeach;?>
        <?php endif;?>
      </div>

      <form action = "addconfirm.php" method = "POST">
        <table>
          <tr>
            <th>メーカー<span>*</span></th>
            <td><input type = "text" name = "maker" class = "textbox"
                value = "<?php if(!empty($_SESSION['maker']))echo $_SESSION['maker']?>"></td>
          </tr>
          <tr>
            <th>商品名<span>*</span></th>
            <td><input type = "text" name = "product" class = "textbox"
                value = "<?php if(!empty($_SESSION['product']))echo $_SESSION['product']?>"></td>
          </tr>
          <tr>
            <th>価格(税込)<span>*</span></th>
            <td><input type = "text" name = "price" class = "textbox"
                value = "<?php if(!empty($_SESSION['price']))echo $_SESSION['price']?>"></td>
          </tr>
          <tr>
            <th>取扱店舗<span>*</span></th>
            <td id = "shopcheck">
              <?php foreach($resultShop as $row):?>
                <?= "<input type = 'checkbox' name = 'shop[]' value = '".$row['shop']."' class = 'chk'>".
                $row['shop']."<br>"?>
              <?php endforeach;?>
            </td>
          </tr>
          <tr>

          </tr>
          <!-- <tr>
            <th>キーワード</th>
            <td>
              <?php foreach($resultKey as $row):?>
                <?= "<input type = 'checkbox' name = 'keyword[]' value = '" .$row['keyword']. "'>".
                $row['keyword']."<br>"?>
              <?php endforeach;?>
              <input type = "checkbox" name = "other" id = other>その他
              <input type = "text" name = "other" id = textOther disabled>
            </td>
          </tr> -->
          <tr>
            <th></th>
            <td><input type = "file" name = "image" class="form-control-file" id="image" accept = "image/*"></td>
          </tr>
          <tr>
            <th></th>
            <td id = "showfile"></td>
          </tr>
        </table>


        <div class = "center">
          <input type = "submit" value = "登録" class = "btn btn-primary" id = "btn1">
        </div>
      </form>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
