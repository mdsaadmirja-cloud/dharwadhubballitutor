<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login_user'])) {
    header("Location: ../View/login.php");
    exit;
}

$user_check = $_SESSION['login_user'];

?>