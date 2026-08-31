<?php 
require_once("../model/userLoginModel.php");
require_once("../dblayer/userOps.php");
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
        $db=ConnectDb::getInstance();
        $myusername = mysqli_real_escape_string($db->getConnection(),$_POST['user_email']);
        $mypassword = mysqli_real_escape_string($db->getConnection(),$_POST['user_password']); 
        $loginModel= new userLoginModel();
        $loginModel->set_username($myusername);
        $loginModel->set_userpassword($mypassword);
        $user=DBuser::checkUser($loginModel);
      // If result matched $myusername and $mypassword, table row must be 1 row
      if(!empty($user) && $user->get_userstatus()=='Enable') {
         // echo "Validateed successfully";
         session_start();
         error_log($user->get_username());
         $_SESSION['login_user'] = $user->get_username();
         $_SESSION['User_type']= $user->get_usertype();
         header("location: ../views/dashboard.php");
      }else {
         header("location: ../views/login.php");
      }
   }
?>