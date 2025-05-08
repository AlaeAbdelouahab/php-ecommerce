<?php
session_start();

if (!isset($_SESSION['idu']) && isset($_COOKIE['remember_me'])) {
    $_SESSION['idu'] = $_COOKIE['remember_me'];
}
?>