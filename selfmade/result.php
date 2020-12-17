<?php
ini_set("session.gc_maxlifetime",300);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
session_start();
require_once("config/config.php");
require_once("model/Products.php");

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
  $pro = new Products($host,$dbname,$user,$pass);
  $pro->connectDB();

  if($_POST){
    // 入力チェック
    $message2 = $pro->validateSearch($_POST);
    if($message2){
      $_SESSION['error2'] = $message2;
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit();
    }else{
      $_SESSION['error2'] = array();
    }
  }

  if(isset($_GET['edit'])){
    // 編集
    $resultMaker['Pro'] = $pro->findByProduct($_GET['edit']);

  }elseif(isset($_GET['del'])){
    $pro->delete($_GET['del']);
    $resultMaker = $pro->findByMaker($_GET);
  }else{
    // 参照
    $resultMaker = $pro->findByMaker($_POST);
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

      <?php if(!isset($_GET['edit'])):?>
      <table>
        <tr>
          <th>メーカー</th>
          <th>商品ID</th>
          <th>商品名</th>
          <th>画像</th>
          <th>価格(税込)</th>
          <th></th>
          <th></th>
        </tr>
        <?php foreach($resultMaker as $row):?>
        <tr>
          <td><?=$row['maker']?></td>
          <td><?=$row['id']?></td>
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
          <td>¥<?=$row['price']?></td>
          <?php if(!isset($_GET['edit'])):?>
          <td>
            <form action = "shop.php" method = "POST">
              <input type = "submit" value ="取扱店舗">
              <!-- 送信用隠しフォーム -->
              <input type = "hidden" name = "id" value = "<?=$row['id']?>">
              <input type = "hidden" name = "maker" value = "<?=$row['maker']?>">
              <input type = "hidden" name = "product" value = "<?=$row['product']?>">
            </form>
          </td>
          <td>
            <p><a href = "?edit=<?=$row['id']?>">編集</a>
            <a href = "?del=<?=$row['id']?>&maker=<?=$row['maker']?>" onClick = "if(!confirm('<?=$row['product']?>を削除しますか？')) return false;">削除</a></p>
          </td>

          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </table>
      <?php endif;?>

      <!-- 編集画面 -->
      <?php if(isset($_GET['edit'])):?>
      <table>
        <tr>
          <th>メーカー</th>
          <th>商品ID</th>
          <th>商品名</th>
          <th>価格</th>
        </tr>
        <tr>
          <td><?= $resultMaker['Pro']['maker'];?></td>
          <td><?= $resultMaker['Pro']['id']?></td>
          <td><?= $resultMaker['Pro']['product']?></td>
          <td>¥<?= $resultMaker['Pro']['price']?></td>
        </tr>
      </table>

      <div class = "edit">
        <p>編集内容を入力してください</p>

        <div class = "error">
          <!-- エラーメッセージ -->
          <?php if(!empty($_SESSION['error'])):?>
            <?php foreach($_SESSION['error'] as $value):?>
              <?= $value."<br>"?>
            <?php endforeach;?>
          <?php endif;?>
        </div>

        <form action = "confirm.php" method = "POST">
          <!-- 送信用隠しフォーム -->
          <input type = "hidden" name = "id" value ="<?=$resultMaker['Pro']['id']?>">

          <table>
            <tr>
              <th>メーカー<span>*</span></th>
              <td><input type = "text" name = "maker" value = "<?=$resultMaker['Pro']['maker']?>"></td>
            </tr>
            <tr>
              <th>商品名<span>*</span></th>
              <td><input type = "text" name = "product" value = "<?=$resultMaker['Pro']['product']?>"></td>
            </tr>
            <tr>
              <th>価格（税込）<span>*</span></th>
              <td><input type = "text" name = "price" value = "<?=$resultMaker['Pro']['price']?>"></td>
            </tr>

            <tr>
              <th></th>
              <td><input type = "file" name = "image" class="form-control-file" id="image" accept = "image/*"></td>
            </tr>
            <tr>
              <th></th>
              <td id = "showfile"></td>
            </tr>
            <tr>
              <th></th>
              <td class = "hidden"><input type = "button" class = "clear" value = "ファイルをクリア"></td>
            </tr>
          </table>

          <div class = "center">
            <input type = "submit" value = "更新" class= "btn btn-primary">
          </div>
        </form>
      <?php endif;?>


      </div>
      </div>


    <?php require("require/footer.php") ?>
  </body>
</html>
