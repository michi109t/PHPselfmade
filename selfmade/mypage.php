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

  if(isset($_SESSION['User'])){
    // print_r($_SESSION['User']);
    $result = $user->findByUser($_SESSION['User']['0']);

  }

  if($_POST){
    // print_r($_POST);
    $user->editStock($_POST);
    $result = $user->findByUser($_SESSION['User']['0']);
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
    <title>マイページ｜Handbook</title>
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

    <?php if($_SESSION['User']['role'] == 0):?>

      <table>
        <tr>
          <th>メーカー</th>
          <th>商品名</th>
          <th>価格</th>
          <th>売切登録</th>
          <th>在庫状況変更</th>
        </tr>
        <?php foreach($result as $row):?>
        <tr>
          <td><?=$row['maker']?></td>
          <td><?=$row['product']?></td>
          <td><?=$row['price']?>円</td>
          <td>
            <?php if($row['stock'] == 1):?>
              在庫あり
            <?php else:?>
              売切
            <?php endif;?>
          </td>
          <form action = "" method = "POST">
          <td>
            <select name = "stock">
              <option value = "" hidden>在庫状況</option>
              <option value = 1>在庫あり</option>
              <option value = 0>売切</option>
            </select>
            <!-- 隠しフォーム -->
            <input type = "hidden" name = "shop" value = "<?=$row['shop']?>">
            <input type = "hidden" name = "product" value = "<?=$row['product']?>">
            <input type = "submit" value = "登録" >
          </td>
          </form>
        </tr>
        <?php endforeach;?>
      </table>


    <!-- 管理者用 -->

    <?php elseif($_SESSION['User']['role'] == 1):?>
        <ul class = "menu">
          <li><a href = "addproducts.php">新規商品登録</a></li>
          <li><a href = "search.php">商品編集/削除</a></li>
          <!-- <li><a href = "editevent.php">催事管理</a></li> -->
          <li><a href = "users.php">ユーザ管理</a></li>
        </ul>
    <?php endif;?>
    </div>

      <?php require("require/footer.php"); ?>
  </body>
</html>
