<?php
// require "session.php";
require "../../Admin/model/Admissionsmodel.php";
require "../../Utilities/Sanitization.php";
require "../../Admin/Utilities/Helper.php";
//require "../Admin/navbar.php";
require_once "../DB Operations/CoursesOps.php";
require "../../Admin/DB Operations/AdmissionsOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $admit = new Admissions();
  $admit->set_id(Sanitization::test_input($_POST["id"]));
  $admit->set_name(Sanitization::test_input($_POST["name"]));
  $admit->set_phone(Sanitization::test_input($_POST["phone"]));
  $admit->set_email(Sanitization::test_input($_POST["email"]));
  $admit->set_dateofbirth(Sanitization::test_input($_POST["dateofbirth"]));
  $admit->set_gender(Sanitization::test_input($_POST["gender"]));
  $admit->set_qualification(Sanitization::test_input($_POST["qualification"]));
  $admit->set_branch(Sanitization::test_input($_POST["branch"]));
  $admit->set_guardiansname(Sanitization::test_input($_POST["guardiansname"]));
  $admit->set_guardiansphone(Sanitization::test_input($_POST["guardiansphone"]));
  $cname = DBcourse::getcoursename($_POST["coursesopted"]);
  $admit->set_coursesopted($cname);
  $admit->set_courseid($_POST["coursesopted"]);
  $admit->set_address(Sanitization::test_input($_POST["address"]));
  $admit->set_adhaarno(Sanitization::test_input($_POST["adhaarno"]));
  $admit->set_CancellationReason($_POST['cancellationReason']);
  $admit->set_Admissionstatus($_POST['admissionstatus']);
  if ($_FILES['adhaarfile']['size'] != 0 && $_FILES['adhaarfile']['error'] == 0) {
    $filetoupload = $_FILES["adhaarfile"];

    if (Helper::fileupload($filetoupload)) {
      $admit->set_adhaarfile($_FILES["adhaarfile"]['name']);
    }
  }

  if ($_FILES['resume']['size'] != 0 && $_FILES['resume']['error'] == 0) {
    $filetoupload = $_FILES["resume"];

    if (Helper::fileupload($filetoupload)) {
      $admit->set_resume($_FILES["resume"]['name']);
    }
  }
  if ($_FILES['photofile']['size'] != 0 && $_FILES['photofile']['error'] == 0) {
    $filetoupload = $_FILES["photofile"];

    if (Helper::fileupload($filetoupload)) {
      $admit->set_photofile($_FILES["photofile"]['name']);
    }
  }
}
?>
<html>

<head>
  <title> Updated Information </title>
  <style>
    .h2 {

      text-align: center;
      color: #2a0a5e;
      margin-top: 3rem;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-10">
        <div class="container">
          <h2 class="h2">
            <?php
            $status = DBadmission::updateadmission($admit);
            if ($status === TRUE) {
              header("location:../View/admissions.php");
            } else {
              echo "error in updating record";
            }
            ?>
          </h2>
</body>

</html>