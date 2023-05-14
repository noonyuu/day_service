<?php
date_default_timezone_set('Asia/Tokyo');
// セッション開始
session_start();
if (!isset($_SESSION['name']) || !$_SESSION['name']) {
  header('Location:login.php');
  exit;
}
require_once dirname(__FILE__) . '/function/db_connection.php';
require_once dirname(__FILE__) . '/function/select.php';

function info()
{
  // 今日の日付を取得
  $today = date("Y-m-d");

  $db = connection();
  $sql = "SELECT u.user_name, i.user_state, i.user_comment ,i.time
        FROM user as u
        INNER JOIN user_info as i
        ON u.user_id = i.user_id
        WHERE i.env = '通常'";

  if (isset($_POST['e_selected_date'])) {
    $time = $_POST['e_selected_date'];
    $sql .= " AND i.time = '$time'";
  } else {
    $sql .= " AND i.time = '$today'";
  }
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
      <h1 class="flex justify-center text-3xl font-bold">体調確認報告一覧</h1>
      <div class="flex justify-center my-5">
        <div class="flex items-center ml-auto">
          <select name="day" id="" onchange="e_day_change(this)">
            <?php
            $info_date = info_date();
            $today = date("Y-n-j");
            if (!$info_date == 0) :
              $old_year = date("Y", strtotime($info_date['old']));
              $old_month = date("n", strtotime($info_date['old']));
              $old_day = date("j", strtotime($info_date['old']));

              $latest_year = date("Y", strtotime($info_date['latest']));
              $latest_month = date("n", strtotime($info_date['latest']));
              $latest_day = date("j", strtotime($info_date['latest']));

              for ($i = $old_year; $i <= $latest_year; $i++) :
                for ($j = $old_month; $j <= $latest_month; $j++) :
                  for ($k = $old_day; $k <= $latest_day; $k++) :
                    echo "<option value='" . $i . "-" . $j . "-" . $k . "'" . (($i . "-" . $j . "-" . $k) == $today ? 'selected' : '') . ">" . $i . "年" . $j . "月" . $k . "日" . "</option>";
                  endfor;
                endfor;
              endfor;
            else :
              echo "<option value=''>データがありません</option>";
            endif;
            ?>
          </select>
        </div>
      </div>
      <div class="table-over">
        <table class="table w-full text-center mt-5 border border-black vertical-line">
          <thead class="bg-gray-900 uppercase text-white">
            <th>名前</th>
            <th>状態</th>
            <th>コメント</th>
            <th>日時</th>
          </thead>
          <tbody id="table-body">
            <?php
            $infos = info();
            while ($info = $infos->fetch(PDO::FETCH_ASSOC)) {
            ?>
              <tr>
                <td class="h-12"><?= $info['user_name'] ?></td>
                <td class="h-12"><?= $info['user_state'] ?></td>
                <td class="h-12"><?= htmlspecialchars($info['user_comment']) ?></td>
                <td class="h-12"><?= $info['time'] ?></td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
  </main>
  <script src="./js/navbar.js"></script>
  <script src="./js/ajax.js"></script>
</body>

</html>