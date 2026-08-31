<?php
require_once "../session.php";
require "../Utilities/Sanitization.php";
require "../../Admin/model/placementmodel.php";
include "../../Admin/DB Operations/placementOps.php";
require_once "../Utilities/Helper.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['editid'])) {
        $placement = new Placement();
        $placement->setId(Sanitization::test_input($_POST["editid"]));
        $placement->setAdmissionid(Sanitization::test_input($_POST["Admissionid"]));
        $placement->setAssistedon(Sanitization::test_input($_POST["Assistedon"]));
        $placement->setCompanyname(Sanitization::test_input($_POST["CompanyName"]));
        $placement->setJobpost(Sanitization::test_input($_POST["Jobpost"]));
        $placement->setResult(Sanitization::test_input($_POST["Result"]));
        $placement->setReason(Sanitization::test_input($_POST["Reason"]));
        $placement->setRejectedby(Sanitization::test_input($_POST["Rejectedby"]));

        $filetoupload = $_FILES["offerletter"];
        Helper::fileupload($filetoupload);
        $placement->setofferletter($_FILES["offerletter"]['name']);

        DBPlacement::update($placement);
    } else if ($_POST["action"] == 'delete') {
        DBPlacement::delete($_POST["id"]);
    } else {
        $placement = new Placement();
        $placement->setAdmissionid(Sanitization::test_input($_POST["Admissionid"]));
        $placement->setAssistedon(Sanitization::test_input($_POST["Assistedon"]));
        $placement->setCompanyname(Sanitization::test_input($_POST["Companyname"]));
        $placement->setJobpost(Sanitization::test_input($_POST["jobpost"]));
        $placement->setResult(Sanitization::test_input($_POST["Result"]));
        $placement->setReason(Sanitization::test_input($_POST["Reason"]));
        $placement->setRejectedby(Sanitization::test_input($_POST["Rejectedby"]));

        $filetoupload = $_FILES["offerletter"];
        Helper::fileupload($filetoupload);
        $placement->setofferletter($_FILES["offerletter"]['name']);

        DBPlacement::insert($placement);
    }
}
?>
<html>

<head>
    <title></title>
</head>

<body>
    <?php
    header("location:../View/viewprofile.php?id=" . $_POST["Admissionid"]);
    ?>


</body>

</html>