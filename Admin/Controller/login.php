<?php

require_once("../model/userLoginModel.php");
require_once("../../blogadmin/dblayer/userOps.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $myusername = trim($_POST['user_email'] ?? '');
    $mypassword = $_POST['user_password'] ?? '';

    // TC_LOG_004 - Empty Fields
    if ($myusername === '' || $mypassword === '') {

        $_SESSION['login_error'] = "Email and password are required.";

        header("Location: ../View/login.php");
        exit();
    }

    $loginModel = new userLoginModel();

    $loginModel->set_username($myusername);
    $loginModel->set_userpassword($mypassword);

    // TC_LOG_003 - Unregistered Email
    if (!DBuser::userExists($myusername)) {

        $_SESSION['login_error'] = "User not found.";

        header("Location: ../View/login.php");
        exit();
    }

    // Check username/email + password
    $user = DBuser::checkUser($loginModel);

    // TC_LOG_001 - Valid Login
    if (!empty($user) && $user->get_userstatus() == 'Enable') {

        error_log($user->get_username());

        $_SESSION['login_user'] = $user->get_username();
        $_SESSION['User_type'] = $user->get_usertype();
        $_SESSION['Role_Id'] = $user->getUserRole();
        $_SESSION['user'] = $user->get_username();
        $_SESSION['role'] = 'admin';
        $_SESSION['user_id'] = $user->get_id();

        if ($_SESSION['Role_Id'] == 2) {

            header("Location: ../View/enquiries.php");

        } else {

            header("Location: ../View/dashboard.php");
        }

        exit();

    } else {

        // TC_LOG_002 - Invalid Password
        $_SESSION['login_error'] = "Invalid credentials.";

        header("Location: ../View/login.php");
        exit();
    }
}