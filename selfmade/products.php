<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Customer.php");

// HTML特殊関数エスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$_POST['maker'] = h($_POST['maker']);
$_POST['product'] = h($_POST['product']);

if($_POST['maker'] == ""){
  $maker = null;
}elseif($_POST['product'] == ""){
  $_POST['product'] = null;
}elseif($_POST['price'] == ""){
  $_POST['price'] == null;
}

try{
  $cus = new Customer($host,$dbname,$user,$pass);
  $cus->connectDB();
  // print_r($_POST);

  // 入力チェック
  if($_POST){
    $message = $cus->validate($_POST);
    if($message){
      $_SESSION['error3'] = $message;
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit();
    }else{
      $_SESSION['error3'] = array();
    }
  }

  if($_POST['maker']){
    // メーカー検索
    $result = $cus->findByMakerProduct($_POST);

    if($_POST['product']){
      // メーカー＆商品検索
      $result = $cus->findByMnP($_POST);
      if($_POST['price']){
        // メーカー＆商品＆価格検索
        $result = $cus->findByMnPnP($_POST);
      }
    }elseif($_POST['price']){
      // メーカー＆価格検索
      $result = $cus->findByMakerPrice($_POST);
    }
    // メーカー取扱い店舗参照
    $resultMS = $cus->findByMakerShop($_POST);

  }elseif($_POST['product']){
    // 商品検索
    $result = $cus->findByProduct($_POST);
    if($_POST['price']){
      // 商品＆価格検索
      $result = $cus->findByProductPrice($_POST);
    }
  }elseif($_POST['price']){
    // 価格検索
    $result = $cus->findByPrice($_POST);
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
    <title>検索結果｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/result.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
    <script type = "text/javascript" src = "js/jquery.js"></script>
  </head>
  <body>
    <?php require("require/header.php") ?>

    <div class = "wrapper">
      <h1>検索結果一覧</h1>

      <!-- 入力メーカー表示 -->
      <?php if($_POST['maker']):?>
      <p>
        <?=$_POST['maker']?>取扱店舗：
        <?php foreach($resultMS as $row):?>
          <?=$row['shop']."/"?>
        <?php endforeach;?>
      </p>
      <?php endif;?>

      <p><img src = "img/img.png" alt = "画像">：画像あり</p>

      <table>
        <tr>
          <th>メーカー</th>
          <th>商品名</th>
          <th></th>
          <th>価格(税込)</th>
          <th>取扱店舗</th>
          <th>在庫</th>
        </tr>
      <!-- 検索結果表示 -->
      <?php foreach($result as $row):?>
        <tr>
          <!-- <div class = "abc"> -->
          <td><?=$row['maker']?></td>
          <td><?=$row['product']?></td>
          <td class = "imglink">

            <?php if(!empty($row['image'])):?>
            <div class = "inner">
              <img src = "img/img.png" alt = "画像" class = "imgex">

            <div class = "popup">
              <div class = "content">
                <img src = "img/products/<?=$row['image']?>">
                <br>

                <button class="close">閉じる</button>

            </div>
            </div>
            </div>

          <?php endif;?>

          </td>
          <td><?="¥".$row['price']?></td>
          <td><?=$row['shop']?></td>
          <td>
            <?php if($row['stock'] == 1):?>
              <?= "在庫あり";?>
            <?php else:?>
              <font color = "red"><?= "売切"?></font>
            <?php endif;?>
          </td>
        </tr>
      <?php endforeach;?>
      </table>

      <div class = "center">
        <a href = "index.php">戻る</a>
      </div>

    </div>

  <?php require("require/footer.php"); ?>

  </body>
</html>
