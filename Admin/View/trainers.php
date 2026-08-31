<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/TrainerOps.php";
include "../../Admin/model/Trainermodel.php";
include "../../Admin/DB Operations/CoursesOps.php";
include "header.php";
?>

<style>
    .form-check-label {
        color: white;
    }

    #trainerslist_length {
        float: left;
        width: 50%;
        display: inline;
        margin-left: 100px;
    }
</style>
<div class="card">
    <div class="card-header">
        <h6 class="">Trainers</h6>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate_fingerprint'): ?>
            <div class="alert alert-danger">
                This Fingerprint ID is already assigned to another trainer in this branch. Please use a different one.
            </div>
        <?php endif; ?>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-trainers-tab" data-bs-toggle="pill" data-bs-target="#pills-trainers" type="button" role="tab" aria-controls="pills-trainers" aria-selected="true">Trainers list</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link " id="pills-newtrainer-tab" data-bs-toggle="pill" data-bs-target="#pills-newtrainer" type="button" role="tab" aria-controls="pills-newtrainer" aria-selected="false">New Trainer</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active"
                id="pills-trainers"
                role="tabpanel"
                aria-labelledby="pills-trainers-tab">
                <table class="table table-stripped" id="trainerslist">
                    <thead>
                        <tr>
                            <th style="display:none"> Id</th>
                            <th>Staff Code</th>
                            <th>Name</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Fingerprint ID</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $trainerslist = DBtrainer::searchtrainer();
                    foreach ($trainerslist as $trainer) {
                        echo "<tr>
<td style='display:none'>" . $trainer->get_id() . "</td>
<td>" . $trainer->get_staffcode() . "</td>
<td>" . $trainer->get_name() . "</td>
<td>" . $trainer->get_branchid() . "</td>
<td>" . $trainer->get_department() . "</td>
<td>" . $trainer->get_designation() . "</td>
<td>" . $trainer->get_fingerprintid() . "</td>
<td>" . $trainer->get_phone() . "</td>
<td>" . $trainer->get_status() . "</td>
<td>
<a class='btn btn-warning'
href='../View/viewtrainer.php?id=" . $trainer->get_id() . "&photofile=" . $trainer->get_photofile() . "'>
View
</a>
</td>
</tr>";
                    }
                    echo  "</tbody>";
                    ?>
                </table>
            </div>
            <div class="tab-pane fade"
                id="pills-newtrainer"
                role="tabpanel"
                aria-labelledby="pills-newtrainer-tab">
                <form class="form-horizontal" action="../Controller/newtrainer.php" method="POST" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="col-md-6 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" placeholder="Full Name" name="name" class="form-control" pattern="[a-zA-Z\-\ ]+" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="phone" class="col-md-6 control-label">Phone</label>
                            <div class="col-sm-12">
                                <input type="tel" id="phone" placeholder="Phone" name="phone" class="form-control" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="email" class="col-md-6 control-label">Email</label>
                            <div class="col-sm-12">
                                <input type="email" id="email" placeholder="Email" name="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="qualification" class="col-md-6 control-label">Qualification</label>
                            <div class="col-sm-12">
                                <input type="text" id="qualification" name="qualification" placeholder="Your Qualification" class="form-control" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="coursesassigned" class="col-md-6 control-label">
                                Course Assigned
                            </label>

                            <div class="col-md-12">
                                <select class="form-select" id="coursesassigned" name="coursesassigned" required>
                                    <option value="">-----SELECT COURSE-----</option>

                                    <?php
                                    $courselist = DBcourse::selectcourse();
                                    foreach ($courselist as $clist) {
                                        echo "<option value='" . $clist->get_id() . "'>" . $clist->get_cname() . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label class="col-md-6 control-label">Staff Code</label>
                            <div class="col-sm-12">
                                <input type="text"
                                    name="staffcode"
                                    class="form-control"
                                    readonly
                                    placeholder="Auto Generated">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-6 control-label">Branch</label>

                            <div class="col-sm-12">

                                <select name="branchid" class="form-select" required>

                                    <option value="">Select Branch</option>

                                    <option value="1">Hubli</option>

                                    <option value="2">Dharwad</option>

                                    <option value="3">Belagavi</option>

                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">

                            <label class="col-md-6 control-label">

                                Department

                            </label>

                            <div class="col-sm-12">

                                <select name="department" class="form-select" required>

                                    <option value="">Select</option>

                                    <option>Training</option>

                                    <option>Administration</option>

                                    <option>Accounts</option>

                                    <option>Reception</option>

                                    <option>HR</option>

                                    <option>Marketing</option>

                                    <option>Management</option>

                                </select>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <label class="col-md-6 control-label">

                                Designation

                            </label>

                            <div class="col-sm-12">

                                <select name="designation" class="form-select" required>

                                    <option value="">Select</option>

                                    <option>Trainer</option>

                                    <option>Senior Trainer</option>

                                    <option>Receptionist</option>

                                    <option>Accountant</option>

                                    <option>HR</option>

                                    <option>Manager</option>

                                    <option>Administrator</option>

                                </select>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <label class="col-md-6 control-label">

                                Fingerprint ID

                            </label>

                            <div class="col-sm-12">

                                <input
                                    type="text"
                                    name="fingerprintid"
                                    class="form-control"
                                    required>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <label class="col-md-6 control-label">

                                Joining Date

                            </label>

                            <div class="col-sm-12">

                                <input
                                    type="date"
                                    name="joiningdate"
                                    class="form-control"
                                    required>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <label class="col-md-6 control-label">

                                Status

                            </label>

                            <div class="col-sm-12">

                                <select name="status" class="form-select">

                                    <option value="Active">

                                        Active

                                    </option>

                                    <option value="Inactive">

                                        Inactive

                                    </option>

                                </select>

                            </div>

                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Working Hours <span style="color:red">*</span></label>

                            <select name="workinghours" class="form-select" required>
                                <option value="">-----SELECT WORKING HOURS-----</option>
                                <option value="6">6 Hours</option>
                                <option value="7">7 Hours</option>
                                <option value="8">8 Hours</option>
                                <option value="9" selected>9 Hours</option>
                                <option value="10">10 Hours</option>
                                <option value="11">11 Hours</option>
                                <option value="12">12 Hours</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Shift <span style="color:red">*</span></label>

                            <select name="shiftid" class="form-select" required>
                                <option value="">-----SELECT SHIFT-----</option>
                                <?php
                                $shiftslist = DBtrainer::getShiftsList();
                                foreach ($shiftslist as $shift) {
                                    echo "<option value='" . $shift['id'] . "'>" . $shift['ShiftName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="address" class="col-md-6 control-label">Address</label>
                            <div class="col-sm-12">
                                <textarea id="address"
                                    name="address"
                                    placeholder="Residential Address"
                                    class="form-control" required></textarea>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="adhaarno" class="col-md-6 control-label">Adhaar Number</label>
                            <div class="col-sm-12">
                                <input type="text" id="adhaarno" name="adhaarno" placeholder="Your Adhaar Number" class="form-control" pattern="[0-9]{4}[0-9]{4}[0-9]{4}" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="adhaarfile" class=" col-md-6 form-label">Upload Adhaar</label>
                            <div class="col-sm-12">
                                <input type="file" name="adhaarfile" id="adhaarfile" class="form-control">
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="photofile" class=" col-md-6 form-label">Upload Photo</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="file" name="photofile" id="photofile" required>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="resume" class=" col-md-6 form-label">Upload Resume</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="file" name="resume" id="resume" required>
                            </div>
                            <hr class="my-4">
                            <h5>Bank Details</h5>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Account Holder Name</label>
                                    <input type="text" name="account_holder_name" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Account Number</label>
                                    <input type="text" name="account_number" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Account Type</label>
                                    <select name="account_type" class="form-select">
                                        <option value="">Select</option>
                                        <option>Savings</option>
                                        <option>Current</option>
                                        <option>Salary</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label>Bank Address</label>
                                    <textarea name="bank_address" class="form-control"></textarea>
                                </div>

                            </div>
                        </div>
                        <br />

                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-warning">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once("footer.php"); ?>
<script>
    var trainerslist = $('#trainerslist').DataTable();
    $(document).ready(function() {
        $("[type=search]").addClass("form-control").attr("placeholder", "Type to search...").attr("style",
            "margin-left:50px");
        $("select").addClass("form-select").attr("aria-label", "Default select example");
    });
</script>
</body>

</html>