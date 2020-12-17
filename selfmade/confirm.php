<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Products.php");

// HTML特殊関数エスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$_SESSION['maker'] = h($_POST['maker']);
$_SESSION['product'] = h($_POST['product']);
$_SESSION['price'] = h($_POST['price']);
$_SESSION['image'] = h($_POST['image']);
if(isset($_POST['keyword'])){
  $_SESSION['keyword'] = $_POST['keyword'];
}else{
  $_SESSION['keyword'] = array();
}
if(isset($_POST['other'])){
  $_SESSION['other'] = h($_POST['other']);
}

try{
  $pro = new Products($host,$dbname,$user,$pass);
  $pro->connectDB();

  if($_POST){
    // 入力チェック
    $message1 = $pro->validate($_POST);
    if($message1){
      $_SESSION['error'] = $message1;
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit();
    }else{
      $_SESSION['error'] = array();
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
    <title>商品更新確認｜Handbook</title>
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
      <h1>商品編集確認</h1>
      <p>この内容でよろしいですか</p>

      <table>
        <tr>
          <th>メーカー</th>
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
          <th>キーワード</th>
          <td>
            <?php foreach($_SESSION['keyword'] as $value):?>
                <?=$value."<br>"?>
            <?php endforeach;?>
            <?php if(isset($_SESSION['other'])):?>
                <?=$_SESSION['other']?>
            <?php endif;?>
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

      <form action = "complete.php" method = "POST">
        <!-- 送信用隠しフォーム -->
        <input type = "hidden" name = "id" value = "<?= $_POST['id']?>">
        <input type = "hidden" name = "maker" value = "<?= $_SESSION['maker']?>">
        <input type = "hidden" name = "product" value = "<?= $_SESSION['product']?>">
        <input type = "hidden" name = "price" value = "<?= $_SESSION['price']?>">
        <?php foreach($_SESSION['keyword'] as $keyword):?>
          <?= '<input type = "hidden" name = "keyword[]" value = "'.$keyword.'">'?>
        <?php endforeach;?>
        <?php if(isset($_SESSION['other'])):?>
        <input type = "hidden" name = "other" value = "<?= $_SESSION['other']?>">
        <?php endif;?>
        <input type = "hidden" name = "image" value = "<?=$_SESSION['image']?>">

        <div class = "center">
          <input type = "submit" value = "更新" class="btn btn-primary">
        </div>
      </form>

      <div class = "center">
        <a href ="search.php">商品検索画面</a>
      </div>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
