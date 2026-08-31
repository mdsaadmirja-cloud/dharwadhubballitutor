<?php
// lms/controller/googleCallback.php
session_start();
require_once '../vendor/autoload.php';
require_once '../model/User.php';
require_once '../../Admin/DB Operations/AdmissionsOps.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

$client = new Google_Client();
$client->setClientId('777019480535-0kcilsdi2fg4qufsc3115n6nq4rtsh4t.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://dharwadhubballitutor.com/lms/controller/googleCallback.php');
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
    $_SESSION['login_user'] = $user;
    $_SESSION['user'] = $user;
    $_SESSION['email'] = $user['email']; // Assuming 'role' is part of the user data
    $_SESSION['role']=$user['role'];
    $_SESSION['courseId'] = DBadmission::getAdmissionByEmail($user['email'])['Courseid']; // Assuming this function exists
    error_log($_SESSION['courseId']);
    header('Location: /lms/views/student_dashboard.php');
    exit();
}
