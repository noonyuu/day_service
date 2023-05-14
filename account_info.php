<?php
require_once dirname(__FILE__) . '/function/db_connection.php';
require_once dirname(__FILE__) . '/function/select.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント情報</title>
  <!-- tailwind css -->
  <link href="./css/output.css" rel="stylesheet">
  <!-- css -->
  <link href="./css/my_style.css" rel="stylesheet">
  <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <!-- header -->
  <?php
  include 'navbar.php';
  ?>
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
            <div class="tab-content w-full">
              <div class="block" id="user">
                <div class="flex-row justify-center">
                  <h1 class="text-center my-4">ユーザーアカウント</h1>
                  <div class="table-over">
                    <table class="table w-full text-center mt-5 border border-gray-900 vertical-line">
                      <thead class="bg-gray-900 uppercase text-white">
                        <th>ID</th>
                        <th>名前</th>
                        <th>性別</th>
                        <th>電話番号</th>
                        <th>住所</th>
                        <th>メール</th>
                        <th>担当職員</th>
                      </thead>
                      <tbody>
                        <?php
                        $users = user_select();
                        while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                          <tr>
                            <td><?= $user['user_id'] ?></td>
                            <td><?= $user['user_name'] ?></td>
                            <td><?= $user['user_gender'] ?></td>
                            <td><?= $user['user_tel'] ?></td>
                            <td><?= $user['user_address'] ?></td>
                            <td><?= $user['user_mail'] ?></td>
                            <td><?= $user['emp_name'] ?></td>
                          </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="hidden" id="emp">
                <div class="flex-row justify-center">
                  <h1 class="text-center my-4 mx-auto">職員アカウント</h1>
                  <div class="table-over">
                    <table class="table w-full text-center mt-5 border border-gray-900 vertical-line">
                      <thead class="bg-gray-900 uppercase text-white">
                        <th>ID</th>
                        <th>名前</th>
                        <th>性別</th>
                        <th>電話番号</th>
                        <th>住所</th>
                        <th>メール</th>
                      </thead>
                      <tbody>
                        <?php
                        $emps = emp_select();
                        while ($emp = $emps->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                          <tr>
                            <td><?= $emp['emp_id'] ?></td>
                            <td><?= $emp['emp_name'] ?></td>
                            <td><?= $emp['emp_gender'] ?></td>
                            <td><?= $emp['emp_tel'] ?></td>
                            <td><?= $emp['emp_address'] ?></td>
                            <td><?= $emp['emp_mail'] ?></td>
                          </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- admin -->
              <div class="hidden" id="admin">
                <div class="flex-row justify-center">
                  <h1 class="text-center my-4 mx-auto">管理者アカウント</h1>
                  <table class="table w-full text-center mt-5 border border-gray-900 vertical-line">
                    <div class="table-over">
                      <thead class="bg-gray-900 uppercase text-white">
                        <th>ID</th>
                        <th>名前</th>
                        <th>性別</th>
                        <th>電話番号</th>
                        <th>住所</th>
                        <th>メール</th>
                      </thead>
                      <tbody>
                        <?php
                        $admins = admin_select();
                        while ($admin = $admins->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                          <tr>
                            <td><?= $admin['admin_id'] ?></td>
                            <td><?= $admin['admin_name'] ?></td>
                            <td><?= $admin['admin_gender'] ?></td>
                            <td><?= $admin['admin_tel'] ?></td>
                            <td><?= $admin['admin_address'] ?></td>
                            <td><?= $admin['admin_mail'] ?></td>
                          </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                  </table>
                </div>
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