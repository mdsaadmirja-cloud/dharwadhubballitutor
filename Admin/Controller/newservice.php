<?php
//require "session.php";
require "../model/Servicemodel.php";
require "../Utilities/Sanitization.php";
require "../Utilities/Helper.php";
require_once "../DB Operations/ServicesOps.php";
// require "../Admin/navbar.php";

require "../DB Operations/CoursesOps.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $admit=new Services();
    $admit->set_name(Sanitization::test_input($_POST["name"]));
    $admit->set_email(Sanitization::test_input($_POST["email"]));
    $admit->set_phone(Sanitization::test_input($_POST["phone"]));
    $admit->set_services(Sanitization::test_input($_POST["services"]));
    $admit->set_totalamt(Sanitization::test_input($_POST["totalamt"]));
    $admit->set_paidamt(Sanitization::test_input($_POST["paidamt"]));
    $admit->set_pendingamt(Sanitization::test_input($_POST["pendingamt"]));
    $admit->set_paymentmode(Sanitization::test_input($_POST["paymentmode"]));
    $admit->set_candidateid(Sanitization::test_input($_POST["candidateid"]));
    $filename="". $admit->get_name().date("Y-m-d").".pdf";

    $admit->set_paymentreceipt($filename);
    $admit->get_paymentreceipt();
    DBservice::insert($admit);
    
    $receipt=DBservice::paymentdetails( $admit->get_candidateid());
    Helper::paymentreceipt($receipt);
  }
?>
<html>

    <head>
        <title> New course </title>
    </head>

    <body>
        <?php 
//  header("location:../View/services.php");
?>


    </body>

</html>