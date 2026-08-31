<?php
require "../session.php";

require_once "../DB Operations/BranchOps.php";
require_once "../model/Branchmodel.php";

include "header.php";
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Attendance Upload</h6>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card-body">

        <form action="../Controller/attendanceUpload.php"
            method="POST"
            enctype="multipart/form-data">

            <div class="row g-3">

                <!-- Branch -->

                <div class="col-md-6">
                    <label class="form-label">Branch</label>

                    <select name="branchid" class="form-select" required>

                        <option value="">-----SELECT BRANCH-----</option>

                        <?php

                        $branchlist = DBbranch::selectbranch();

                        foreach ($branchlist as $branch) {
                        ?>

                            <option value="<?php echo $branch->get_id(); ?>">

                                <?php echo $branch->get_branchname(); ?>

                            </option>

                        <?php
                        }

                        ?>

                    </select>
                </div>


                <!-- Report Type -->

                <div class="col-md-6">
                    <label class="form-label">Report Type</label>

                    <select name="reporttype"
                        class="form-select">

                        <option value="auto">Auto Detect</option>
                        <option value="attendance">Attendance Record</option>
                        <option value="statistical">Statistical Report</option>
                        <option value="exception">Exception Report</option>
                        <option value="daily">Daily Punch Report</option>

                    </select>
                </div>


                <!-- Excel Upload -->

                <div class="col-md-12">
                    <label class="form-label">
                        Select Attendance Excel File
                    </label>

                    <input
                        type="file"
                        name="attendancefile"
                        accept=".xls,.xlsx"
                        class="form-control"
                        required>
                </div>


                <!-- Options -->

                <div class="col-md-12">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="skipduplicate"
                            value="1"
                            checked>

                        <label class="form-check-label">

                            Skip Duplicate Attendance

                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="autodetect"
                            value="1"
                            checked>

                        <label class="form-check-label">

                            Auto Detect Report Format

                        </label>

                    </div>


                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="calculatehours"
                            value="1"
                            checked>

                        <label class="form-check-label">

                            Calculate Working Hours Automatically

                        </label>

                    </div>

                </div>


                <!-- Upload Button -->

                <div class="col-md-12">

                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i class="fa fa-upload"></i>

                        Upload Attendance

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<?php require_once("footer.php"); ?>