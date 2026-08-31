<?php
require "../../Admin/session.php";
require "../../Admin/Utilities/Helper.php";
include "../../Admin/DB Operations/AdmissionsOps.php";
require "../../Admin/model/Admissionsmodel.php";
require "../../Model/Registration.php";
include "../../Admin/DB Operations/CoursesOps.php";
require "header.php";
$id = $_GET["id"];
$name1 = $_GET['name'];
$email1 = $_GET['email'];
$phone1 = $_GET['phone'];
$qualification1 = $_GET['qualification'];
$admission = DBadmission::viewadmission($id);
?>

<div class="card">
    <div class="card-header">
        <h6 class="">Admission Form</h6>
    </div>
    <div class="card-body">
        <form action="../Controller/newadmissions.php" method="POST" role="form" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="col-md-6 control-label">Full Name</label>
                    <div class="col-sm-12">
                        <input type="text" id="name" placeholder="Full Name" name="name" class="form-control" pattern="[a-zA-Z\-\ ]+" value="<?php
                                                                                                                                                echo "$name1";
                                                                                                                                                ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="phone" class="col-md-6 control-label">Phone</label>
                    <div class="col-sm-12">
                        <input type="tel" id="phone" placeholder="Phone" name="phone" class="form-control" value="<?php
                                                                                                                    echo "$phone1";
                                                                                                                    ?>">
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="email" class="col-md-6 control-label">Email</label>
                    <div class="col-sm-12">
                        <input type="email" id="email" placeholder="Email" name="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?php
                                                                                                                                                                        echo "$email1";
                                                                                                                                                                        ?>">
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="dateofbirth" class="col-md-6 control-label">Date of Birth</label>
                    <div class="col-sm-12">
                        <input type="date" id="dateofbirth" name="dateofbirth" class="form-control" required>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="gender" class="col-md-6 control-label">Gender</label>
                    <div class="col-md-12">
                        <div class="col-md-4 form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="gender" value="Female">
                            <label class="form-check-label" for="inlineRadio1">Female</label>
                        </div>
                        <div class="col-md-4 form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="gender" value="Male">
                            <label class="form-check-label" for="gender">Male</label>
                        </div>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="qualification" class="col-md-6 control-label">Qualification</label>
                    <div class="col-sm-12">
                        <input type="text" id="qualification"
                            name="qualification"
                            placeholder="Your Qualification"
                            class="form-control" required
                            value="<?php
                                    echo $qualification1;
                                    ?>">
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="branch" class="col-md-6 control-label">Branch</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="branch" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="Belagavi">Belagavi</option>
                            <option value="Dharwad">Dharwad</option>
                            <option value="Hubballi">Hubballi</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="guardiansname" class="col-md-6 control-label">Guardians Name</label>
                    <div class="col-sm-12">
                        <input type="text" id="guardiansname" name="guardiansname" placeholder="Guardians Name" class="form-control" pattern="[a-zA-Z\-\ ]+" required>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="guardiansphone" class="col-md-8 control-label">Guardians Phone
                        Number</label>
                    <div class="col-sm-12">
                        <input type="text" id="guardiansphone" name="guardiansphone" placeholder="Guardians Phone Number" class="form-control" required>
                    </div>
                </div>
                <br />


                <div class="col-md-6">
                    <label for="coursesopted" class="col-md-6 control-label">Courses
                        Opted</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="coursesopted" name="coursesopted" required>
                            <option value="">Select Your Interest</option>
                            <?php
                            $option = "";
                            $courselist = DBcourse::selectall();
                            foreach ($courselist as $course) {
                                $option .= "<option value='" . $course->get_id() . "'";
                                $option .=  ">" . $course->get_cname() . "</option>";
                            }
                            echo $option;

                            ?>
                        </select>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="address" class="col-md-6 control-label">Address</label>
                    <div class="col-sm-12">
                        <textarea id="address"
                            name="address"
                            placeholder="Residential Address"
                            class="form-control"
                            required></textarea>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="adhaarno" class="col-md-12 control-label">Adhaar Number</label>
                    <div class="col-sm-12">
                        <input type="text" id="adhaarno" name="adhaarno" placeholder="Your Adhaar Number" class="form-control" pattern="[0-9]{4}[0-9]{4}[0-9]{4}" required>
                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="adhaarfile" class=" col-md-12 form-label">Upload Your Adhaar</label>
                    <div class="col-sm-12">

                        <input type="file" name="adhaarfile" id="adhaarfile" class="form-control">

                    </div>
                </div>
                <br />

                <div class="col-md-6">
                    <label for="photofile" class=" col-md-6 form-label">Upload Your Photo</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="file" name="photofile" id="photofile" required>
                    </div>
                </div>
                <br />
                <div class="col-md-6">
                    <label for="resume" class=" col-md-12 form-label">Upload Your Resume</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="file" name="resume" id="resume">
                    </div>
                </div>
                <br />

                <!-- /form -->
            </div>
    </div>
    <div class="card-footer">
        <div class="form-group">
            <div class="col-sm-12 ">
                <button type="submit" class="btn btn-warning">Register</button>
            </div>
        </div>
        </form>
    </div>
</div>
<?php include_once("footer.php") ?>
<script>
    $(document).ready(function() {

        $("#dateofbirth").focus(function() {
            let thisYear = new Date();
            thisYear = thisYear.getFullYear();
            let allowedYear = thisYear - 5;
            allowedYear = allowedYear.toString();

            let year = new Date(allowedYear);
            let dd = String(year.getDate()).padStart(2, '0');
            let mm = String(year.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = year.getFullYear();

            year = yyyy + '-' + mm + '-' + dd;

            $("#dateofbirth").attr("max", year);
        })
    });
</script>