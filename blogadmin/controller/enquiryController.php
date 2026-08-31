<?php
$config= require_once("../../views/config.php");
require "../model/enquirymodel.php";
require "../Utilities/Sanitization.php";
include "../dblayer/enquiryOps.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $enq = new enquiry();
  $enq->set_name(Sanitization::test_input($_POST["name"]));
  $enq->set_email(Sanitization::test_input($_POST["email"]));
  $enq->set_phone(Sanitization::test_input($_POST["phone"]));
  $enq->set_enqueryFor(Sanitization::test_input($_POST["query"]));
  DBenquery::insert($enq);
  if (isset($_POST['isAdmin']))
    header("location: ../View/enquiry.php");
  else
    header("location: ../../");
}
