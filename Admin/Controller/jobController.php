<?php
require "../Utilities/Sanitization.php";
require "../../Admin/model/jobModel.php";
include "../../Admin/DB Operations/jobOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['editid'])) {
        $job = new Job();
        $job->setId(Sanitization::test_input($_POST["editid"]));
        $job->setJobid(Sanitization::test_input($_POST["jobid"]));
        $job->setName(Sanitization::test_input($_POST["Name"]));
        $job->setDescription(Sanitization::test_input($_POST["Description"]));
        $job->setJobLocation(Sanitization::test_input($_POST["JobLocation"]));
        $job->setRemoteWork(Sanitization::test_input($_POST["RemoteWork"]));

        DBJob::update($job);
    } else if ($_POST["action"] == 'delete') {
        DBJob::delete($_POST["id"]);
    } else {
        $job = new Job();
        $job->setJobid(Sanitization::test_input($_POST["jobid"]));
        $job->setName(Sanitization::test_input($_POST["Name"]));
        $job->setDescription(Sanitization::test_input($_POST["Description"]));
        $job->setJobLocation(Sanitization::test_input($_POST["JobLocation"]));
        $job->setRemoteWork(Sanitization::test_input($_POST["RemoteWork"]));
        DBJob::insert($job);
    }
}
?>
<html>

<head>
    <title> New course </title>
</head>

<body>
    <?php
    header("location:../View/job.php?id=" . $_POST["jobid"]);
    ?>
</body>

</html>