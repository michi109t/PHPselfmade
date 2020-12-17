<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Products.php");

// ログイン画面経由を確認
if(!isset($_SESSION['User'])){
  header('Location:/selfmade/login.php');
  exit;
}elseif(empty($_POST)){
  // 商品登録画面経由を確認
  header('Location:/selfmade/addproducts.php');
  exit;
}

// HTML特殊関数エスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$_SESSION['maker'] = h($_POST['maker']);
$_SESSION['product'] = h($_POST['product']);
$_SESSION['price'] = h($_POST['price']);
$_SESSION['shop'] = $_POST['shop'];
$_SESSION['image'] = h($_POST['image']);


try{
  $pro = new Products($host,$dbname,$user,$pass);
  $pro->connectDB();
  // print_r($_POST);
  // 入力チェック
  if($_POST){

    $message1 = $pro->validate($_POST);
    if($message1){
      $_SESSION['error1'] = $message1;
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit();
    }else{
      $_SESSION['error1'] = array();
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
    <title>商品登録確認｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/confirm.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/myheader.php") ?>

    <div class = "wrapper">
      <h1>新規商品登録</h1>
      <p>この内容でよろしいですか</p>

        <table>
          <tr>
            <th>メーカー名</th>
            <td><?= $_SESSION['maker']?></td>
          </tr>
          <tr>
            <th>商品名</th>
            <td><?= $_SESSION['product']?></td>
          </tr>
          <tr>
            <th>価格（税込）</th>
            <td><?= $_SESSION['price']?>円</td>
          </tr>
          <tr>
            <th>取扱店舗</th>
            <td>
              <?php foreach($_SESSION['shop'] as $value):?>
                <?=$value."<br>"?>
              <?php endforeach;?>
            </td>
          </tr>
          <tr>
            <th></th>
            <td id = "viewfile">
              <?php if($_SESSION['image']):?>
                <img src = "img/products/<?=$_SESSION['image']?>">
              <?php endif;?>
            </td>
          </tr>
        </table>

      <!-- 送信用隠しフォーム -->
      <form action = "addcomp.php" method = "POST">
        <input type = "hidden" name = "maker" value = "<?= $_SESSION['maker']?>">
        <input type = "hidden" name = "product" value = "<?= $_SESSION['product']?>">
        <input type = "hidden" name = "price" value = "<?= $_SESSION['price']?>">
        <?php foreach($_SESSION['shop'] as $shop):?>
          <?= '<input type = "hidden" name = "shop[]" value = "'.$shop.'">'?>
        <?php endforeach;?>

        <input type = "hidden" name = "image" value = "<?=$_SESSION['image']?>">


        <div class = "center">
          <input type = "submit" value = "登録" class="btn btn-primary">
        </div>
      </form>

      <div class = "center">
        <a href = "addproducts.php">戻る</a>
      </div>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
