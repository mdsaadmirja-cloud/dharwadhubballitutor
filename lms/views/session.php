<?php
   session_start();
   
   $user_check = $_SESSION['user'];
   if(!isset($_SESSION['user'])){
      header("location:../../lms/views/login.php");
   }
?>