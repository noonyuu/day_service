<?php
//　ログイン認証
if (isset($_SESSION['pass_save'])) {
  //　ログイン維持状態の時ログインページへ飛ぼうとしている
  if (basename($_SERVER['PHP_SELF']) === 'login.php') {
    header("Location:board_home.php");
  }
} else {
  // 仮置き
  //　ログインページ以外へ飛ぼうとしているときにログインページへ遷移させる
  if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location:login.php");
  }
}