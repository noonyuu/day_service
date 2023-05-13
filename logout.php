<?php
session_start();
require_once dirname(__FILE__) . '/function/auto_login.php';

session_unset();
session_destroy();

header("location: login.php");
exit;
