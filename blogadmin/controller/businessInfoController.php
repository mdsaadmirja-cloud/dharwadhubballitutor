<?php
$config= require_once("../../views/config.php");
require "../model/businessModel.php";
require "../Utilities/Sanitization.php";
require "../dblayer/businessOps.php";

require "../Utilities/Helper.php";
require_once("../../vendor/autoload.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['companyId'])){
    $business = new Business();
    $business->setBusinessId(Sanitization::test_input($_POST["companyId"]));
    $business->setBusinessName(Sanitization::test_input($_POST["companyname"]));
    $business->setBusinessContact(Sanitization::test_input($_POST["companycontact"]));
    $business->setBusinessEmail(Sanitization::test_input($_POST["companyemail"]));
    $business->setBusinessTag(Sanitization::test_input($_POST["companytag"]));
    $business->setBusinessAddress(Sanitization::test_input($_POST["companyaddress"]));
    $business->setBusinessGSTIN(Sanitization::test_input($_POST["companyGSTIN"]));
  
    $quill_json = trim($_POST['hidden_element']);
    try {
      $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
      $result = $quill->render();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $result=Sanitization::test_input($result);
    $business->setBusinessAboutBusiness($result);
    if (isset($_FILES["logoImage"])) {
      
      $filetoupload = $_FILES["logoImage"];
      Helper::fileupload($filetoupload, "../img/");
      $business->setBusinessLogoImage($_FILES["logoImage"]['name']);
  }
    DBbusiness::update($business);
  }else{
    $business = new Business();
    $business->setBusinessName(Sanitization::test_input($_POST["companyname"]));
    $business->setBusinessContact(Sanitization::test_input($_POST["companycontact"]));
    $business->setBusinessEmail(Sanitization::test_input($_POST["companyemail"]));
    $business->setBusinessTag(Sanitization::test_input($_POST["companytag"]));
    $business->setBusinessAddress(Sanitization::test_input($_POST["companyaddress"]));
    $business->setBusinessGSTIN(Sanitization::test_input($_POST["companyGSTIN"]));
  
    $quill_json = trim($_POST['hidden_element']);
    try {
      $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
      $result = $quill->render();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $result=Sanitization::test_input($result);
  
    $business->setBusinessAboutBusiness($result);
    if (isset($_FILES["logoImage"])) {
      $filetoupload = $_FILES["logoImage"];
      Helper::fileupload($filetoupload, "../img/");
      $business->setBusinessLogoImage($_FILES["logoImage"]['name']);
    }
    DBbusiness::insert($business);
  }
  } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  }
header("location:../views/dashboard.php");
