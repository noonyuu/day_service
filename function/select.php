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
