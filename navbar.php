<?php
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
  // 直接アクセスされた場合の処理
  die('アクセスが許可されていません。');
}
// session_start();
$_SESSION['logout'] = true;
?>

<header class="z-[100] fixed top-0 w-full bg-gray-700 border-gray-200">
  <nav>
    <div class="h-20 max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="#" class="flex items-center">
        <img src="./images/logo.png" alt="logo" class="w-10 h-10 mr-2 mt-2">
        <span class="self-center text-3xl text-white font-semibold whitespace-nowrap">掲示板</span>
      </a>
      <!-- ユーザーの名前を取得 -->
      <!-- <a href="#" class="nav-link ml-auto mr-4"><i class="ml-2 mr-4 fas fa-user text-white"></i><?= $_SESSION['name'] ?></a> -->
      <a href="logout.php" class="nav-link ml-auto mr-4 text-white"><i class="fa-sharp fa-solid fa-arrow-right-from-bracket mr-2"></i>ログアウト</a>
      <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-white rounded-lg md:hidden md:flex-row" aria-controls="navbar-default" aria-expanded="false">
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul class="z-[100] font-medium flex flex-col bg-gray-600 p-4 md:p-0 mt-4 md:flex-row md:space-x-8 md:mt-0">
          <li>
            <a href="board_home.php" class="block py-2 pl-3 pr-4 text-white rounded">Home</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>