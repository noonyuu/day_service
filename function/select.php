<?php
function emp_select()
{
  $db = connection();
  $sql = "SELECT * FROM emp";

  if ($result = $db->query($sql)) {
    return $result;
  } else {
    die('データベース接続エラー');
  }
  exit;
}

function user_select()
{
  $db = connection();
  $sql = "SELECT u.user_id, u.user_name, u.user_gender, u.user_address, u.user_tel, u.user_mail, e.emp_name 
          FROM user as u
          INNER JOIN emp as e 
          ON u.user_mgr = e.emp_id";

  if ($result = $db->query($sql)) {
    return $result;
  } else {
    die('データベース接続エラー');
  }
  exit;
}
function admin_select()
{
  $db = connection();
  $sql = "SELECT * FROM admin";

  if ($result = $db->query($sql)) {
    return $result;
  } else {
    die('データベース接続エラー');
  }
  exit;
}
function info_date()
{
  $db = connection();
  $sql = "SELECT MIN(time) as old, Max(time) as latest FROM user_info";

  if ($result = $db->query($sql)) {
    $rowCount = $result->rowCount(); // 件数取得
    if ($rowCount > 0) {
      return $result->fetch(PDO::FETCH_ASSOC);
    } else {
      return 0;
    }
  } else {
    die('データベース接続エラー');
  }
  exit;
}
