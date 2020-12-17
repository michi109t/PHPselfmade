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

  if(isset($_GET['del'])){
    // 削除
    $user->delete($_GET['del']);
    header('Location:/selfmade/users.php');
    exit();
  }else{
    // 全参照
    $result = $user->findAll();
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
    <title>ユーザ管理｜Handbook</title>
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

      <h1>ユーザ一覧</h1>

      <a href = "adduser.php">新規ユーザ登録</a>

      <table>
        <tr>
          <th></th>
          <th>ユーザID</th>
          <th>メールアドレス</th>
          <?php if(!isset($_GET['edit'])):?>
          <th></th>
          <?php endif;?>
        </tr>
        <?php foreach($result as $row):?>
        <tr>
          <td><?=$row['id']?></td>
          <td><?= $row['user']?></td>
          <td><?= $row['mail']?></td>
          <?php if(!isset($_GET['edit'])):?>
          <td class = "center">
            <a href = "?del=<?=$row['id']?>" onClick = "if(!confirm('ユーザ:<?=$row['user']?>を削除しますか？')) return false;">削除</a>
          </td>
        <?php endif;?>
        </tr>
        <?php endforeach;?>
      </table>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
