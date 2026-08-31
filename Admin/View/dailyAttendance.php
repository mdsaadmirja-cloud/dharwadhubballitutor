<?php
require "../session.php";

require_once "../DB Operations/BranchOps.php";
require_once "../DB Operations/AttendanceOps.php";
require_once "../model/Branchmodel.php";

include "header.php";

$selectedBranch = isset($_GET['branchid']) ? $_GET['branchid'] : "";
$selectedDate   = isset($_GET['attdate']) ? $_GET['attdate'] : date("Y-m-d");
$records = [];

if ($selectedBranch != "") {
    $records = AttendanceOps::getDailyAttendance($selectedBranch, $selectedDate);
}
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daily Attendance </h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">

        <form method="GET" class="row g-3 mb-4">

            <div class="col-md-5">
                <label class="form-label">Branch</label>

                <select name="branchid" class="form-select" required>
                    <option value="">-----SELECT BRANCH-----</option>
                    <?php
                    $branchlist = DBbranch::selectbranch();
                    foreach ($branchlist as $branch) {
                        $isSelected = ($selectedBranch == $branch->get_id()) ? "selected" : "";
                    ?>
                        <option value="<?php echo $branch->get_id(); ?>" <?php echo $isSelected; ?>>
                            <?php echo $branch->get_branchname(); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">Date</label>
                <input type="date" name="attdate" class="form-control"
                    value="<?php echo $selectedDate; ?>" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-warning w-100">View</button>
            </div>

        </form>

        <?php if ($selectedBranch != "" && count($records) > 0) { ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Late</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Hours Worked</th>
                        <th>Overtime (min)</th>
                        <th>Short (min)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $r) { ?>
                        <tr>
                            <td><?php echo $r['TrainerID']; ?></td>
                            <td><?php echo $r['TrainerName']; ?></td>
                            <td>
                                <?php
                                $status = $r['Status'];
                                if ($status == "Absent") {
                                    echo "<span class='text-danger'>Absent</span>";
                                } elseif ($status == "Incomplete") {
                                    echo "<span class='text-warning'>Incomplete</span>";
                                } else {
                                    echo $status;
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($r['Late'] == "Yes") {
                                    echo "<span class='text-danger'>Yes (" . $r['LateMinutes'] . " min)</span>";
                                } else {
                                    echo "No";
                                }
                                ?>
                            </td>
                            <td><?php echo $r['PunchIn'] ? date("H:i", strtotime($r['PunchIn'])) : "-"; ?></td>
                            <td><?php echo $r['PunchOut'] ? date("H:i", strtotime($r['PunchOut'])) : "-"; ?></td>
                            <td><?php echo $r['WorkingHours']; ?></td>
                            <td><?php echo $r['OvertimeMinutes']; ?></td>
                            <td><?php echo $r['ShortMinutes']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } elseif ($selectedBranch != "") { ?>
            <p>No attendance records found for this date.</p>
        <?php } ?>

    </div>
</div>

<?php require_once("footer.php"); ?>