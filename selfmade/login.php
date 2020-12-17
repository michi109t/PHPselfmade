<?php
session_start();

require_once("config/config.php");
require_once("model/User.php");

// HTML特殊関数エスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

try{
  $user = new User($host,$dbname,$user,$pass);
  $user->connectDB();

  if($_POST){
    $_POST['user'] = h($_POST['user']);
    $_POST['password'] = h($_POST['password']);

    $result = $user->login($_POST);
    $pass = $_POST['password'];
    if(!empty($result)){
      $hash = $result['password'];
      if(password_verify($pass,$hash)){
        $_SESSION['User'] = $result;
        header('Location:/selfmade/mypage.php');
        exit;
      }


    }else{
      $message = "ログインできませんでした";
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
    <title>ログイン｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/inputmini.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/header.php") ?>

    <div class = "wrapper">
      <div class = "center">
        <a href = "index.php">商品案内</a>
      </div>
      <p>ユーザIDとパスワードを入力してください</p>

      <!-- エラーメッセージ -->
      <div class = "center">
        <div class = "error">
        <?php if(isset($message)):?>
          <?= $message?>
        <?php endif;?>
        </div>
      </div>

      <form action = "" method = "POST">
        <table>
          <tr>
            <th>ユーザID</th>
            <td><input type = "text" name = "user" class = "input"></td>
          </tr>
          <tr>
            <th>パスワード</th>
            <td><input type = "password" name = "password" class = "input"></td>
          </tr>
        </table>
        <br>
        <div class = "center">
          <input type = "submit" value = "ログイン" class = "btn btn-primary">
        </div>

      </form>

      <div class = "center">
        <a href = "passreset.php">パスワードを忘れた方はこちら</a>
      </div>

    </div>

  <?php require("require/footer.php"); ?>

  </body>
</html>
