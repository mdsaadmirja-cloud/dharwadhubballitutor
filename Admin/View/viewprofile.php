<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/AdmissionsOps.php";
include "../../Admin/model/Admissionsmodel.php";
include "../../Admin/Utilities/Helper.php";
include "../../Admin/DB Operations/CoursesOps.php";
include "../../Admin/DB Operations/CoursemodalOps.php";
include "../../Admin/DB Operations/placementOps.php";
include_once "header.php";

$id = $_GET['id'];
$admission = DBadmission::viewadmission($id);
$view = DBcourse::getCourseByAdmissionid($id);
?>


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Students Details </h6>
            </div>
            <div class="col-md-6">
                <div class="form-check float-right">
                    <input type="radio" class="btn-check" name="options" id="option2">
                    <label class="" for="option2">Edit <i class="fas fa-edit"></i></label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 ">
                <div class="d-flex flex-column align-items-center text-center">
                    <img src="<?php echo '../uploads/', $admission->get_photofile(); ?>" alt="Admin" width="200" height="200" />
                    <label class="btn btn-default" for="photofile">
                        <i class="fa fa-camera" aria-hidden="true"></i></label>
                    <input type="file" name="photofile" id="photofile" class="form-control" form="myForm" hidden>
                </div>
            </div>
            <div class="col-md-8">
                <form class="form" action="../Controller/stdprofileupdate.php" method="POST" id="myForm" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="col-md-6 control-label">Full Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" placeholder="Full Name" name="name" class="form-control" pattern="[a-zA-Z\-\ ]+" value="<?php echo $admission->get_name(); ?>">
                                <input type="hidden" id="id" name="id" value="<?php echo $admission->get_id(); ?>">
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="phone" class="col-md-6 control-label">Phone</label>
                            <div class="col-sm-12">
                                <input type="tel" id="phone" placeholder="Phone" name="phone" class="form-control" value="<?php
                                                                                                                            echo $admission->get_phone();
                                                                                                                            ?>">
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="email" class="col-md-6 control-label">Email</label>
                            <div class="col-sm-12">
                                <input type="email" id="email" placeholder="Email" name="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?php
                                                                                                                                                                                echo $admission->get_email();
                                                                                                                                                                                ?>">
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                            <label for="dateofbirth" class="col-md-6 control-label">Date of
                                Birth</label>
                            <div class="col-sm-12">
                                <input type="date" id="dateofbirth" name="dateofbirth" class="form-control" required value="<?php
                                                                                                                            echo $admission->get_dateofbirth();
                                                                                                                            ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="admissionstatus" class="col-md-6 control-label">Admission Status</label>
                            <div class="col-sm-12">
                                <select id="admissionstatus" name="admissionstatus" class="form-select" required>
                                    <option value="0" <?php echo $admission->get_Admissionstatus() == 0 ? 'selected' : ''; ?>>Cancelled</option>
                                    <option value="1" <?php echo $admission->get_Admissionstatus() == 1 ? 'selected' : ''; ?>>Active</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="cancellationReason" class="col-md-6 control-label">Cancellation Reason</label>
                            <div class="col-sm-12">
                                <textarea id="cancellationReason" name="cancellationReason" class="form-control" required><?php
                                                                                                                            echo $admission->get_CancellationReason();
                                                                                                                            ?></textarea>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" align=center>
                <label for="gender" class="control-label">Gender</label></br>
                <div class="form-check-inline">
                    <?php
                    // Get the gender value
                    $gender = $admission->get_gender();

                    // Define the checked status for each gender
                    $isFemaleChecked = ($gender === "Female") ? "checked" : "";
                    $isMaleChecked = ($gender === "Male") ? "checked" : "";

                    // Output the radio buttons
                    echo '
<div class="form-check-inline">
    <input class="form-check-input" 
           type="radio" 
           name="gender" 
           id="genderFemale" 
           value="Female" ' . $isFemaleChecked . '>
    <label class="form-check-label" 
           for="genderFemale">Female</label>
</div>
<div class="form-check-inline">
    <input class="form-check-input" 
           type="radio" 
           name="gender" 
           id="genderMale" 
           value="Male" ' . $isMaleChecked . '>
    <label class="form-check-label" 
           for="genderMale">Male</label>
</div>';
                    ?>

                </div>
            </div>
            <br />
            <div class="col-md-6">
                <label for="qualification" class="col-md-6 control-label">Qualification</label>
                <div class="col-sm-12">
                    <input type="text" id="qualification" name="qualification" placeholder="Your Qualification" class="form-control" pattern="[A-Za-z]+" required value="<?php
                                                                                                                                                                            echo $admission->get_qualification();
                                                                                                                                                                            ?>">
                </div>
            </div>
            <br />
            <div class="col-md-6">
                <label for="branch" class="col-md-6 control-label">Branch</label>
                <div class="col-sm-12">
                    <select class="form-select" id="branch" name="branch" required>
                        <option value="Dharwad" <?php echo ($admission->get_branch() == "Dharwad") ? "selected" : ""; ?>>Dharwad</option>
                        <option value="Hubballi" <?php echo ($admission->get_branch() == "Hubballi") ? "selected" : ""; ?>>Hubballi</option>
                        <option value="Belagavi" <?php echo ($admission->get_branch() == "Belagavi") ? "selected" : ""; ?>>Belagavi</option>
                        <option value="Online" <?php echo ($admission->get_branch() == "Online") ? "selected" : ""; ?>>Online</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <label for="guardiansname" class="col-md-12 control-label">Guardians
                    Name</label>
                <div class="col-sm-12">
                    <input type="text" id="guardiansname" name="guardiansname" placeholder="Guardians Name" class="form-control" pattern="[a-zA-Z\-\ ]+" required value="<?php
                                                                                                                                                                            echo $admission->get_guardiansname();
                                                                                                                                                                            ?>">
                </div>
            </div>
            <br />
            <div class="col-md-6">
                <label for="guardiansphone" class="col-md-12 control-label">Guardians Phone
                    Number</label>
                <div class="col-sm-12">
                    <input type="text" id="guardiansphone" name="guardiansphone" placeholder="Guardians Phone Number" class="form-control" required value="<?php
                                                                                                                                                            echo $admission->get_guardiansphone();
                                                                                                                                                            ?>">
                </div>
            </div>
            <br />
            <div class="col-md-6">
                <label for="coursesopted" class="col-md-6 control-label">Courses Opted</label>
                <div class="col-sm-12">
                    <select class="form-select" id="coursesopted" name="coursesopted" required>
                        <?php
                        $option = "";
                        $courselist = DBcourse::selectall();
                        foreach ($courselist as $course) {
                            $option .= "<option value='" . $course->get_id() . "'";
                            if ($course->get_id() == $admission->get_courseid()) {
                                $option .= "selected";
                                echo $course->get_id();
                            }
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
                    <textarea id="address" name="address" placeholder="Residential Address" class="form-control" required><?php echo $admission->get_address(); ?>
                        </textarea>
                </div>
            </div>
            <br />
            <div class="col-md-6">
                <label for="adhaarno" class="col-md-12 control-label">Adhaar Number</label>
                <div class="col-sm-12">
                    <input type="text" id="adhaarno" name="adhaarno" placeholder="Your Adhaar Number" class="form-control" pattern="[0-9]{4}[0-9]{4}[0-9]{4}" value="<?php
                                                                                                                                                                        echo $admission->get_adhaarno();
                                                                                                                                                                        ?>">
                </div>
            </div>
            <br />
            <div class="col-md-6">
                <label for="adhaarfile" class="col-md-6 form-label">Adhaar File</label>
                <div class="col-md-12">
                    <a href="<?php echo "../uploads/" . $admission->get_adhaarfile(); ?>" class="form-control" download> Click to download Adhaar
                        file</a>
                </div>
            </div>
            <div class="col-md-6">
                <label for="adhaarfile" class=" col-md-6 form-label">Resume</label>
                <div class="col-sm-12">
                    <?php
                    $resume = $admission->get_resume();

                    $resumePath = "../uploads/resume/" . $resume;
                    $oldResumePath = "../uploads/" . $resume;

                    if (file_exists($resumePath)) {
                        $downloadPath = $resumePath;
                    } elseif (file_exists($oldResumePath)) {
                        $downloadPath = $oldResumePath;
                    } else {
                        $downloadPath = "#";
                    }
                    ?>

                    <a href="<?php echo $downloadPath; ?>" class="form-control" download>
                        Click to Download Resume
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <label for="adhaarfile" class=" col-md-6 form-label">Upload Adhaar</label>
                <div class="col-sm-12">
                    <input type="file" name="adhaarfile" id="adhaarfile" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <label for="resume" class=" col-md-12 form-label">Upload Resume</label>
                <div class="col-sm-12">
                    <input type="file" name="resume" id="resume" class="form-control">

                </div>
            </div>
            <br />
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-success" type="submit" name="submit" form="myForm">Update</button>
        </form>
    </div>
</div>

<br />
<br />
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6>Course Complition Details</h6>
            </div>
        </div>
    </div>
    <div class="card-body text-center">
        <h4>
            <?php
            echo $view->get_coursesopted();
            ?>
        </h4>
        <br />
        <div class="container">
            <?php
            $result = "";
            $courseId = $view->get_coursesopted();
            $CourseModalList = DBCourseModal::getcourseModalByCName($courseId);
            foreach ($CourseModalList as $coursemodal) {
                $result .= '<div class="circles active" ><p>' . $coursemodal->getName() . '</p></div>';
            }
            echo $result;
            ?>

        </div>
    </div>
</div>
<br />
<br />
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Placement Details</h6>
            </div>
            <div class="col" align="right">
                <span data-toggle=modal data-target=#addModal>
                    <button type="button" + class="btn btn-warning btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="temp_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style=display:none>Id</th>
                        <th>Assisted on</th>
                        <th>Company Name</th>
                        <th>Job Role</th>
                        <th>Result </th>
                        <th>Reason</th>
                        <th>Rejected By</th>
                        <th>Offer Letter</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $placementList = DBPlacement::getplacementbyId($id);
                    foreach ($placementList as $placement) {
                        echo "<tr> <td style=display:none>" . $placement->getId() . "</td>
                        <td>" . $placement->getAssistedon() . "</td>
                        <td>" . $placement->getCompanyname() . "</td> 
                        <td>" . $placement->getJobpost() . "</td> 
                        <td>" . $placement->getResult() . "</td> 
                        <td>" . $placement->getReason() . "</td> 
                        <td>" . $placement->getRejectedby() . "</td> 
                        <td>" . $placement->getofferletter() . "</td>
                        <td> 
                        <div class='dropdown'>
                        <button class='btn btn-secondary dropdown-toggle' 
                        type='button' 
                        id='dropdownMenu2' 
                        data-toggle='dropdown' 
                        aria-expanded='false'>
                        Actions
                        </button>
                        <div class='dropdown-menu' 
                        aria-labelledby='dropdownMenu2'>
                            <button class='btn btn-primary dropdown-item'
                            data-toggle='modal' 
                            data-target='#editModal' 
                            role='button' 
                            data-id='" . $placement->getId() . "'>
                            <i class='fas fa-pencil-alt'></i> 
                                Edit Modal
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-toggle='modal' 
                           data-target='#deleteModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $placement->getId() . "'>
                            <i class='fas fa-trash-alt'></i>
                              Delete Modal
                          </button>
                        </div>
                        </div>
                    </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</div>


<div class="modal fade" id=addModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" action="../Controller/placementController.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Placement Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Assisted on<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="Assistedon" id="Assistedon" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                                <input type="hidden" name="Admissionid" id="Admissionid" value="<?php echo $id; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Company Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Companyname" id="Companyname" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Job Role<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="jobpost" id="jobpost" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Result<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="custom-select" id="Result" name="Result">
                                    <option value="">Select</option>
                                    <option value="Inprogress">Inprogress</option>
                                    <option value="Placed">Placed</option>
                                    <option value="GotRejected">GotRejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group" id="Rejected" style="display:none;">
                        <div class="row">
                            <label class="col-md-4 text-right">Rejected By</label>
                            <div class="col-md-8">
                                <select class="custom-select" id="Rejectedby" name="Rejectedby">
                                    <option value="">Select</option>
                                    <option value="Company">Company</option>
                                    <option value="Candidate">Candidate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group" id="Reasonfor" style="display:none;">
                        <div class="row">
                            <label class="col-md-4 text-right">Reason<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Reason" id="Reason" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="letter" style="display:none;">
                        <div class="row">
                            <label class="col-md-4 text-right" for="offerletter">Offer Later</label>
                            <div class="col-md-8">
                                <input type="file" name="offerletter" id="offerletter" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="Createdby" id="Createdby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="Modifiedby" id="Modifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex=-1 role=dialog aria-hidden=true enctype="multipart/form-data">
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Placement Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Assisted on<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="editedAssistedon" id="editedAssistedon" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                                <input type="hidden" name="Admissionid" id="Admissionid" value="<?php echo $id; ?>" />
                                <input type="hidden" name="editedId" id="editedId" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Company Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedCompanyname" id="editedCompanyname" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Job Role<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedjobpost" id="editedjobpost" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Result<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="custom-select" id="editedResult" name="editedResult">
                                    <option value="">Select</option>
                                    <option value="Inprogress">Inprogress</option>
                                    <option value="Placed">Placed</option>
                                    <option value="GotRejected">GotRejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group" id="editRejected" style="display:none;">
                        <div class="row">
                            <label class="col-md-4 text-right">Rejected By</label>
                            <div class="col-md-8">
                                <select class="custom-select" id="editedRejectedby" name="editedRejectedby">
                                    <option value="">Select</option>
                                    <option value="Company">Company</option>
                                    <option value="Candidate">Candidate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group" id="editReasonfor" style="display:none;">
                        <div class="row">
                            <label class="col-md-4 text-right">Reason<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedReason" id="editedReason" class="form-control" />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group" id="editletter" style="display:none;">
                        <div class="row">
                            <label class="col-md-4 text-right" for="offerletter">Offer Later</label>
                            <div class="col-md-8">
                                <input type="file" name="editedofferletter" id="editedofferletter" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="editedCreatedby" id="editedCreatedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="editedModifiedby" id="editedModifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="submit" id="editbutton" class="btn btn-success" value="Add" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id=deleteModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="POST" id="delete_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Delete Placement Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Placement Details.
                    </p>
                    <input type="hidden" name="deleteid" id="deleteid" value="">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" name="submit" id="deletebutton" class="btn btn-danger" value="Confirmed" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#myForm :input").prop("disabled", true);
        $('input[type=radio][name=options]').click(function() {
            $('#myForm :input').prop('disabled', false);
        });

        $("#Result").change(function() {
            debugger;
            if ($(this).val() == "GotRejected") {
                $("#Reasonfor").show();
                $("#Rejected").show();
            } else {
                $("#Reasonfor").hide();
                $("#Rejected").hide();
            }
        });
        $("#editedResult").change(function() {
            debugger;
            if ($(this).val() == "GotRejected") {
                $("#editReasonfor").show();
                $("#editRejected").show();
            } else {
                $("#editReasonfor").hide();
                $("#editRejected").hide();
            }
        });

        $("#Result").change(function() {
            debugger;
            if ($(this).val() == "Placed") {
                $("#letter").show();
            } else {
                $("#letter").hide();
            }
        });

        $("#editedResult").change(function() {
            debugger;
            if ($(this).val() == "Placed") {
                $("#editletter").show();
            } else {
                $("#editletter").hide();
            }
        });


        $('#editModal').on('show.bs.modal', function(e) {
            debugger;
            var rowid = $(e.relatedTarget).data('id');
            $('#editedId').val(rowid);
        });


        $('#temp_table tbody').on('click', 'tr', function() {
            debugger;
            /* Get the row as a parent of the link that was clicked on */
            //  $('#editedId').val(this.cells[0].innerHTML);
            $('#editedAssistedon').val(this.cells[1].innerHTML);
            $('#editedCompanyname').val(this.cells[2].innerHTML);
            $('#editedjobpost').val(this.cells[3].innerHTML);
            $('#editedResult').val(this.cells[4].innerHTML);
            $('#editedReason').val(this.cells[5].innerHTML);
            $('#editedRejectedby').val(this.cells[5].innerHTML);
            $('#editedofferletter').val(this.cells[5].innerHTML);
            $('#editedCreatedby').val(this.cells[4].innerHTML);
            $('#editedModifiedby').val(this.cells[5].innerHTML);

        });

        var dataTable = $('#temp_table').DataTable({

        });

        var nEditing = null;

        $('#editbutton').click(function(event) {
            debugger;
            var formData = {
                editid: $('#editedId').val(),
                Assistedon: $('#editedAssistedon').val(),
                CompanyName: $('#editedCompanyname').val(),
                Jobpost: $('#editedjobpost').val(),
                Result: $('#editedResult').val(),
                Reason: $('#editedReason').val(),
                Rejectedby: $('#editedRejectedby').val(),
                offerletter: $('#editedofferletter').val(),

            };
            console.log(formData);
            $.ajax({
                type: "POST",
                url: config.developmentPath +
                    "/Admin/Controller/placementController.php",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function(data) {
                condole.log(data);
            });

        });

        $('#deleteModal').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $('#deleteid').val(rowid);
        });
        $('#deletebutton').click(function() {
            $.ajax({
                url: config.developmentPath + "/Admin/Controller/placementController.php",
                method: "POST",
                data: {
                    id: $('#deleteid').val(),
                    action: 'delete'
                },
                success: function(data) {
                    $('#message').html(data);
                    dataTable.ajax.reload();
                    setTimeout(function() {
                        $('#message').html('');
                    }, 5000);
                }
            });

        });


    });
</script>