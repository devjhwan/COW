<?php
session_start();
$cookie_name = "hotelAuth";

unset($_SESSION['user_id']);
session_destroy();

setcookie($cookie_name, '', time() - 3600, "/");

header("Location: ../html/login.html");
exit;
