<?php
require "../../Admin/session.php";
require "../../Admin/Utilities/Helper.php";
include "../../Admin/DB Operations/followupOps.php";
require_once "../../Admin/DB Operations/CoursesOps.php";
require "../../Model/Registration.php";
require_once "../../Admin/DB Operations/enqueryOps.php";
require_once "header.php";
$id = $_GET["id"];
$details = DBenquery::getEnqueryById($id);
?>
<div class="card">
    <div class="card-header">
        <h6>Edit Enquiry</h6>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="../Controller/newenquiry.php" method="POST" role="form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class=label for=name2>Name</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                        <input type=text name=name2 class=form-control id=name2 placeholder=Name required value="<?php echo $details->get_name(); ?>" />
                    </div>
                </div>
                <br />
                <div class="col-md-4">
                    <label class=label for=email2>Email</label>
                    <div class="col-sm-12">
                        <input type=email name=email2 class=form-control id=email2 placeholder=name@example.com value="<?php echo $details->get_email(); ?>" />
                    </div>
                </div>
                <br />
                <div class="col-md-4">
                    <label class=label for=phone2>Phone:</label>
                    <div class="col-sm-12">
                        <input type=tel name=phone2 class=form-control id=phone2 placeholder=Number required value="<?php echo $details->get_phone() ?>" />
                    </div>
                </div>
                <br />
                <div class="col-md-4">
                    <label class=label for=source>Source:</label>
                    <div class="col-sm-12">
                        <input type=text name=source class=form-control id=source required value="<?php echo $details->get_Source() ?>" />
                    </div>
                </div>
                <br />
                <div class="col-md-4">
                    <label class="label" for="branch">Branch</label>
                    <div class="col-sm-12">
                        <select class="custom-select" id="branch" name="branch">

                            <option value="Belagavi" <?php if ($details->getBranch() == "Belagavi") echo "selected"; ?>>
                                Belagavi
                            </option>

                            <option value="Dharwad" <?php if ($details->getBranch() == "Dharwad") echo "selected"; ?>>
                                Dharwad
                            </option>

                            <option value="Hubballi" <?php if ($details->getBranch() == "Hubballi") echo "selected"; ?>>
                                Hubballi
                            </option>

                            <option value="Online" <?php if ($details->getBranch() == "Online") echo "selected"; ?>>
                                Online
                            </option>

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class=label for=trainings2>Trainings</label>
                    <div class="col-sm-12">
                        <select class="custom-select" id="trainings2" name="trainings2">
                            <option value="">Select your Interest</option>
                            <?php
                            $option = "";
                            $courselist = DBcourse::selectall();
                            foreach ($courselist as $course) {
                                if ($details->getEnqType() == "Trainings") {
                                    if ($details->get_enqueryFor() == $course->get_cname()) {
                                        $option .= "<option selected value='" . $course->get_cname() . "'>" . $course->get_cname() . "</option>";
                                    } else {
                                        $option .= "<option value='" . $course->get_cname() . "'>" . $course->get_cname() . "</option>";
                                    }
                                } else {
                                    $option .= "<option value='" . $course->get_cname() . "'>" . $course->get_cname() . "</option>";
                                }
                            }
                            echo $option;
                            ?>
                        </select><br />
                    </div>
                </div>
                <br />
                <div class="col-md-6">
                    <label class=label for=internship2>Internships</label>
                    <div class="col-sm-12">
                        <input type=text class=form-control id=internship2 name=internship2 value="<?php if ($details->getEnqType() == "Internship") {
                                                                                                        echo $details->get_enqueryFor();
                                                                                                    } ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class=label for=services>Services</label>
                    <div class="col-sm-12">
                        <input type=text class=form-control id=services name=services value="<?php if ($details->getEnqType() == "Services") {
                                                                                                    echo $details->get_enqueryFor();
                                                                                                } ?>">

                    </div>
                </div>
                <br />

            </div>
    </div>
    <div class="card-footer">
        <input type="hidden" id="recaptcha-token" name="recaptcha-token">
        <div class="form-group">
            <div class="col-sm-12 ">
                <button type="submit" class="btn btn-warning">Save</button>
            </div>
        </div>
    </div>
    </form>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg"></script>
<script>
    function onSubmit(token) {
        document.getElementById("contactForm").submit();
    }

    function prepareRecaptcha() {
        grecaptcha.ready(function() {
            grecaptcha.execute('6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                action: 'submit'
            }).then(function(token) {
                document.getElementById('recaptcha-token').value = token;
            });
        });
    }

    // Trigger on load
    window.onload = prepareRecaptcha;
</script>
<?php
require_once "footer.php";
?>