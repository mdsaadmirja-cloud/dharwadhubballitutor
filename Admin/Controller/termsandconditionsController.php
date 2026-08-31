<?php
require "../model/termsandconditionsModel.php";
require "../Utilities/Sanitization.php";
require "../DB Operations/termsandconditionsOps.php";
require "../Utilities/Helper.php";
require_once("../../vendor/autoload.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['id'])) {
    $terms = new terms();
    $terms->setdescription(Sanitization::test_input($_POST["description"]));
    $quill_json = trim($_POST['hidden_element']);
    try {
      $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
      $result = $quill->render();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $result = Sanitization::test_input($result);
  
    DBterms::update($terms);
  }else{
    $terms = new terms();
    
    $quill_json = trim($_POST['hidden_element']);
    try {
      $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
      $result = $quill->render();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $result = Sanitization::test_input($result);
    $terms->setdescription(Sanitization::test_input($result));

    DBterms::insert($terms);
}
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location:../views/termsandconditions.php");