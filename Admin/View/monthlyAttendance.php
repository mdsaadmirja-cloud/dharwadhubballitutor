<?php
require "../session.php";

require_once "../DB Operations/BranchOps.php";
require_once "../DB Operations/AttendanceOps.php";
require_once "../model/Branchmodel.php";

include "header.php";

$selectedBranch = isset($_GET['branchid']) ? $_GET['branchid'] : "";
$selectedYear   = isset($_GET['year']) ? $_GET['year'] : date("Y");
$selectedMonth  = isset($_GET['month']) ? $_GET['month'] : date("m");
$records = [];

if ($selectedBranch != "") {
    $records = AttendanceOps::getMonthlyAttendance($selectedBranch, $selectedYear, $selectedMonth);
}
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"> Monthly Attendance </h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">

        <form method="GET" class="row g-3 mb-4">

            <div class="col-md-4">
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

            <div class="col-md-3">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control"
                    value="<?php echo $selectedYear; ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Month</label>
                <select name="month" class="form-select" required>
                    <?php for ($m = 1; $m <= 12; $m++) {
                        $selected = ($selectedMonth == $m) ? "selected" : "";
                    ?>
                        <option value="<?php echo $m; ?>" <?php echo $selected; ?>>
                            <?php echo date("F", mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php } ?>
                </select>
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
                        <th>Present</th>
                        <th>Half Day</th>
                        <th>Absent</th>
                        <th>Incomplete</th>
                        <th>Late Days</th>
                        <th>Total Hours</th>
                        <th>Total OT (min)</th>
                        <th>Total Late (min)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $r) { ?>
                        <tr>
                            <td><?php echo $r['TrainerID']; ?></td>
                            <td><?php echo $r['TrainerName']; ?></td>
                            <td><?php echo $r['PresentDays']; ?></td>
                            <td><?php echo $r['HalfDays']; ?></td>
                            <td><?php echo $r['AbsentDays']; ?></td>
                            <td><?php echo $r['IncompleteDays']; ?></td>
                            <td><?php echo $r['LateDays']; ?></td>
                            <td><?php echo round($r['TotalHours'], 2); ?></td>
                            <td><?php echo $r['TotalOvertimeMinutes']; ?></td>
                            <td><?php echo $r['TotalLateMinutes']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } elseif ($selectedBranch != "") { ?>
            <p>No attendance records found for this month.</p>
        <?php } ?>

    </div>
</div>

<?php require_once("footer.php"); ?>