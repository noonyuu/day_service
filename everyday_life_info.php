<?php
// セッション開始
session_start();
require_once dirname(__FILE__) . '/function/db_connection.php';

function info()
{
  $db = connection();
  $sql = "SELECT u.user_name, i.user_state, i.user_comment ,i.time
        FROM usertest as u
        INNER JOIN user_info as i
        ON u.user_id = i.user_id
        WHERE i.env = '通常'";
  if ($result = $db->query($sql)) {
    return $result;
  } else {
    die('データベース接続エラー');
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>体調確認</title>
  <!-- tailwind css -->
  <link href="./css/output.css" rel="stylesheet">
  <!-- css -->
  <link href="./css/my_style.css" rel="stylesheet">
  <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-back-color">
  <header>
    <?php
      include 'navbar.php';
    ?>
  </header>

  <main class="container p-0 mt-5 mx-auto">
    <div class="flex-row justify-center mx-5">
      <div class="flex justify-center text-5xl font-bold">体調確認</div>
      <table class="table w-full text-center mt-5 border border-black vertical-line">
        <thead class="bg-gray-900 uppercase text-white">
          <th>名前</th>
          <th>状態</th>
          <th>コメント</th>
          <th>日時</th>
        </thead>
        <tbody>
          <?php
          $infos = info();
          while ($info = $infos->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <tr>
              <td class="h-12 w-[30]"><?= $info['user_name'] ?></td>
              <td class="h-12 w-[20]"><?= $info['user_state'] ?></td>
              <td class="h-12 w-[30]"><?= $info['user_comment'] ?></td>
              <td class="h-12 w-[20]"><?= $info['time'] ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <?php
  ?>
  <!-- <script src="./js/navbar.js"></script> -->
</body>

</html>