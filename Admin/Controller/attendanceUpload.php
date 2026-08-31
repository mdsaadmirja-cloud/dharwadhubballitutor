<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();

require "../../Admin/session.php";

require_once dirname(__DIR__) . "/Utilities/AttendanceImporter.php";
require_once dirname(__DIR__) . "/DB Operations/AttendanceOps.php";
require_once dirname(__DIR__) . "/model/AttendanceModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $branchID   = $_POST['branchid'];
    $reportType = $_POST['reporttype'];

    $tmpFile  = $_FILES['attendancefile']['tmp_name'];
    $fileName = $_FILES['attendancefile']['name'];

    if (!empty($tmpFile)) {

        $result = AttendanceImporter::importAttendance(
            $tmpFile,
            $branchID,
            $reportType,
            $fileName
        );

        ob_end_clean();

        $_SESSION['attendance_import_result'] = $result;
        $_SESSION['attendance_import_branch']  = $branchID;

        header("Location: ../View/attendanceImportReview.php");
        exit;
    }

    echo "No file uploaded.";
}
if (
    isset($_GET['action']) &&
    $_GET['action'] == "deleteAttendance"
) {

    $branchID = $_GET['branchid'];
    $month    = $_GET['month'];
    $year     = $_GET['year'];

    AttendanceOps::deleteAttendanceByMonth(
        $branchID,
        $month,
        $year
    );

    header(
        "Location: ../View/attendanceReport.php?deleted=1"
    );

    exit;
}
