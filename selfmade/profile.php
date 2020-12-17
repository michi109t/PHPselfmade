<?php
session_start();
require_once("config/config.php");
require_once("model/User.php");

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
  $user = new User($host,$dbname,$user,$pass);
  $user->connectDB();

  // 登録情報参照
  $result = $user->findById($_SESSION['User'][0]);
  // print_r($result);

  $userid = $_SESSION['User'][0];
  $user = $_SESSION['User']['user'];
  $mail = $_SESSION['User']['mail'];
  $role = $_SESSION['User']['role'];
  $shop = $_SESSION['User']['shop'];



}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>登録情報｜Handbook</title>
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
      <?php if($_SESSION['User']['role'] == 1):?>
        <?php require("require/menu.php") ?>
      <?php endif;?>

    <h1>登録情報</h1>
    <table>
      <tr>
        <th>所属</th>
        <th>ユーザID</th>
        <th>メールアドレス</th>
      </tr>
      <tr>
        <td>
          <?php if($role == 0):?>
            店舗
          <?php else:?>
            商品課
          <?php endif;?>
        </td>
        <td><?=$user?></td>
        <td><?=$mail?></td>
      </tr>
    </table>

    <div class = "edit">

      <!-- エラーメッセージ -->
      <div class = "error">
      <?php if(isset($_SESSION['error'])):?>
        <?php foreach($_SESSION['error'] as $value):?>
          <?= $value."<br>"?>
        <?php endforeach?>
      <?php endif?>
      </div>

    <form action = "editconfirm.php" method = "POST">
      <!-- 送信用隠しフォーム -->
      <input type = "hidden" name = "id" value = "<?=$userid?>">
      <table>
        <tr>
          <th>ユーザID<span>*</span></th>
          <td><input type = "text" name = "user" value = "<?=$user?>"></td>
        </tr>
        <tr>
          <th>パスワード<span>*</span></th>
          <td><input type = "password" name = "password"></td>
        </tr>
        <tr>
          <th>メールアドレス<span>*</span></th>
          <td><input type = "text" name = "mail" value = "<?=$mail?>"></td>
        </tr>
        <tr>
          <th>所属<span>*</span></th>
          <td><input type = "radio" name = "role" value = 0 <?php if($role == 0):?> checked <?php endif;?>>店舗
              <input type = "radio" name = "role" value = 1 <?php if($role == 1):?> checked <?php endif;?>>商品課</td>
        </tr>
        <!-- 所属が店舗だった時表示 -->
        <?php if($role == 0):?>
        <tr>
          <th>店舗名<span>*</span></th>
          <td><input type = "text" name = shop value = "<?= $shop?>"></td>
        </tr>
        <?php endif;?>
        <!-- 所属が商品課だった場合 -->
        <?php if($role == 1):?>
        <tr>
          <th>名前<span>*</span></th>
          <td><input type = "text" name = shop value = "<?= $shop?>" placeholder = "商品課＋名字"></td>
        </tr>
        <?php endif;?>
      </table>
      <div class = "center">
        <input type = "submit" value = "更新" class= "btn btn-primary">
      </div>
    </form>

    </div>

    <div class = "center">
      <a href = "mypage.php">マイページへ</a>
    </div>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
