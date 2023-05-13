<?php
session_start();
if (isset($_SESSION['logout'])) {
  session_unset();
  session_destroy();
  header("location: login.php");
  exit;
} elseif ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
  // 直接アクセスされた場合の処理
  die('アクセスが許可されていません。');
}