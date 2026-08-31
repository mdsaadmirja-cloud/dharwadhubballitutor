<?php
$config = require_once("../../views/config.php");
require "../model/templateModel.php";
require "../Utilities/Sanitization.php";
include "../dblayer/templateOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['messageId'])) {
        $template = new Template();
        $template->setsender(Sanitization::test_input($_POST["sender"]));
        $template->setmessage(Sanitization::test_input($_POST["message"]));
        $template->setCreatedBy(Sanitization::test_input($_POST["CreatedBy"]));
        $template->setModifiedBy(Sanitization::test_input($_POST["ModifiedBy"]));
        $template->setmessageId(Sanitization::test_input($_POST["messageId"]));

        DBtemplate::update($template);
    } else if ($_POST["action"] == 'delete') {
        DBtemplate::delete($_POST["id"]);
    } else {
        $template = new Template();
        $template->setsender(Sanitization::test_input($_POST["sender"]));
        $template->setmessage(Sanitization::test_input($_POST["message"]));
        $template->setCreatedBy(Sanitization::test_input($_POST["CreatedBy"]));
        $template->setModifiedBy(Sanitization::test_input($_POST["ModifiedBy"]));
        DBtemplate::insert($template);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location:../views/smsDetails.php");
