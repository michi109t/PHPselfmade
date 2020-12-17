<?php
session_start();
require_once("config/config.php");
require_once("model/User.php");

// ログイン画面経由を確認
if(!isset($_SESSION['User'])){
  header('Location:/selfmade/login.php');
  exit;
}

try{
  $user = new User($host,$dbname,$user,$pass);
  $user->connectDB();

}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>新規ユーザ登録｜Handbook</title>
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
    <?php require("require/menu.php") ?>

    <div class = "wrapper">
      <h1>新規ユーザ登録</h1>

      <!-- エラーメッセージ -->
      <div class = "error">
        <?php if(isset($_SESSION['error'])):?>
          <?php foreach($_SESSION['error'] as $value):?>
            <?= $value."<br>"?>
          <?php endforeach;?>
        <?php endif;?>
      </div>

      <form action = "userconfirm.php" method = "POST">
        <table>
          <tr>
            <th>ユーザID<span>*</span></th>
            <td><input type = "text" name = "user" class = "textbox"></td>
          </tr>
          <tr>
            <th>パスワード<span>*</span></th>
            <td><input type = "password" name = "password" class = "textbox"></td>
          </tr>
          <tr>
            <th>メールアドレス<span>*</span></th>
            <td><input type = "text" name = "mail" class = "textbox"></td>
          </tr>
          <tr>
            <th>所属<span>*</span></th>
            <td><input type = "radio" name = "role" value = 0 id = "r0" required>店舗
                <input type = "radio" name = "role" value = 1 id = "r1" required>商品課
                </td>
          </tr>
          <!-- 非表示ここから -->
          <tr>
            <!-- 所属が店舗 -->
              <th id = "role0">店舗名<span>*</span></th>
            <!-- 所属が商品課 -->
              <th id = "role1">名前<span>*</span></th>
              <td id = "hidden"><input type = "text" name = "shop" class = "textbox"></td>
          </tr>

          <!-- 非表示ここまで -->
        </table>

        <div class = "center">
          <input type = "submit" value = "登録" class = "btn btn-primary">
        </div>

      </form>

      

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
