<?php
session_start();

require_once("config/config.php");
require_once("model/User.php");

try{
  $user = new User($host,$dbname,$user,$pass);
  $user->connectDB();

  if($_GET){
    // トークン登録時刻参照
    $result = $user->token($_GET['token']);
    
    // 時間
    date_default_timezone_set("Asia/Tokyo");
    $now = strtotime('now'); //現在時刻
    $reset = strtotime($result['reset_date']); //リセット登録した時刻
    $diff = $now - $reset;

    // 有効期限判定
    if($diff > 1800){
      // タイムオーバー
      $timeover = "有効期限切れのURLです。";

    }else{
      $timeover = "";
      // パスワードリセット用ユーザー参照
      $passReset = $user->passReset($_GET['token']);
      if($_POST){
        // パスワード再登録
        $user->editPass($_GET['token'],$_POST);
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
    <title>パスワードリセット｜Handbook</title>
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
      <h1>パスワード再登録</h1>
      <?php if(!empty($timeover)):?>
        <div class = "center">
          <?=$timeover?>
          <p>もう一度初めからやり直してください</p>

          <a href = "login.php">ログインページ</a>
          <a href = "passreset.php">パスワードリセット</a>
        </div>
      <?php else:?>
        <?php if(empty($_POST)):?>
        <p>メールアドレスと新しいパスワードを入力してください</p>

        <div class = "center">

          <form action = "" method ="POST">
          <table>
            <tr>
              <th>メールアドレス</th>
              <td><input type = "email" name = "mail"></td>
            </tr>
            <tr>
              <th>新しいパスワード</th>
              <td><input type = "password" name = "password"></td>
            </tr>
          </table>

            <input type = "submit" value = "再登録" class = "btn btn-primary">
          </form>
          </div>
        <?php else:?>

          <div class = "center">
            <p>パスワードを再登録しました!</p>

            <p><a href = "login.php">ログインページ</a></p>
          </div>
        <?php endif;?>
      <?php endif;?>


    </div>

  <?php require("require/footer.php"); ?>

  </body>
</html>
