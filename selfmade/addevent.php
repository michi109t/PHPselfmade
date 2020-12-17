<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Event.php");

// ログイン画面経由を確認
if(!isset($_SESSION['User'])){
  header('Location:/selfmade/login.php');
  exit;
}

try{
  $event = new Event($host,$dbname,$user,$pass);
  $event->connectDB();

  // 催事場参照
  $result = $event->findAll();

  // 店舗参照
  $resultShop = $event->findAllShop();


}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}

 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>催事登録｜Handbook</title>
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
      <h1>新規催事登録</h1>

      <!-- エラーメッセージ -->
      <div class = "error">
        <?php if(!empty($_SESSION['error3'])):?>
          <?php foreach($_SESSION['error3'] as $value):?>
            <?= $value."<br>"?>
          <?php endforeach;?>
        <?php endif;?>
      </div>

      <form action = "eventconfirm.php" method = "POST">
        <table>
          <tr>
            <th>メーカー<span>*</span></th>
            <td><input type = "text" name = "eventmaker" class = "textbox" value = "<?php if(!empty($_SESSION['eventmaker']))echo $_SESSION['eventmaker']?>"></td>
          </tr>
          <tr>
            <th>催事期間<span>*</span></th>
            <td>
              <input type = "text" name = "startdate" placeholder = "YYYY-MM-DD" class = "term" value = "<?php if(!empty($_SESSION['startdate']))echo $_SESSION['startdate']?>">
               〜
              <input type = "text" name = "enddate" placeholder = "YYYY-MM-DD" class = "term" value = "<?php if(!empty($_SESSION['enddate']))echo $_SESSION['enddate']?>">
            </td>
          </tr>
          <tr>
            <th>催事場所<span>*</span></th>
            <td id = "shopcheck">
              <?php foreach($result as $row):?>
                <?= "<input type = 'checkbox' name = 'place[]' value ='" .$row['place']. "' class = 'place chk'>" .$row['place'] ."<br>"?>
              <?php endforeach;?>
              <input type = "checkbox" name = "otherevent" id = other>その他
              <input type = "text" name = "otherevent" id = textOther value = "<?php if(!empty($_SESSION['otherevent']))echo $_SESSION['otherevent']?>" disabled>
            </td>
          </tr>
          <!-- 非表示ここから -->
          <tr>
            <th class = "shopHidden">管理店舗<span>*</span></th>
            <td class = "shopHidden">
              <?php foreach($resultShop as $row):?>
                <?= "<input type = 'radio' name = 'shop' value = '".$row['shop']."' class = 'chk'>".
                $row['shop']."<br>"?>
              <?php endforeach;?>
            </td>
          </tr>
          <!-- ここまで -->
        </table>

        <div class = "center">
          <input type = "submit" value = "登録" class = "btn btn-primary" id = "btn1">
        </div>

      </form>


    </div>

    <?php require("require/footer.php"); ?>
  </body>
</html>
