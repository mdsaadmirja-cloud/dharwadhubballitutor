<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/CoursemodalOps.php";
include "../../Admin/Utilities/Helper.php";
include_once "header.php";
$Id = $_GET['id'];
$coursemodal = DBCourseModal::getcourseModalById($Id);
?>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Course Details </h6>
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
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $CourseModalList = DBCourseModal::getcourseModalById($Id);
                    foreach ($CourseModalList as $coursemodal) {
                        echo "<tr><td>" . $coursemodal->getName() . "</td>
                        <td>" . $coursemodal->getDescription() . "</td>                
                </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id=addModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" action="../Controller/newcoursemodal.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Course Module</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Name" id="Name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                                <input type="hidden" name="id" id="id" value="<?php echo $Id; ?>"  />

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="Description" id="Description" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    </br>
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
<?php include "footer.php"; ?>