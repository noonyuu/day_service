<?php

function connection()
{
  // table connect
  $server_name = "localhost";
  $user_name = "root";
  $db_name = "day_service";
  $password = "root";

  $options = [
    // PDOの例外エラーを詳細にする
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // 結果を連想配列として返してくれる
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // エミュレートをオフにする
    PDO::ATTR_EMULATE_PREPARES => false,
  ];

  // データベースに接続
  try {
    return  new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8mb4", $user_name, $password, $options);
  } catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
  }
}
