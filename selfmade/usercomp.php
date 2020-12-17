<?php
session_start();
require_once("config/config.php");
require_once("model/User.php");

// ログイン画面経由を確認
if(!isset($_SESSION['User'])){
  header('Location:/selfmade/login.php');
  exit;
}elseif(empty($_POST)){
  header('Location:/selfmade/adduser.php');
  exit;
}

try{
  $user = new User($host,$dbname,$user,$pass);
  $user->connectDB();

  // 登録
  if($_POST){
    $user->add($_POST);

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
    <title>ユーザ登録完了｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/myheader.php") ?>

    <div class = "wrapper">
      <h1>ユーザ登録しました！</h1>

      <div class = "center">
        <a href = "mypage.php">マイページ</a>
      </div>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
