<?php
$configs = require_once("../../views/config.php");
require "../model/PrivacyPolicyModel.php";
require "../Utilities/Sanitization.php";
require "../dblayer/PrivacyPolicyOps.php";
require "../Utilities/Helper.php";
require_once("../../vendor/autoload.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
      $Privacy = new Privacy();
      $quill_json = trim($_POST['hidden_element']);
      try {
        $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
        $result = $quill->render();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      $Privacy->setdescription($result);
    
      DBPrivacy::update($Privacy);
    }else{
        $Privacy = new Privacy();
        
        $quill_json = trim($_POST['hidden_element']);
        try {
          $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
          $result = $quill->render();
        } catch (Exception $e) {
          echo $e->getMessage();
        }
        $Privacy->setdescription($result);
        DBPrivacy::insert($Privacy);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location:../views/PrivacyPolicy.php");
      