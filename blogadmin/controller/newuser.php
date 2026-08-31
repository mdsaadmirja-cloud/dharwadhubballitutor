<?php
require_once "../Model/usermodel.php";
require_once "../Utilities/Sanitization.php";
require_once "../DB Operations/userOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


  if (isset($_POST['userid'])) {
    $user = new User();
    $user->set_username(Sanitization::test_input($_POST["username"]));
    $user->set_usercontact(Sanitization::test_input($_POST["usercontact"]));
    $user->set_useremail(Sanitization::test_input($_POST["useremail"]));
    $user->set_userpassword(Sanitization::test_input($_POST["userpassword"]));
    $user->set_usertype(Sanitization::test_input($_POST["usertype"]));
    $user->set_userstatus(Sanitization::test_input($_POST["userstatus"]));
    $user->set_id(Sanitization::test_input($_POST['userid']));
    DBuser::update($user);
  } else if ($_POST["action"] == 'delete') {
    DBuser::delete($_POST["id"]);
  } else {
    $user = new User();
    $user->set_username(Sanitization::test_input($_POST["username"]));
    $user->set_usercontact(Sanitization::test_input($_POST["usercontact"]));
    $user->set_useremail(Sanitization::test_input($_POST["useremail"]));
    $user->set_userpassword(Sanitization::test_input($_POST["userpassword"]));
    $user->set_usertype(Sanitization::test_input($_POST["usertype"]));
    $user->set_userstatus(Sanitization::test_input($_POST["userstatus"]));
    DBuser::insert($user);
  }
  header("location: ../View/user.php");
}
