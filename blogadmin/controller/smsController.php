<?php
$config = require_once("../../views/config.php");
require "../model/smsModel.php";
require "../Utilities/Sanitization.php";
require "../DBlayer/smsOps.php";
require "../Utilities/Helper.php";

require_once("../../vendor/autoload.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Id'])) {
        $sms = new Sms();
        $sms->setId(Sanitization::test_input($_POST["Id"]));
        error_log($_POST["Id"]);
        $sms->setusername(Sanitization::test_input($_POST["username"]));
        $sms->setAPIkey(Sanitization::test_input($_POST["APIkey"]));
        $sms->setkey(Sanitization::test_input($_POST["key"]));
        $sms->settest(Sanitization::test_input($_POST["test"]));
        $sms->setModifiedBy(Sanitization::test_input($_POST["ModifiedOn"]));
        $sms->setCreatedOn(Sanitization::test_input($_POST["CreatedOn"]));
        $sms->setModifiedBy(Sanitization::test_input($_POST["ModifiedBy"]));
        $sms->setCreatedBy(Sanitization::test_input($_POST["CreatedBy"]));

        DBsms::update($sms);
    } else {
        $sms = new Sms();
        $sms->setusername(Sanitization::test_input($_POST["username"]));
        $sms->setAPIkey(Sanitization::test_input($_POST["APIkey"]));
        $sms->setkey(Sanitization::test_input($_POST["key"]));
        $sms->settest(Sanitization::test_input($_POST["test"]));
        $sms->setModifiedBy(Sanitization::test_input($_POST["ModifiedOn"]));
        $sms->setCreatedOn(Sanitization::test_input($_POST["CreatedOn"]));
        $sms->setModifiedBy(Sanitization::test_input($_POST["ModifiedBy"]));
        $sms->setCreatedBy(Sanitization::test_input($_POST["CreatedBy"]));

        DBsms::insert($sms);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location:../views/smsDetails.php");
