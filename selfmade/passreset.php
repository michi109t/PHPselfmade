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
    $_POST['mail'] = h($_POST['mail']);
    $result = $user->pass($_POST);
    if(!empty($result)){
      // ランダムな文字列の生成
      $token = bin2hex(random_bytes(32));
      // 有効期限設定
      date_default_timezone_set("Asia/Tokyo");
      $resetdate = date('Y-m-d H:i:s');

      mb_language("Japanese");
      mb_internal_encoding("UTF-8");


      $to = $result['mail']; //宛先
      $subject = "パスワード再登録"; //件名
      $msg = "下記のURLから30分以内にパスワードリセットを行なってください。\r\n";
      $msg.= 'http://localhost/selfmade/reset.php?token='.$token."\r\n";
      $msg.= "※このメールにお心当たりのない場合は、第三者がメールアドレスの入力を誤った可能性があります。\r\n";
      $msg.= "そのため、このメールは破棄していただいて結構です。";
      $headers = "From: hae@hae.com";

      // メール送信
      mb_send_mail($to,$subject,$msg,$headers);
      $user->reset($result['mail'],$token,$resetdate);

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
    <title>パスワード忘れた方｜Handbook</title>
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
      <?php if(isset($result)):?>
        <p style="color:red">メールが送信されました！</p>
        <p><a href="login.php">戻る</a></p>
      <?php else:?>

      <h1>パスワードを忘れた方</h1>
      <p>登録メールアドレスを入力してください</p>

      <div class = "center">

        <form action = "" method ="POST">
          <input type = "email" name = "mail">
          <input type = "submit" value = "送信" class = "btn btn-primary">

        <br>
        <a href = "login.php">ログインページ</a>
      </div>
    <?php endif;?>
    </div>

  <?php require("require/footer.php"); ?>

  </body>
</html>
