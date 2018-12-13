<?php
  
require_once('config/config.php');

//room_idがなかったらランダムに生成する
if (!isset($_GET['room_id']))  {
  $res = $db->query("select id from chessdata");
  if  (count($res) > 100) {
      header("Location:http://google.com");
      exit;
  }
  while(true) {
    $new_room_id = mt_rand(100000, 999999);
    foreach( $res as $value ) {
      if ($value['id'] === $new_room_id) continue;
    }
   break;
  }
  header( "Location: game.php?room_id=$new_room_id");
  exit;
}

$request_room_id = (int)$_GET['room_id'];
//room_idの値がおかしかったらリダイレクト
if(($request_room_id < 99999) or ($request_room_id > 999999)) {
  header("Location: index.php");
  exit;
}

$stmt = $db->prepare('SELECT * FROM chessdata WHERE id = :id');
$params = array(':id' => $request_room_id);
$stmt->execute($params);
$result = $stmt->fetch();

if (!isset($_SESSION['room_id']) or $_SESSION['room_id'] !== $request_room_id) {
  //初めてのroom_idだったら、新しく部屋を作る
  if ($result === false) { 
    $sql = "INSERT INTO chessdata (id, turn) VALUES (:id, :turn)";
    $stmt = $db->prepare($sql);
    $params = array(':id' => $request_room_id, ':turn' => 0);
    $stmt->execute($params);
    $_SESSION['room_id'] = $request_room_id;
    $_SESSION['color'] = 'w';
  } else if ($result['turn'] === 0) {
  //誰かがいて、待機状態（turnが0）ならturnを1にして試合スタート)
    $sql = "UPDATE chessdata  SET turn = :turn WHERE id = :id";
    $stmt = $db->prepare($sql);
    $params = array(':id' => $request_room_id, ':turn' => 1);
    $stmt->execute($params);
    $_SESSION['room_id'] = $request_room_id;
    $_SESSION['color'] = 'b';
  }
}
    
 
echo $result['id'];


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>チェスゲーム</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
    <meta name="description" content="">
    <link rel="stylesheet" href="css/chessboard-0.3.0.min.css" />
  </head>
  <body style="margin:0;padding:0;overflow:hidden;">
      <div id="board" style="width:400px"></div>
<p><?php
?></p>
<p>Status: <span id="status"></span></p>
<p>FEN: <span id="fen"></span></p>
<p>PGN: <span id="pgn"></span></p>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="js/jq-ui.js"></script>
    <script src="js/chessboard-0.3.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.2/chess.min.js"></script>
    <script src="js/game.js"></script>
    <script>document.addEventListener('touchmove', function(e) {e.preventDefault();}, {passive: false});</script>
  </body>
</html>
