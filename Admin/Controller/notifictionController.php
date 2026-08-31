<?php
require "../../Model/notificationModal.php";
require "../Utilities/Sanitization.php";
include "../../Admin/DB Operations/notificationOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Id'])) {
        $notification = new Notification();
        $notification->setMessage(Sanitization::test_input($_POST["Message"]));
        $notification->setStatus(Sanitization::test_input($_POST["Status"]));
        $notification->setCategory(Sanitization::test_input($_POST["Category"]));

        DBnotification::update($notification);
    } else if ($_POST["action"] == 'delete') {
        DBnotification::delete($_POST["id"]);
    } else {
        $notification = new Notification();
        $notification->setMessage(Sanitization::test_input($_POST["Message"]));
        $notification->setStatus(Sanitization::test_input($_POST["Status"]));
        $notification->setCategory(Sanitization::test_input($_POST["Category"]));

        DBnotification::insert($notification);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location: ../View/dashboard.php");