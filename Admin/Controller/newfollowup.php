<?php

require "../model/followupmodel.php";
require "../Utilities/Sanitization.php";
include "../DB Operations/followupOps.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $follow=new Enqfollowup();
    $follow->set_followenqid(Sanitization::test_input($_POST["followenqid"]));
    $follow->set_followcomment(Sanitization::test_input($_POST["followcomment"]));
    $follow->set_followupBy(Sanitization::test_input($_POST["followupBy"]));
    $follow->set_followupDate(Sanitization::test_input($_POST["followupDate"]));
    $follow->set_Status(Sanitization::test_input($_POST["status"]));

    // $follow->setEnqStatus(Sanitization::test_input($_POST["enqStatus"]));
    DBfollow::insert($follow); 
    header("location:../View/enquiries.php");
  } else if($_SERVER["REQUEST_METHOD"] == "GET"){
      error_log($_GET['id']);
    DBfollow::getFollowUpByEnqId($_GET['id']);
}

     
?>