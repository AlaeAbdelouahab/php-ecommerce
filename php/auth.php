<?php
session_start();

if (!isset($_SESSION['idu']) && isset($_COOKIE['remember_me'])) {
    $_SESSION['idu'] = $_COOKIE['remember_me'];
}

if(!isset($_SESSION['admin']) && isset($_COOKIE['admin'])){
    $_SESSION['admin']=1;
}
?>