<?php
// セッション開始
session_start();
require_once dirname(__FILE__) . '/function/db_connection.php';
require_once dirname(__FILE__) . '/function/auto_login.php';

function user_login($user_name, $user_tel, $pass_save)
{
  // db情報取得
  $db = connection();
  // // ユーザーIDからユーザー情報を取得
  $sql = "SELECT * FROM usertest WHERE user_tel = '$user_tel'";

  if ($result = $db->query($sql)) {
    //check username
    $rowCount = $result->rowCount();
    if ($rowCount >= 1) {
      $user = $result->fetch();
      //check password
      if (($user_tel == $user['user_tel']) && ($user_name == $user['user_name'])){
        // user_idをセッションに保存
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['user_name'];
        //  権限をセッションに保存
        $_SESSION['auth'] = "user";
      } else {
        setcookie("login_error", "名前、又は電話番号が一致しません", time() + 60);
      }
    }
  }
  //ログイン保持の有無の確認
  if ($pass_save) {
    //ログイン保持する
    $_SESSION['pass_save'] = true;
  }
  header("location: board_home.php");
}

function emp_login($emp_id, $emp_password, $pass_save)
{
  // db情報取得
  $db = connection();
  $sql = "";
  $pass = "";
  // IDから情報を取得
  if($emp_id < 100){
    $sql = "SELECT * FROM admin WHERE admin_id = '$emp_id'";
    $pass = "admin_pass";
  }else{
    $sql = "SELECT * FROM emptest WHERE emp_id = '$emp_id'";
    $pass = "emp_pass";
  }

  if ($result = $db->query($sql)) {
    //check empname
    $rowCount = $result->rowCount();
    if ($rowCount >= 1) {
      $emp = $result->fetch();
      //check password
      if (password_verify($emp_password, $emp[$pass])) {

        //  権限をセッションに保存
        if ($emp_id < 100) {
          $_SESSION['admin_id'] = $emp_id;
          $_SESSION['name'] = $emp['admin_name'];
          $_SESSION['auth'] = "admin";
        } else {
          $_SESSION['emp_id'] = $emp_id;
          $_SESSION['name'] = $emp['emp_name'];
          $_SESSION['auth'] = "emp";
        }
      } else {
        setcookie("login_error", "パスワードが一致しません", time() + 60);
        header("location: login.php");
      }
    }
  }
  //ログイン保持の有無の確認
  if ($pass_save) {
    //ログイン保持する
    $_SESSION['pass_save'] = true;
  }
  // var_dump($_SESSION['auth']);
  header("location: board_home.php");
}

class Login
{
  public function userLogin()
  {
    if (isset($_POST['user_login'])) {
      $user_name = $_POST['user_name'];
      $user_tel = $_POST['user_tel'];
      if (isset($_POST['pass_save'])) {
        // チェックボックスが選択された場合の処理
        $pass_save = $_POST['pass_save'];
      } else {
        // チェックボックスが選択されなかった場合の処理
        $pass_save = "false";
      }
      $pass_save = (($pass_save == "true") ? true : false);
    }
    user_login($user_name, $user_tel, $pass_save);
  }
  public function empLogin()
  {
    if (isset($_POST['emp_login'])) {
      $emp_id = $_POST['emp_id'];
      $emp_password = $_POST['emp_password'];
      if (isset($_POST['pass_save'])) {
        // チェックボックスが選択された場合の処理
        $pass_save = $_POST['pass_save'];
      } else {
        // チェックボックスが選択されなかった場合の処理
        $pass_save = "false";
      }
      $pass_save = (($pass_save == "true") ? true : false);
    }
    emp_login($emp_id, $emp_password, $pass_save);
  }
}

$action = $_GET['action'] ?? 'defaultAction';
$login = new Login();
if (method_exists($login, $action)) {
  $login->$action();
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ログインページ</title>
  <!-- tailwind css -->
  <link href="./css/output.css" rel="stylesheet">
  <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-back-color h-screen">
  <main class="container mx-auto block justify-center" id="tab-id">
    <?php
    if (isset($_COOKIE["login_error"])) {
      echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded' role='alert'>エラー: {$_COOKIE["login_error"]}</div>";
    }
    ?>
    <div class="flex justify-center mt-3">
      <img src="./images/logo.png" alt="ロゴ" class="w-32 h-32">
    </div>
    <!-- 切り替えタブ -->
    <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
      <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
        <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-blue-600" onclick="change(event,'user')">本人用</a>
      </li>
      <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
        <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-blue-600 bg-white" onclick="change(event,'famiry')">職員用</a>
      </li>
    </ul>

    <div class="flex justify-center">
      <div class="w-11/12 md:w-7/12 bg-white flex-row justify-center border border-gray-200 rounded-2xl p-5 m-5 shadow-lg">
        <h1 class="font-body text-center justify-center my-4 text-4xl">ログイン</h1>

        <div class="tab-content mt-6">
          <!-- ログインフォーム -->
          <div class="block" id="user">
            <form action="login.php?action=userLogin" method="post">
              <!-- 名前 -->
              <div class="relative mb-5 mx-auto md:w-3/4">
                <input type="text" name="user_name" id="user_name" class="block p-4 w-full h-16 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="user_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
              </div>
              <!-- 電話番号 -->
              <div class="relative mb-5 mx-auto md:w-3/4">
                <input type="tel" id="user_tel" name="user_tel" class="block p-4 w-full h-16 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="user_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号(数字のみ)</label>
              </div>
              <div class="relative mb-5 mx-auto md:w-3/4">
                <input type="checkbox" name="pass_save" id="pass_save" value="true">
                <label for="pass_save">ログイン状態を維持</label>
              </div>
              <div class="relative mb-5 mx-auto flex justify-center">
                <button type="submit" class="w-2/3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit" name="user_login">ログイン</button>
              </div>
            </form>
          </div>

          <!-- 職員用 -->
          <div class="hidden" id="famiry">
            <div class="flex-row justify-center">
              <form action="login.php?action=empLogin" method="post">
                <!-- id -->
                <div class="relative mb-5 mx-auto md:w-3/4">
                  <input type="number" name="emp_id" id="emp_id" class="block p-4 w-full h-16 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="emp_id" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">ID</label>
                </div>
                <!-- 名前 -->
                <!-- <div class="relative mb-5 mx-auto md:w-3/4">
                  <input type="text" name="emp_name" id="emp_name" class="block p-4 w-full h-16 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="emp_name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">名前</label>
                </div> -->
                <!-- tel -->
                <!-- <div class="relative mb-5 mx-auto md:w-3/4">
                  <input type="tel" id="emp_tel" name="emp_tel" class="block p-4 w-full h-16 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" maxlength="11" placeholder=" " required />
                  <label for="emp_tel" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">電話番号(数字のみ)</label>
                </div> -->
                <!-- password -->
                <div class="relative mb-5 mx-auto md:w-3/4">
                  <input type="password" id="emp_password" name="emp_password" class="block p-4 w-full h-16 text-lg text-black rounded-lg border-2 bg-white focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                  <label for="emp_password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-2 scale-75 top-2 z-10 origin-[0] px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-2 left-1">パスワード</label>
                  <div class="absolute inset-y-0 right-1 flex my-2 items-center">
                    <i class="text-right fa fa-eye pr-3" id="password_eye"></i>
                  </div>
                </div>

                <div class="relative mb-5 mx-auto md:w-3/4">
                  <input type="checkbox" name="pass_save" id="pass_save" value="true">
                  <label for="pass_save">ログイン状態を維持</label>
                </div>
                <div class="relative mb-5 mx-auto flex justify-center">
                  <button type="submit" class="w-2/3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit" name="emp_login">ログイン</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="./js/pass.js"></script>
  <script type="text/javascript" src="./js/tab_sele.js"></script>

</body>

</html>