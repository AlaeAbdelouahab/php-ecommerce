<?php
session_start();

$_SESSION = [];

session_destroy();


if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}

if (isset($_COOKIE['admin'])) {
    setcookie('admin', '', time() - 3600, '/');
}

header("Location: ../Client/login.php");
exit;
?>
