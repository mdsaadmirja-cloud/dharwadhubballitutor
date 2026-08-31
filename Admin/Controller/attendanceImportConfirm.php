<?php
require "../../Admin/session.php";

require_once dirname(__DIR__) . "/Utilities/AttendanceImporter.php";
require_once dirname(__DIR__) . "/DB Operations/AttendanceOps.php";
require_once dirname(__DIR__) . "/model/AttendanceModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $result   = isset($_SESSION['attendance_import_result']) ? $_SESSION['attendance_import_result'] : null;
    $branchID = isset($_SESSION['attendance_import_branch']) ? $_SESSION['attendance_import_branch'] : "";

    if ($result !== null) {

        $skipped = $result['skipped'];
        $selectedIndexes = isset($_POST['selected']) ? $_POST['selected'] : [];

        foreach ($selectedIndexes as $index) {
            if (isset($skipped[$index])) {
                AttendanceImporter::forceSaveSkipped($skipped[$index]);
            }
        }
    }

    unset($_SESSION['attendance_import_result']);
    unset($_SESSION['attendance_import_branch']);

    header("Location: ../View/attendanceReport.php?branchid=" . $branchID);
    exit;
}