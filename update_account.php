<?php
// セッション開始
session_start();
if (!isset($_SESSION['name']) || !$_SESSION['name']) {
  header('Location:login.php');
  exit;
}

require_once dirname(__FILE__) . '/function/db_connection.php';
require_once dirname(__FILE__) . '/function/select.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $auth = $_GET['auth'];

  $db = connection();
  if ($auth == "user") {
    $sql = "SELECT * FROM user WHERE user_id = :id";
  } else if ($auth == "emp") {
    $sql = "SELECT * FROM emp WHERE emp_id = :id";
  } else if ($auth == "admin") {
    $sql = "SELECT * FROM admin WHERE admin_id = :id";
  } else {
    header('Location:board_home.php');
    exit;
  }
  if ($stmt = $db->prepare($sql)) {
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
  } else {
    die('データベース接続エラー');
  }
}

if (isset($_POST['user_update'])) {
  $user_id = $_POST['user_id'];
  $user_name = $_POST['user_name'];
  $user_gender = $_POST['user_gender'];
  $user_tel = $_POST['user_tel'];
  $user_address = $_POST['user_address'];
  $user_mail = $_POST['user_mail'];
  $user_mgr = $_POST['user_mgr'];

  $db = connection();

  $sql = "UPDATE user SET user_name = ?, user_gender = ?, user_tel = ?, user_address = ?, user_mail = ?, user_mgr = ? WHERE user_id = ?";
  // プレースホルダーに対する値を指定してバインド
  $stmt = $db->prepare($sql);
  $stmt->bindValue(1, $user_name);
  $stmt->bindValue(2, $user_gender);
  $stmt->bindValue(3, $user_tel);
  $stmt->bindValue(4, $user_address);
  $stmt->bindValue(5, $user_mail);
  $stmt->bindValue(6, $user_mgr);
  $stmt->bindValue(7, $user_id);

  // クエリの実行
  $result = $stmt->execute();

  if ($result) {
    // 更新が成功した場合の処理
    header('Location: account_info.php');
    exit;
  } else {
    // 更新が失敗した場合の処理
    echo "更新に失敗しました";
  }
}

if (isset($_POST['emp_update'])) {
  $emp_id = $_POST['emp_id'];
  $emp_name = $_POST['emp_name'];
  $emp_gender = $_POST['emp_gender'];
  $emp_tel = $_POST['emp_tel'];
  $emp_address = $_POST['emp_address'];
  $emp_mail = $_POST['emp_mail'];

  $db = connection();

  $sql = "UPDATE emp SET emp_name = ?, emp_gender = ?, emp_tel = ?, emp_address = ?, emp_mail = ? WHERE emp_id = ?";
  // プレースホルダーに対する値を指定してバインド
  $stmt = $db->prepare($sql);
  $stmt->bindValue(1, $emp_name);
  $stmt->bindValue(2, $emp_gender);
  $stmt->bindValue(3, $emp_tel);
  $stmt->bindValue(4, $emp_address);
  $stmt->bindValue(5, $emp_mail);
  $stmt->bindValue(6, $emp_id);

  // クエリの実行
  $result = $stmt->execute();

  if ($result) {
    // 更新が成功した場合の処理
    header('Location: account_info.php');
    exit;
  } else {
    // 更新が失敗した場合の処理
    echo "更新に失敗しました";
  }
}

if (isset($_POST['admin_update'])) {
  $admin_id = $_POST['admin_id'];
  $admin_name = $_POST['admin_name'];
  $admin_gender = $_POST['admin_gender'];
  $admin_tel = $_POST['admin_tel'];
  $admin_address = $_POST['admin_address'];
  $admin_mail = $_POST['admin_mail'];

  $db = connection();

  $sql = "UPDATE admin SET admin_name = ?, admin_gender = ?, admin_tel = ?, admin_address = ?, admin_mail = ? WHERE admin_id = ?";
  // プレースホルダーに対する値を指定してバインド
  $stmt = $db->prepare($sql);
  $stmt->bindValue(1, $admin_name);
  $stmt->bindValue(2, $admin_gender);
  $stmt->bindValue(3, $admin_tel);
  $stmt->bindValue(4, $admin_address);
  $stmt->bindValue(5, $admin_mail);
  $stmt->bindValue(6, $admin_id);

  // クエリの実行
  $result = $stmt->execute();

  if ($result) {
    // 更新が成功した場合の処理
    header('Location: account_info.php');
    exit;
  } else {
    // 更新が失敗した場合の処理
    echo "更新に失敗しました";
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント情報変更</title>
  <!-- tailwind -->
  <link href="./css/output.css" rel="stylesheet" />
  <!-- css -->
  <link rel="stylesheet" href="./css/my_style.css" />
</head>

<body class="bg-back-color">
  <!-- header -->
  <?php
  include 'navbar.php';
  ?>
  <main class="container p-0 mx-auto">
    <form action="" method="post">
      <?php
      if ($auth == "user") {
        echo "<h1 class='text-center mt-4'>ユーザーアカウント情報変更</h1>";
      ?>
        <!-- ID -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="number" name="user_id" id="user_id" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?= $row->user_id; ?>" readonly />
          <label for="user_id" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">ID</label>
        </div>
        <!-- 名前 -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="text" name="user_name" id="user_name" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?= $row->user_name; ?>" placeholder=" " required />
          <label for="user_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
        </div>
        <!-- 性別 -->
        <div class="relative mb-5 md:w-3/4 border-2 border-gray-200 bg-white m-5 rounded-lg mx-auto">
          <div class="flex flex-row h-20">
            <div class="flex items-center text-gray-500 pl-2">性別</div>
            <div class="flex flex-row items-center ml-20">
              <div class="inline-block mx-2">
                <input type="radio" id="user_female" name="user_gender" value="女性" class="hidden peer" required <?= ($row->user_gender) == '女性' ? 'checked' : '' ?> />
                <label for="user_female" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">女性</label>
              </div>
              <div class="inline-block mx-2">
                <input type="radio" id="user_male" name="user_gender" value="男性" class="hidden peer" <?= $row->user_gender == '男性' ? 'checked' : '' ?> />
                <label for="user_male" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">男性</label>
              </div>
            </div>
          </div>
        </div>
        <!-- tel -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="tel" name="user_tel" id="user_tel" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?= $row->user_tel; ?>" />
          <label for="user_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号</label>
        </div>
        <!-- mail -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="email" name="user_mail" id="user_mail" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?= $row->user_mail; ?>" />
          <label for="user_mail" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">メールアドレス</label>
        </div>
        <!-- address -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="text" name="user_address" id="user_address" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?= $row->user_address; ?>" />
          <label for="user_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">住所</label>
        </div>
        <!-- mgr -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <select name="user_mgr" id="user_mgr" class="block p-2 w-full h-20 text-lg rounded-lg border-2 bg-white" required>
            <option value="" hidden>職員ID</option>
            <?php
            $emps = emp_select();
            while ($emp = $emps->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . $emp['emp_id'] . "'" . (($row->user_mgr) == $emp['emp_id'] ? ' selected' : '') . ">" . "ID" . ":" . $emp['emp_id'] . "->" . $emp['emp_name'] . "</option>";
            }
            ?>
          </select>
          <label for="user_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">担当職員</label>
        </div>
        <!-- 送信ボタン -->
        <div class="flex justify-center mx-auto mb-5">
          <button type="submit" name="user_update" class="w-40 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">登録</button>
        </div>
      <?php
        // 職員アカウント情報変更
      } elseif ($auth == "emp") {
        echo "<h1 class='text-center mt-4'>職員アカウント情報変更</h1>";
      ?>
        <!-- ID -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="number" name="emp_id" id="emp_id" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?= $row->emp_id; ?>" readonly />
          <label for="emp_id" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">ID</label>
        </div>
        <!-- 名前 -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="text" name="emp_name" id="emp_name" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?= $row->emp_name; ?>" placeholder=" " required />
          <label for="emp_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
        </div>
        <!-- 性別 -->
        <div class="relative mb-5 md:w-3/4 border-2 border-gray-200 bg-white m-5 rounded-lg mx-auto">
          <div class="flex flex-row h-20">
            <div class="flex items-center text-gray-500 pl-2">性別</div>
            <div class="flex flex-row items-center ml-20">
              <div class="inline-block mx-2">
                <input type="radio" id="emp_female" name="emp_gender" value="女性" class="hidden peer" required <?= ($row->emp_gender) == '女性' ? 'checked' : '' ?> />
                <label for="emp_female" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">女性</label>
              </div>
              <div class="inline-block mx-2">
                <input type="radio" id="emp_male" name="emp_gender" value="男性" class="hidden peer" <?= $row->emp_gender == '男性' ? 'checked' : '' ?> />
                <label for="emp_male" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">男性</label>
              </div>
            </div>
          </div>
        </div>
        <!-- tel -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="tel" name="emp_tel" id="emp_tel" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?= $row->emp_tel; ?>" />
          <label for="emp_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号</label>
        </div>
        <!-- mail -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="email" name="emp_mail" id="emp_mail" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?= $row->emp_mail; ?>" />
          <label for="emp_mail" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">メールアドレス</label>
        </div>
        <!-- address -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="text" name="emp_address" id="emp_address" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?= $row->emp_address; ?>" />
          <label for="emp_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">住所</label>
        </div>
        <!-- 送信ボタン -->
        <div class="flex justify-center mx-auto mb-5">
          <button type="submit" name="emp_update" class="w-40 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">登録</button>
        </div>
      <?php
      // 管理者の場合
      } elseif ($auth == "admin") {
      ?>
        <!-- ID -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="number" name="admin_id" id="admin_id" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?= $row->admin_id; ?>" readonly />
          <label for="admin_id" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">ID</label>
        </div>
        <!-- 名前 -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="text" name="admin_name" id="admin_name" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" value="<?= $row->admin_name; ?>" placeholder=" " required />
          <label for="admin_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
        </div>
        <!-- 性別 -->
        <div class="relative mb-5 md:w-3/4 border-2 border-gray-200 bg-white m-5 rounded-lg mx-auto">
          <div class="flex flex-row h-20">
            <div class="flex items-center text-gray-500 pl-2">性別</div>
            <div class="flex flex-row items-center ml-20">
              <div class="inline-block mx-2">
                <input type="radio" id="admin_female" name="admin_gender" value="女性" class="hidden peer" required <?= ($row->admin_gender) == '女性' ? 'checked' : '' ?> />
                <label for="admin_female" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">女性</label>
              </div>
              <div class="inline-block mx-2">
                <input type="radio" id="admin_male" name="admin_gender" value="男性" class="hidden peer" <?= $row->admin_gender == '男性' ? 'checked' : '' ?> />
                <label for="admin_male" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">男性</label>
              </div>
            </div>
          </div>
        </div>
        <!-- tel -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="tel" name="admin_tel" id="admin_tel" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?= $row->admin_tel; ?>" />
          <label for="admin_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号</label>
        </div>
        <!-- mail -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="email" name="admin_mail" id="admin_mail" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="<?= $row->admin_mail; ?>" />
          <label for="admin_mail" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">メールアドレス</label>
        </div>
        <!-- address -->
        <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
          <input type="text" name="admin_address" id="admin_address" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?= $row->admin_address; ?>" />
          <label for="admin_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">住所</label>
        </div>
        <!-- 送信ボタン -->
        <div class="flex justify-center mx-auto mb-5">
          <button type="submit" name="admin_update" class="w-40 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">登録</button>
        </div>
      <?php
      }
      ?>
    </form>
  </main>
</body>

</html>