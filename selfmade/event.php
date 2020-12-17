<?php

define('CALENDAR_ID', '4h9qjp4k8q830e42p27tg6uh40@group.calendar.google.com');
define('API_KEY', 'AIzaSyCOlYAqx1SvKkhswQUIqAj58OkDoirEaiY');
define('API_URL', 'https://www.googleapis.com/calendar/v3/calendars/'.CALENDAR_ID.'/events?key='.API_KEY.'&singleEvents=true');

// ここでデータを取得する範囲を決めています
$t = mktime(0, 0, 0, 1, 1, 2019);
$t2 = mktime(0, 0, 0, 12, 31, 2050);

$params = array();
$params[] = 'orderBy=startTime'; //並べ替え（startTime or updated)
// $params[] = 'maxResults=10'; //最大件数　指定なしだと全て取得
$params[] = 'timeMin='.urlencode(date('c', $t)); //開始日付
$params[] = 'timeMax='.urlencode(date('c', $t2)); //終了日付

$url = API_URL.'&'.implode('&', $params);

$results = file_get_contents($url);


$json = json_decode($results, true);

$count = count($json['items']); //登録催事数

?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>催事一覧｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/result.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/header.php") ?>

    <div class = "wrapper">
      <h1>催事一覧</h1>
      <div class = "center">
        <a href = "index.php">TOP</a>
      </div>

      <div class = "center">

        <table>
          <tr>
            <th>メーカー</th>
            <th>URL</th>
            <th>開始</th>
            <th>終了</th>
          </tr>
          <!-- 現在催事一覧 -->
          <?php if(!isset($_GET['prev'])):?>
          <?php for($i = 0; $i < $count; $i++):?>
            <?php if($json['items'][$i]['end']['date'] > date("Y-m-d")):?>
              <tr>
                <td><?=$json['items'][$i]['summary']?></td>
                <?php if(isset($json['items'][$i]['description'])):?>
                <td><?=$json['items'][$i]['description']?></td>
                <?php else:?>
                <td></td>
                <?php endif;?>
                <td><?=$json['items'][$i]['start']['date']?></td>
                <td><?=$json['items'][$i]['end']['date']?></td>
              </tr>
            <?php endif;?>
          <?php endfor;?>
          <?php endif;?>

          <!-- 過去の催事一覧 -->
          <?php if(isset($_GET['prev'])):?>
          <?php for($i = 0; $i < $count; $i++):?>
            <?php if($json['items'][$i]['end']['date'] < date("Y-m-d") && $json['items'][$i]['end']['date'] > date("Y",strtotime('-1 year'))):?>
              <tr>
                <td><?=$json['items'][$i]['summary']?></td>
                <?php if(isset($json['items'][$i]['description'])):?>
                <td><?=$json['items'][$i]['description']?></td>
                <?php else:?>
                <td></td>
                <?php endif;?>
                <td><?=$json['items'][$i]['start']['date']?></td>
                <td><?=$json['items'][$i]['end']['date']?></td>
              </tr>
            <?php endif;?>
          <?php endfor;?>
          <?php endif;?>
        </table>
      </div>

      <div class = "center">
        <?php if(!isset($_GET['prev'])):?>
        <a href = "?prev">過去一年間の催事一覧</a>
        <?php elseif(isset($_GET['prev'])):?>
        <a href = "event.php">催事一覧</a>
        <?php endif?>
      </div>

      <!-- <div class = "center">
        <iframe src="https://calendar.google.com/calendar/embed?src=4h9qjp4k8q830e42p27tg6uh40%40group.calendar.google.com&ctz=Asia%2FTokyo" style="border: 0" width="768" height="900" frameborder="0" scrolling="no"></iframe>
      </div> -->
    </div>


    <?php require("require/footer.php"); ?>
  </body>
</html>
