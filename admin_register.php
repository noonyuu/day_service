<?php
if (!isset($_SESSION)) {
  session_start(); // セッションがアクティブでない場合にのみ開始
}
if (!isset($_SESSION['access_granted']) || !$_SESSION['access_granted']) {
  die('アクセスが許可されていません。');
}
require_once dirname(__FILE__) . '/function/db_connection.php';

$message = "";

if (isset($_POST['admin_submit'])) {
  // 入力されたデータを処理する
  // $user_id = $_POST["user_id"];
  $admin_name = $_POST["admin_name"];
  $admin_gender = $_POST["admin_gender"];
  $admin_tel = $_POST["admin_tel"];
  $admin_address = $_POST["admin_address"];
  $admin_mail = $_POST["admin_mail"];
  $admin_password = $_POST["admin_password"];
  $admin_password_conf = $_POST["admin_password_conf"];

  create_admin_account($admin_name, $admin_gender, $admin_tel, $admin_address, $admin_mail, $admin_password, $admin_password_conf);
}


function create_admin_account($admin_name, $admin_gender, $admin_tel, $admin_address, $admin_mail, $admin_password, $admin_password_conf)
{
  // データベースに接続
  $db = connection();

  // Password Check
  if ($admin_password != $admin_password_conf) {
    $message = "パスワードが一致しません";
    setcookie("error", $message, time() + 60);
    exit;
  } else {
    // データベースに同じID or emailが存在しないことを確認する
    $stmt = $db->prepare("SELECT COUNT(*) FROM admin WHERE admin_mail = ?");
    $stmt->execute([$admin_mail]);
    $count = $stmt->fetch(PDO::FETCH_COLUMN);
  }


  if ($count > 0) {
    $message = "メールアドレスが既に使用されています。別のメールアドレスを使用してください。";
    setcookie("error", $message, time() + 60);
    exit;
  } else {
    // Hash password
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

    // トランザクション開始
    $db->beginTransaction();
    $stmt = $db->prepare("INSERT INTO admin (admin_name, admin_pass ,admin_gender, admin_tel, admin_address, admin_mail) VALUES (?,?,?,?,?,?)");

    try {
      $stmt->execute([$admin_name, $hashed_password, $admin_gender, $admin_tel, $admin_address, $admin_mail]);
      $db->commit();
    } catch (PDOException $e) {
      $db->rollback();
      $message = $e->getMessage();
      setcookie("error", $message, time() + 60);
      exit;
    } finally {
      $db = null;
      session_unset();
      session_destroy();
      header("location: login.php");
      exit;
    }
  }
}
?>
<?php
if (!isset($_SESSION['show'])) {
?>
  <!DOCTYPE html>
  <html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者アカウント作成</title>
    <!-- tailwind -->
    <link href="./css/output.css" rel="stylesheet" />
    <!-- css -->
    <link rel="stylesheet" href="./css/my_style.css" />
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>

  <body>
    <main>
      <div class="z-50 relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
        <div class="px-4 py-5 flex-col justify-center">
          <?php
          if (isset($_COOKIE["error"])) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded' role='alert'>エラー: {$_COOKIE["error"]}</div>";
          }
          ?>
          <div class="tab-content w-full">
            <div class="block" id="user">
            <?php
          }
            ?>
            <div class="flex-row justify-center">
              <h1 class="text-center my-4">管理者アカウント作成</h1>
              <form action="" method="post">
                <!-- name -->
                <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                  <input type="text" name="admin_name" id="admin_name" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="admin_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
                </div>
                <!-- 性別 -->
                <div class="relative mb-5 md:w-3/4 border-2 border-gray-200 bg-white m-5 rounded-lg mx-auto">
                  <div class="flex flex-row h-20">
                    <div class="flex items-center text-gray-500 pl-2">性別</div>
                    <div class="flex flex-row items-center ml-20">
                      <div class="inline-block mx-2">
                        <input type="radio" id="admin_female" name="admin_gender" value="女性" class="hidden peer" required />
                        <label for="admin_female" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">女性</label>
                      </div>
                      <div class="inline-block mx-2">
                        <input type="radio" id="admin_male" name="admin_gender" value="男性" class="hidden peer" />
                        <label for="admin_male" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">男性</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- tel -->
                <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                  <input type="tel" name="admin_tel" id="admin_tel" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="admin_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号</label>
                </div>
                <!-- mail -->
                <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                  <input type="email" name="admin_mail" id="admin_mail" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                  <label for="admin_mail" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">メールアドレス</label>
                </div>
                <!-- address -->
                <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                  <input type="text" name="admin_address" id="admin_address" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="admin_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">住所</label>
                </div>
                <!-- password -->
                <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                  <input type="password" name="admin_password" id="admin_password" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="admin_password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード</label>
                </div>
                <!-- password_conf -->
                <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                  <input type="password" name="admin_password_conf" id="admin_password_conf" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="admin_password_conf" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード確認</label>
                </div>
                <!-- 送信ボタン -->
                <div class="flex justify-center mx-auto">
                  <button type="submit" name="admin_submit" class="w-40 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">登録</button>
                </div>
              </form>
            </div>
            </div>
          </div>
          <?php
          if (!isset($_SESSION['show'])) {
          ?>
        </div>
      </div>
    </main>
  </body>

  </html>
<?php
          }
?>