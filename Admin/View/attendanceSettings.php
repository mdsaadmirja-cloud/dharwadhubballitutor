<?php
require "../session.php";

require_once "../DB Operations/AttendanceOps.php";
require_once "../DB Operations/BranchOps.php";
require_once "../DB Operations/TrainerOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    AttendanceOps::saveSettingsProfile($_POST);
    header("Location: attendanceSettings.php");
    exit;
}

if (isset($_GET['delete'])) {
    AttendanceOps::deleteSettingsProfile($_GET['delete']);
    header("Location: attendanceSettings.php");
    exit;
}

$branchList  = DBbranch::selectbranch();
$shiftList   = AttendanceOps::getShifts();
$trainerList = DBtrainer::getAllTrainers();
$profiles    = AttendanceOps::getAllSettingsProfiles();

include "header.php";
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Add / Update Attendance Settings Profile</h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Branch (leave blank = applies to all)</label>
                    <select name="branchid" id="branchid" class="form-select">
                        <option value="">-- All Branches --</option>
                        <?php foreach ($branchList as $b) { ?>
                            <option value="<?php echo $b->get_id(); ?>">
                                <?php echo $b->get_branchname(); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Shift (leave blank = applies to all shifts)</label>
                    <select name="shiftid" class="form-select">
                        <option value="">-- All Shifts --</option>
                        <?php foreach ($shiftList as $s) { ?>
                            <option value="<?php echo $s['id']; ?>">
                                <?php echo $s['ShiftName']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Trainer (leave blank = not trainer-specific)</label>
                    <select name="trainerid" id="trainerid" class="form-select">
                        <option value="">-- Not Trainer Specific --</option>
                        <?php foreach ($trainerList as $t) { ?>
                            <option value="<?php echo $t['id']; ?>" data-branch="<?php echo $t['BranchId']; ?>">
                                <?php echo $t['Name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Office Start Time</label>
                    <input type="time" step="1" name="officestart" class="form-control" value="08:00:00" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Office End Time</label>
                    <input type="time" step="1" name="officeend" class="form-control" value="20:00:00" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Grace Minutes</label>
                    <input type="number" name="graceminutes" class="form-control" value="15" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Full Day Hours</label>
                    <input type="number" step="0.01" name="fulldayhours" class="form-control" value="9.00" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Half Day Hours</label>
                    <input type="number" step="0.01" name="halfdayhours" class="form-control" value="5.00" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Mid Day Hours</label>
                    <input type="number" step="0.01" name="middayhours" class="form-control" value="3.00" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save"></i> Save Profile
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6>Existing Settings Profiles</h6>
    </div>
    <div class="card-body">

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch</th>
                    <th>Shift</th>
                    <th>Trainer</th>
                    <th>Office Start</th>
                    <th>Office End</th>
                    <th>Grace</th>
                    <th>Full</th>
                    <th>Half</th>
                    <th>Mid</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profiles as $p) { ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['BranchName'] ?? '<span class="text-muted">All</span>'; ?></td>
                        <td><?php echo $p['ShiftName'] ?? '<span class="text-muted">All</span>'; ?></td>
                        <td><?php echo $p['TrainerName'] ?? '<span class="text-muted">-</span>'; ?></td>
                        <td><?php echo $p['OfficeStart']; ?></td>
                        <td><?php echo $p['OfficeEnd']; ?></td>
                        <td><?php echo $p['GraceMinutes']; ?></td>
                        <td><?php echo $p['FullDayHours']; ?></td>
                        <td><?php echo $p['HalfDayHours']; ?></td>
                        <td><?php echo $p['MidDayHours']; ?></td>
                        <td><?php echo $p['Status']; ?></td>
                        <td>
                            <?php if ($p['id'] != 1) { ?>
                                <a href="?delete=<?php echo $p['id']; ?>" class="text-danger" onclick="return confirm('Delete this profile?')">Delete</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>
<script>
    document.getElementById('branchid').addEventListener('change', function() {
        const selectedBranch = this.value;
        const trainerSelect = document.getElementById('trainerid');
        const options = trainerSelect.querySelectorAll('option[data-branch]');

        trainerSelect.value = ""; // reset trainer selection whenever branch changes

        options.forEach(function(opt) {
            if (selectedBranch === "" || opt.getAttribute('data-branch') === selectedBranch) {
                opt.style.display = "";
            } else {
                opt.style.display = "none";
            }
        });
    });
</script>
<?php require_once("footer.php"); ?>