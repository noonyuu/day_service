<?php
session_start();
if (isset($_SESSION['name'])) {
} elseif ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
  // 直接アクセスされた場合の処理
  die('アクセスが許可されていません。');
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="refresh" content="2;URL=board_home.php">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>loading</title>
  <!-- tailwind css -->
  <link href="./css/output.css" rel="stylesheet">
</head>

<body class="bg-back-color">
  <div class="h-screen flex flex-col justify-center items-center">
    <img src="./images/logo.png" alt="ロゴ" class="w-60 h-60">
    <div class="flex justify-center">
      <div class="animate-ping h-2 w-2 bg-blue-600 rounded-full"></div>
      <div class="animate-ping h-2 w-2 bg-blue-600 rounded-full mx-4"></div>
      <div class="animate-ping h-2 w-2 bg-blue-600 rounded-full"></div>
    </div>
  </div>
</body>

</html>