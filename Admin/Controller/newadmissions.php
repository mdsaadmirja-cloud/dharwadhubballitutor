<?php
require_once "../session.php";

require_once "../model/Admissionsmodel.php";
require_once "../Utilities/Sanitization.php";
require_once "../Utilities/Helper.php";
//require "../Admin/navbar.php";
require_once "../DB Operations/AdmissionsOps.php";
require_once "../DB Operations/CoursesOps.php";
// require_once "../../Admin/model/smsModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $admit = new Admissions();
  if (!empty($_POST["id"])) {
    $admit->set_enqueryId(Sanitization::test_input($_POST["id"]));
  } else {
    $admit->set_enqueryId(0);
  }

  $admit->set_name(Sanitization::test_input($_POST["name"]));
  $admit->set_phone(Sanitization::test_input($_POST["phone"]));
  $admit->set_email(Sanitization::test_input($_POST["email"]));
  $admit->set_dateofbirth(Sanitization::test_input($_POST["dateofbirth"]));
  $admit->set_gender(Sanitization::test_input($_POST["gender"]));
  $admit->set_qualification(Sanitization::test_input($_POST["qualification"]));
  $admit->set_guardiansname(Sanitization::test_input($_POST["guardiansname"]));
  $admit->set_guardiansphone(Sanitization::test_input($_POST["guardiansphone"]));
  $cname = DBcourse::getcoursename($_POST["coursesopted"]);
  $admit->set_coursesopted($cname);
  $admit->set_courseid(Sanitization::test_input($_POST["coursesopted"]));
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
  $admit->set_branch(Sanitization::test_input($_POST["branch"]));
  DBadmission::insert($admit);
}
?>
<html>

<head>
  <title> New Admission </title>
</head>

<body>
  <?php
  header("location:../View/admissions.php");
  ?>


</body>

</html>