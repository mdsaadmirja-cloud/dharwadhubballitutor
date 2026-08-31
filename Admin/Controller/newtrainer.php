<?php
require "../../Admin/model/Trainermodel.php";
require "../../Utilities/Sanitization.php";
require "../../Admin/Utilities/Helper.php";
require "../../Admin/DB Operations/TrainerOps.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/DB Operations/dbconnection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $admit = new Trainer();
  $admit->set_name(Sanitization::test_input($_POST["name"]));
  $admit->set_phone(Sanitization::test_input($_POST["phone"]));
  $admit->set_email(Sanitization::test_input($_POST["email"]));
  $admit->set_qualification(Sanitization::test_input($_POST["qualification"]));
  $admit->set_coursesassigned(array($_POST["coursesassigned"]));
  $admit->set_branchid($_POST["branchid"]);
  $admit->set_department($_POST["department"]);
  $admit->set_designation($_POST["designation"]);
  $admit->set_fingerprintid($_POST["fingerprintid"]);
  $admit->set_joiningdate($_POST["joiningdate"]);
  $admit->set_status($_POST["status"]);
  $admit->set_workinghours($_POST["workinghours"]);
  $admit->set_shiftid($_POST["shiftid"]);
  $admit->set_address(Sanitization::test_input($_POST["address"]));
  $admit->set_adhaarno(Sanitization::test_input($_POST["adhaarno"]));
  $filetoupload = $_FILES["adhaarfile"];
  Helper::fileupload($filetoupload);
  $filetoupload = $_FILES["photofile"];
  Helper::fileupload($filetoupload);
  $filetoupload = $_FILES["resume"];
  Helper::fileupload($filetoupload);
  $admit->set_adhaarfile($_FILES["adhaarfile"]['name']);
  $admit->set_photofile($_FILES["photofile"]['name']);
  $admit->set_resume($_FILES["resume"]['name']);
  $admit->set_bank_name($_POST["bank_name"]);
  $admit->set_account_holder_name($_POST["account_holder_name"]);
  $admit->set_account_number($_POST["account_number"]);
  $admit->set_account_type($_POST["account_type"]);
  $admit->set_ifsc_code($_POST["ifsc_code"]);
  $admit->set_branch_name($_POST["branch_name"]);
  $admit->set_bank_address($_POST["bank_address"]);
  $db = ConnectDb::getInstance();
  $connectionObj = $db->getConnection();

  $result = $connectionObj->query("SELECT IFNULL(MAX(id),0)+1 AS nextid FROM trainers");
  $row = $result->fetch_assoc();

  $staffCode = "STF" . str_pad($row['nextid'], 3, "0", STR_PAD_LEFT);

  $admit->set_staffcode($staffCode);
  $last_id = DBtrainer::insert($admit);

  if ($last_id === "DUPLICATE") {
    header("location:../View/trainers.php?error=duplicate_fingerprint");
    exit;
  }

  $db = ConnectDb::getInstance();
  $connectionObj = $db->getConnection();

  foreach ($admit->get_coursesassigned() as $courseId) {
    $sql = "INSERT INTO trainercoursemapping (trainerid, courseid)
          VALUES ('$last_id', '$courseId')";
    $connectionObj->query($sql);
  }
}
header("location:../View/trainers.php");
