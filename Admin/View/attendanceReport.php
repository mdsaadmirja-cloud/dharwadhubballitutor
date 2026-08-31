<?php
require "../session.php";

require_once "../DB Operations/BranchOps.php";
require_once "../DB Operations/AttendanceOps.php";
require_once "../model/Branchmodel.php";

include "header.php";

$selectedBranch = isset($_GET['branchid']) ? $_GET['branchid'] : "";
$selectedMonth  = isset($_GET['month']) ? $_GET['month'] : "";
$selectedYear   = isset($_GET['year']) ? $_GET['year'] : "";

$records = [];

if (
    $selectedBranch != "" &&
    $selectedMonth != "" &&
    $selectedYear != ""
) {

    $records = AttendanceOps::getAttendanceByBranchMonth(
        $selectedBranch,
        $selectedMonth,
        $selectedYear
    );
}
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Attendance Report - Branch Wise</h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">

        <form method="GET" class="row">

            <div class="col-md-4">
                <label>Branch</label>

                <select name="branchid"
                    class="form-control"
                    required>

                    <option value="">-----SELECT BRANCH-----</option>

                    <?php
                    $branchlist = DBbranch::selectbranch();

                    foreach ($branchlist as $branch) {

                        $selected = "";

                        if (
                            isset($_GET['branchid']) &&
                            $_GET['branchid'] == $branch->get_id()
                        ) {
                            $selected = "selected";
                        }
                    ?>

                        <option
                            value="<?php echo $branch->get_id(); ?>"
                            <?php echo $selected; ?>>

                            <?php echo $branch->get_branchname(); ?>

                        </option>

                    <?php } ?>

                </select>
            </div>


            <div class="col-md-3">

                <label>Month</label>

                <select
                    name="month"
                    class="form-control"
                    required>

                    <?php

                    for ($m = 1; $m <= 12; $m++) {

                        $selected = "";

                        if (
                            isset($_GET['month']) &&
                            $_GET['month'] == $m
                        ) {
                            $selected = "selected";
                        }

                    ?>

                        <option
                            value="<?php echo $m; ?>"
                            <?php echo $selected; ?>>

                            <?php echo date("F", mktime(0, 0, 0, $m, 1)); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <div class="col-md-3">

                <label>Year</label>

                <select
                    name="year"
                    class="form-control"
                    required>

                    <?php

                    for ($y = date("Y"); $y >= 2024; $y--) {

                        $selected = "";

                        if (
                            isset($_GET['year']) &&
                            $_GET['year'] == $y
                        ) {
                            $selected = "selected";
                        }

                    ?>

                        <option
                            value="<?php echo $y; ?>"
                            <?php echo $selected; ?>>

                            <?php echo $y; ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <div class="col-md-2">

                <label>&nbsp;</label>

                <button
                    type="submit"
                    class="btn btn-primary btn-block">

                    View Report

                </button>

                <?php if ($selectedBranch != "" && $selectedMonth != "" && $selectedYear != "") { ?>

                    <button
                        type="button"
                        class="btn btn-danger btn-block mt-2"
                        id="deleteAttendance"
                        data-branch="<?php echo $selectedBranch; ?>"
                        data-month="<?php echo $selectedMonth; ?>"
                        data-year="<?php echo $selectedYear; ?>">

                        Delete Attendance

                    </button>

                <?php } ?>

            </div>

        </form>

        <?php if ($selectedBranch != "" && count($records) > 0) { ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Late</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Total Hours Worked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $r) { ?>
                        <tr>
                            <td><?php echo $r['TrainerID']; ?></td>
                            <td><?php echo $r['TrainerName']; ?></td>
                            <td><?php echo $r['AttendanceDate']; ?></td>
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
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } elseif ($selectedBranch != "") { ?>
            <p>No attendance records found for this branch.</p>
        <?php } ?>

    </div>
</div>

<script>
    const deleteBtn = document.getElementById("deleteAttendance");

    if (deleteBtn) {

        deleteBtn.addEventListener("click", function() {

            if (!confirm("Delete attendance for the selected Branch, Month and Year?")) {
                return;
            }

            const branch = this.dataset.branch;
            const month = this.dataset.month;
            const year = this.dataset.year;

            window.location =
                "../Controller/attendanceUpload.php?action=deleteAttendance" +
                "&branchid=" + encodeURIComponent(branch) +
                "&month=" + encodeURIComponent(month) +
                "&year=" + encodeURIComponent(year);

        });

    }
</script>
<?php require_once("footer.php"); ?>