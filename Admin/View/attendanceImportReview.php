<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "../../Admin/session.php";

include "header.php";

$result   = isset($_SESSION['attendance_import_result']) ? $_SESSION['attendance_import_result'] : null;
$branchID = isset($_SESSION['attendance_import_branch']) ? $_SESSION['attendance_import_branch'] : "";

if ($result === null) {
    echo "<p>No import data found. Please upload a file first.</p>";
    require_once("footer.php");
    exit;
}

$saved   = $result['saved'];
$skipped = $result['skipped'];
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Attendance Import Review</h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
    <div class="card-body">

        <h6 class="text-success">Stored (<?php echo count($saved); ?>)</h6>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Worked Min</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($saved as $r) { ?>
                    <tr>
                        <td><?php echo $r['fingerprintID']; ?></td>
                        <td><?php echo $r['employeeName']; ?></td>
                        <td><?php echo $r['attendanceDate']; ?></td>
                        <td><?php echo $r['status']; ?></td>
                        <td><?php echo $r['workedMinutes']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (count($saved) == 0) { ?>
                    <tr>
                        <td colspan="5">None</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h6 class="text-danger mt-4">Skipped (<?php echo count($skipped); ?>)</h6>

        <form action="../Controller/attendanceImportConfirm.php" method="POST">

            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="document.querySelectorAll('.skip-check').forEach(cb => cb.checked = this.checked)"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skipped as $index => $r) { ?>
                        <tr>
                            <td>
                                <?php if ($r['reason'] == 'Already Exists') { ?>
                                    <input type="checkbox" class="skip-check" name="selected[]" value="<?php echo $index; ?>">
                                <?php } else { ?>
                                    <span class="text-muted">N/A</span>
                                <?php } ?>
                            </td>
                            <td><?php echo $r['fingerprintID']; ?></td>
                            <td><?php echo $r['employeeName']; ?></td>
                            <td><?php echo $r['attendanceDate']; ?></td>
                            <td>
                                <?php if ($r['reason'] == 'Trainer Not Found') { ?>
                                    <span class="text-danger">Trainer Not Found</span>
                                <?php } else { ?>
                                    <span class="text-warning">Already Exists</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (count($skipped) == 0) { ?>
                        <tr>
                            <td colspan="5">None</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php if (count($skipped) > 0) { ?>
                <button type="submit" class="btn btn-warning">Save Selected & Continue</button>
            <?php } else { ?>
                <a href="attendanceReport.php?branchid=<?php echo $branchID; ?>" class="btn btn-warning">Continue to Report</a>
            <?php } ?>

        </form>

    </div>
</div>

<?php require_once("footer.php"); ?>