<?php

session_start();
if (!isset($_SESSION['name']) || !$_SESSION['name']) {
  header('Location:login.php');
  exit;
}
require_once dirname(__FILE__) . '/function/db_connection.php';
require_once dirname(__FILE__) . '/function/emp_select.php';

$message = "";

class CreateAccount
{
  // ユーザーのアカウントを作成する
  public function userCreateAccount()
  {
    if (isset($_POST['user_submit'])) {
      // 入力されたデータを処理する
      // $user_id = $_POST["user_id"];
      $user_name = $_POST["user_name"];
      $user_gender = $_POST["user_gender"];
      $user_tel = $_POST["user_tel"];
      $user_address = $_POST["user_address"];
      $user_mail = $_POST["user_mail"];
      $user_mgr = $_POST["user_mgr"];
      $user_password = $_POST["user_password"];
      $user_password_conf = $_POST["user_password_conf"];
    }
    create_user_account($user_name, $user_gender, $user_tel, $user_address, $user_mail, $user_mgr, $user_password, $user_password_conf);
  }

  public function empCreateAccount()
  // 職員のアカウントを作成する
  {
    if (isset($_POST['emp_submit'])) {
      $emp_name = $_POST["emp_name"];
      $emp_gender = $_POST["emp_gender"];
      $emp_tel = $_POST["emp_tel"];
      $emp_address = $_POST["emp_address"];
      $emp_mail = $_POST["emp_mail"];
      $emp_password = $_POST["emp_password"];
      $emp_password_conf = $_POST["emp_password_conf"];
    }
    create_emp_account($emp_name, $emp_gender, $emp_tel, $emp_address, $emp_mail, $emp_password, $emp_password_conf);
  }
}

$action = $_GET['action'] ?? 'defaultAction';
$CreateAccount = new CreateAccount();
if (method_exists($CreateAccount, $action)) {
  $CreateAccount->$action();
}

// ユーザーのアカウント作成
function create_user_account($user_name, $user_gender, $user_tel, $user_address, $user_mail, $user_mgr, $user_password, $user_password_conf)
{
  // データベースに接続
  $db = connection();

  // Password Check
  if ($user_password != $user_password_conf) {
    $message = "パスワードが一致しません";
    setcookie("error", $message, time() + 60);
    exit;
  } else {
    // データベースに同じID or emailが存在しないことを確認する
    $stmt = $db->prepare("SELECT COUNT(*) FROM user WHERE user_mail = ?");
    $stmt->execute([$user_mail]);
    $count = $stmt->fetch(PDO::FETCH_COLUMN);
  }


  if ($count > 0) {
    $message = "メールアドレスが既に使用されています。別のメールアドレスを使用してください。";
    setcookie("error", $message, time() + 60);
    exit;
  } else {
    // Hash password
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // トランザクション開始
    $db->beginTransaction();
    $stmt = $db->prepare("INSERT INTO user (user_name, user_pass ,user_gender, user_tel, user_address, user_mail, user_mgr) VALUES (?,?,?,?,?,?,?)");

    try {
      $stmt->execute([$user_name, $hashed_password, $user_gender, $user_tel, $user_address, $user_mail, $user_mgr]);
      $db->commit();
    } catch (PDOException $e) {
      $db->rollback();
      $message = $e->getMessage();
      setcookie("error", $message, time() + 60);
      exit;
    } finally {
      header("location: create_account.php");
      $db = null;
    }
    exit;
  }
}

// 職員のアカウント作成
function create_emp_account($emp_name, $emp_gender, $emp_tel, $emp_address, $emp_mail, $emp_password, $emp_password_conf)
{
  // データベースに接続
  $db = connection();
  // Password Check
  if ($emp_password != $emp_password_conf) {
    $message = "パスワードが一致しません";
    setcookie("error", $message, time() + 60);
    exit;
  } else {
    // データベースに同じID or emailが存在しないことを確認する
    $stmt = $db->prepare("SELECT COUNT(*) FROM emp WHERE emp_mail = ?");
    $stmt->execute([$emp_mail]);
    $count = $stmt->fetch(PDO::FETCH_COLUMN);
  }

  if ($count > 0) {
    $message = "メールアドレスが既に使用されています。別のメールアドレスを使用してください。";
    setcookie("error", $message, time() + 60);
    exit;
  } else {
    // Hash password
    $hashed_password = password_hash($emp_password, PASSWORD_DEFAULT);

    // トランザクション開始
    $db->beginTransaction();
    $stmt = $db->prepare("INSERT INTO emp (emp_name, emp_pass ,emp_gender, emp_tel, emp_address, emp_mail) VALUES (?,?,?,?,?,?)");
    try {
      $stmt->execute([$emp_name, $hashed_password, $emp_gender, $emp_tel, $emp_address, $emp_mail]);
      $db->commit();
    } catch (PDOException $e) {
      $db->rollback();
      $error = $e->getMessage();
      setcookie("error", $error, time() + 60);
      return false;
    } finally {
      $db = null;
      header("location: create_account.php");
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>災害用掲示板</title>
  <!-- tailwind -->
  <link href="./css/output.css" rel="stylesheet" />
  <!-- css -->
  <link rel="stylesheet" href="./css/my_style.css" />
  <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100">
  <!-- header -->
  <?php
  include 'navbar.php';
  ?>
  <!-- main-->
  <main>
    <div class="flex flex-wrap z-50" id="tab-id">
      <div class="w-full">
        <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
          <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
            <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-blue-600" onclick="change(event,'user')">ユーザー</a>
          </li>
          <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
            <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-blue-600 bg-white" onclick="change(event,'emp')">職員</a>
          </li>
          <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
            <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-blue-600 bg-white" onclick="change(event,'admin')">管理者</a>
          </li>
        </ul>

        <div class="z-50 relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
          <div class="px-4 py-5 flex-col justify-center">
            <?php
            if (isset($_COOKIE["error"])) {
              echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded' role='alert'>エラー: {$_COOKIE["error"]}</div>";
            }
            ?>
            <div class="tab-content w-full">
              <div class="block" id="user">
                <div class="flex-row justify-center">
                  <h1 class="text-center my-4">ユーザーアカウント作成</h1>
                  <form action="create_account.php?action=userCreateAccount" method="post">
                    <!-- id -->
                    <!-- <div class="relative mb-5 m-5 md:w-3/4">
                      <input type="number" name="user_id" id="user_id" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="user_id" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">ID</label>
                    </div> -->
                    <!-- name -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="text" name="user_name" id="user_name" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="user_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
                    </div>
                    <!-- 性別 -->
                    <div class="relative mb-5 md:w-3/4 border-2 border-gray-200 bg-white m-5 rounded-lg mx-auto">
                      <div class="flex flex-row h-20">
                        <div class="flex items-center text-gray-500 pl-2">性別</div>
                        <div class="flex flex-row items-center ml-20">
                          <div class="inline-block mx-2">
                            <input type="radio" id="user_female" name="user_gender" value="女性" class="hidden peer" required />
                            <label for="user_female" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">女性</label>
                          </div>
                          <div class="inline-block mx-2">
                            <input type="radio" id="user_male" name="user_gender" value="男性" class="hidden peer" />
                            <label for="user_male" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">男性</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- tel -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="tel" name="user_tel" id="user_tel" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="user_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号</label>
                    </div>
                    <!-- mail -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="email" name="user_mail" id="user_mail" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                      <label for="user_mail" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">メールアドレス</label>
                    </div>
                    <!-- address -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="text" name="user_address" id="user_address" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="user_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">住所</label>
                    </div>
                    <!-- mgr -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <select name="user_mgr" id="user_mgr" class="block p-2 w-full h-20 text-lg rounded-lg border-2 bg-white" required>
                        <option value="" hidden>職員ID</option>
                        <?php
                        $emps = emp_select();
                        while ($emp = $emps->fetch(PDO::FETCH_ASSOC)) {
                          echo "<option value='" . $emp['emp_id'] . "'>" . "ID" . ":" . $emp['emp_id'] . "->" . $emp['emp_name'] . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <!-- <div class="relative mb-5 m-5 md:w-3/4 mx-auto">

                        <input type="number" name="user_mgr" id="user_mgr" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                        <label for="user_mgr" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">担当職員ID</label>
                      </div> -->


                    <!-- password -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="password" name="user_password" id="user_password" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="user_password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード</label>
                    </div>
                    <!-- password_conf -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="password" name="user_password_conf" id="user_password_conf" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="user_password_conf" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード確認</label>
                    </div>
                    <!-- 送信ボタン -->
                    <div class="flex justify-center mx-auto">
                      <button type="submit" name="user_submit" class="w-40 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">登録</button>
                    </div>
                  </form>
                </div>
              </div>

              <div class="hidden" id="emp">
                <div class="flex-row justify-center">
                  <h1 class="text-center my-4 mx-auto">職員アカウント作成</h1>
                  <form action="create_account.php?action=empCreateAccount" method="post">
                    <!-- id -->
                    <!-- <div class="relative mb-5 m-5 md:w-3/4">
                      <input type="number" name="emp_id" id="emp_id" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_id" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">ID</label>
                    </div> -->
                    <!-- name -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="text" name="emp_name" id="emp_name" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
                    </div>
                    <!-- 性別 -->
                    <div class="relative mb-5 md:w-3/4 border-2 border-gray-200 bg-white m-5 rounded-lg mx-auto">
                      <div class="flex flex-row h-20">
                        <div class="flex items-center text-gray-500 pl-2">性別</div>
                        <div class="flex flex-row items-center ml-20">
                          <div class="inline-block mx-2">
                            <input type="radio" id="emp_female" name="emp_gender" value="女性" class="hidden peer" required />
                            <label for="emp_female" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">女性</label>
                          </div>
                          <div class="inline-block mx-2">
                            <input type="radio" id="emp_male" name="emp_gender" value="男性" class="hidden peer" />
                            <label for="emp_male" class="inline-flex items-center justify-between w-full px-3 py-2 text-black bg-white border border-gray-900 rounded-lg cursor-pointer peer-checked:border-black peer-checked:bg-black peer-checked:text-white">男性</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- tel -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="tel" name="emp_tel" id="emp_tel" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号</label>
                    </div>
                    <!-- mail -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="email" name="emp_mail" id="emp_mail" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_mail" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">メールアドレス</label>
                    </div>
                    <!-- address -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="text" name="emp_address" id="emp_address" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_address" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">住所</label>
                    </div>
                    <!-- password -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="password" name="emp_password" id="emp_password" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード</label>
                    </div>
                    <!-- password_conf -->
                    <div class="relative mb-5 m-5 md:w-3/4 mx-auto">
                      <input type="password" name="emp_password_conf" id="password_conf" class="block p-4 w-full h-20 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                      <label for="emp_password_conf" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード確認</label>
                    </div>
                    <!-- 送信ボタン -->
                    <div class="flex justify-center mx-auto">
                      <button type="submit" name="emp_submit" class="w-40 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">登録</button>
                    </div>
                  </form>
                </div>
              </div>
              <!-- admin -->
              <div class="hidden" id="admin">
                <?php
                $_SESSION['access_granted'] = true;
                $_SESSION['show'] = true;
                include('admin_register.php');
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script type="text/javascript" src="./js/tab_sele.js"></script>
  <script src="./js/navbar.js"></script>
</body>

</html>