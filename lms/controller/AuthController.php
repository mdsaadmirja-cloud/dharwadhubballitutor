<?php
// lms/controller/AuthController.php
session_start();
require_once '../vendor/autoload.php';
require_once '../model/User.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

class AuthController {
    public static function googleLogin() {
        $client = new Google_Client();
        $client->setClientId('777019480535-0kcilsdi2fg4qufsc3115n6nq4rtsh4t.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('https://dharwadhubballitutor.com/lms/controller/AuthController.php');
        $client->addScope('email');
        $client->addScope('profile');

        if (!isset($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
            exit();
        } else {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            $user = User::findOrCreateGoogleUser($userInfo);
            $_SESSION['user'] = $user;
            
            error_log($user['email']);
            $_SESSION['email'] =$user['email'];
            header('Location: /lms/views/student_dashboard.php');
            exit();
        }
    }

    public static function adminLogin($email, $password) {
        $user = User::findByEmail($email);
        if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: /lms/views/admin_dashboard.php');
            exit();
        } else {
            return false;
        }
    }

    public static function logout() {
        session_destroy();
        header('Location: ../views/login.php');
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'googleLogin') {
    AuthController::googleLogin();
} 