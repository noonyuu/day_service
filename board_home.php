<?php
// セッション開始
session_start();
if (!isset($_SESSION['name']) || !$_SESSION['name']) {
  header('Location:login.php');
  exit;
}
require_once dirname(__FILE__) . '/function/db_connection.php';

// ボタンを押した時にdbの状態が「災害」なら通常、「通常」なら災害に切り替える
if (isset($_POST['submit'])) {
  // db情報取得
  $db = connection();
  $admin_id = $_SESSION['admin_id'];

  $sql = "SELECT env FROM env";
  if ($result = $db->query($sql)) {
    $envs = $result->fetch(PDO::FETCH_ASSOC);
    $env = $envs['env'];
  }

  // トランザクション開始
  $db->beginTransaction();
  if ($env == "通常") {
    $sql = "UPDATE env SET env = '災害', admin_id = $admin_id";
  } elseif ($env == "災害") {
    $sql = "UPDATE env SET env = '通常', admin_id = $admin_id";
  }

  try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $db->commit();
  } catch (PDOException $e) {
    $db->rollBack();
    echo "データベース接続エラー: " . $e->getMessage();
  } finally {
    $db = null;
    header("location: board_home.php");
    exit;
  }
}

// db情報取得
$db = connection();
$sql = "SELECT env FROM env";
if ($result = $db->query($sql)) {
  $envs = $result->fetch(PDO::FETCH_ASSOC);
  $env = $envs['env'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<!-- 会員の方はこちら -->

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>掲示板</title>
  <!-- tailwind css -->
  <link href="./css/output.css" rel="stylesheet">
  <!-- css -->
  <link href="./css/my_style.css" rel="stylesheet">
  <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-back-color">
  <!-- header -->
  <?php
  include 'navbar.php';
  ?>
  <main class="container mx-auto p-0">
    <!-- user nemu -->
    <div class="h-screen flex flex-col justify-center items-center">
      <?php
      if ($_SESSION['auth'] == "admin") {
      ?>
        <div class="text-5xl">現在：<?= $env ?>時用</div>
        <div class="mt-10 max-w-sm w-full">
          <!-- 状況の切り替え -->
          <button id="open-modal" name="switch_btn" class="w-full border border-gray-800 bg-red-500 rounded-2xl flex flex-col justify-between leading-normal my-5">
            <div class="w-full text-white text-3xl flex justify-center py-3">状況切り替え</div>
          </button>
          <!-- 災害時安否確認 -->
          <div class="border border-gray-800 bg-red-500 rounded-2xl flex flex-col justify-between leading-normal my-5" onclick="location.href='./disaster_info.php'">
            <div class="text-white text-3xl flex justify-center py-3">災害時安否確認</div>
          </div>
          <!-- 通常時体調確認 -->
          <div class="border border-gray-800 bg-white rounded-2xl flex flex-col justify-between leading-normal my-5" onclick="location.href='./everyday_life_info.php'">
            <div class="text-black text-3xl flex justify-center py-3">通常時体調確認</div>
          </div>
          <!-- ユーザー登録 -->
          <div class="border border-gray-800 bg-white rounded-2xl flex flex-col justify-between leading-normal my-5" onclick="location.href='./create_account.php'">
            <div class="text-black text-3xl flex justify-center py-3">ユーザー登録</div>
          </div>
          <!-- ユーザー一覧 -->
          <div class="border border-gray-800 bg-white rounded-2xl flex flex-col justify-between leading-normal my-5" onclick="location.href='./account_info.php'">
            <div class="text-black text-3xl flex justify-center py-3">ユーザー一覧</div>
          </div>

          <!-- 状態切り替え時に使用するモーダル -->
          <div id="modal" class="modal hidden fixed inset-0 flex items-center justify-center">
            <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-75"></div>

            <div class="modal-container bg-white w-full md:w-1/3 mx-auto rounded shadow-lg z-50 overflow-y-auto">
              <div class="relative modal-content py-4 px-6">
                <div class="flex flex-col justify-between items-center pb-3">
                  <button id="close-modal" class="absolute top-0 right-0 flex items-center abstract text-black close-modal p-2">
                    <span class="text-3xl">×</span>
                  </button>
                  <div class="flex flex-col">
                    <p class="w-full text-2xl font-bold text-center flex justify-center items-center text-gray-950 mb-2">状態の切り替え</p>
                    <p class="w-full text-2xl font-bold text-center text-gray-950">
                      現在「<?= $env ?>」状態です
                    </p>
                  </div>
                </div>

                <form action="" method="post" class="my-4 flex justify-center">
                  <button type="submit" name="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">
                    変更する
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php
      } elseif ($_SESSION['auth'] == "emp") {
      ?>
        <div class="max-w-sm w-full">
          <!-- 災害時安否確認 -->
          <div class="border border-gray-800 bg-red-500 rounded-2xl flex flex-col justify-between leading-normal my-5" onclick="location.href='./disaster_info.php'">
            <div class="text-white text-3xl flex justify-center py-3">災害時安否確認</div>
          </div>
          <!-- 通常時体調確認 -->
          <div class="border border-gray-800 bg-white rounded-2xl flex flex-col justify-between leading-normal my-5" onclick="location.href='./everyday_life_info.php'">
            <div class="text-black text-3xl flex justify-center py-3">通常時体調確認</div>
          </div>
        </div>
        <?php
      } elseif ($_SESSION['auth'] == "user") {
        if ($env == "災害") {
        ?>
          <!-- 災害時 -->
          <div class="max-w-sm w-full m-5 mt-10" onclick="location.href='./report.php'">
            <div class="border border-gray-800 bg-white rounded-2xl  flex flex-col justify-between leading-normal">
              <div class="flex justify-end pt-2 pr-2">
                <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center">
                  <span class="flex items-center"><i class="fa-solid fa-pen text-2xl" style="color: #fafafa;"></i></span>
                </div>
              </div>
              <div class="text-gray-900 font-bold flex justify-center text-2xl">あんぴほうこく</div>
              <div class="text-gray-900 font-bold text-6xl flex justify-center pb-12">安否報告</div>
            </div>
          </div>
        <?php
        } elseif ($env == "通常") {
        ?>
          <!-- 1つ目 -->
          <div class="max-w-sm w-full m-5 mt-10" onclick="location.href='./report.php'">
            <div class="border border-gray-800 bg-white rounded-2xl  flex flex-col justify-between leading-normal">
              <div class="flex justify-end pt-2 pr-2">
                <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center">
                  <span class="flex items-center"><i class="fa-solid fa-pen text-2xl" style="color: #fafafa;"></i></span>
                </div>
              </div>
              <div class="text-gray-900 font-bold flex justify-center text-2xl">たいちょうほうこく</div>
              <div class="text-gray-900 font-bold text-6xl flex justify-center pb-12">体調報告</div>
            </div>
          </div>
      <?php
        }
      }
      ?>
      <!-- (奇数調整)-->
      <!-- <div class="max-w-sm w-full m-5 flex-auto"></div> -->
    </div>
  </main>
  <script src="./js/navbar.js"></script>
  <script src="./js/modal.js"></script>
</body>

</html>