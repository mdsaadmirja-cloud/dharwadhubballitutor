<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/jobOps.php";
include "../../Admin/Utilities/Helper.php";
include_once "header.php";

$Id = $_GET['id'];
$job = DBJob::getjobById($Id);
?>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Job Details</h6>
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
                        <th>Title</th>
                        <th>Description</th>
                        <th>Job Location</th>
                        <th>Remote Work</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $jobList = DBJob::getjobById($Id);
                    foreach ($jobList as $job) {
                        echo "<tr> <td style=display:none>" . $job->getId() . "</td>
                        <td>" . $job->getName() . "</td>
                        <td>" . $job->getDescription() . "</td> 
                        <td>" . $job->getJobLocation() . "</td>
                        <td>" . $job->getRemoteWork() . "</td>
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
                            data-id='" . $job->getId() . "'>
                            <i class='fas fa-pencil-alt'></i> 
                                Edit 
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-toggle='modal' 
                           data-target='#deleteModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $job->getId() . "'>
                            <i class='fas fa-trash-alt'></i>
                              Delete 
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
<div class="modal fade" id=addModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" action="/Admin/Controller/jobController.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Job Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Title <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Name" id="Name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                                <input type="hidden" name="jobid" id="id" value="<?php echo $Id; ?>" />

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

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Job Location <span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <input type="text" name="JobLocation" id="JobLocation" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Remote Work</label>
                            <div class="col-md-8">
                                <select class="custom-select" id="RemoteWork" name="RemoteWork">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
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

<div class="modal fade" id=editModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Job Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedName" id="editedName" class="form-control" required value="" />
                                <input type="hidden" name="editid" id="editid" value="" />
                                <input type="hidden" name="jobid" id="jobid" value="<?php echo $Id; ?>" />

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="editedDescription" id="editedDescription" class="form-control" required value=""></textarea>
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Job Location <span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedJobLocation" id="editedJobLocation" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Remote Work</label>
                            <div class="col-md-8">
                                <select class="custom-select" id="editedRemoteWork" name="RemoteWork">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="submit" id="editbutton" class="btn btn-success" value="save" />
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
                    <h4 class="modal-title" id="modal_title">Delete Job Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Job Details.
                    </p>
                    <input type="hidden" name="deleteid" id="deleteid" value="">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" name="submit" id="deletebutton" class="btn " value="Confirmed" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

// Check #x
$( "#RemoteWork" ).prop( "checked", true );
 
// Uncheck #x
$( "#RemoteWork" ).prop( "checked", false );

        // $('#RemoteWork').change(function() {
        //     debugger;
        //     if (this.checked) {
        //         $('#RemoteWork').val(1)
        //     } else {
        //         $('#RemoteWork').val(0)
        //     }
        // })

        $('#editModal').on('show.bs.modal', function(e) {
            debugger;
            var rowid = $(e.relatedTarget).data('id');
            $('#editid').val(rowid);
        });


        $('#temp_table tbody').on('click', 'tr', function() {
            debugger;
            /* Get the row as a parent of the link that was clicked on */
            // $('#id').val(this.cells[0].innerHTML);
            $('#editedName').val(this.cells[1].innerHTML);
            $('#editedDescription').val(this.cells[2].innerHTML);
            $('#editedJobLocation').val(this.cells[3].innerHTML);
            $('#editedRemoteWork').val(this.cells[4].innerHTML);
        });

        var dataTable = $('#temp_table').DataTable({

        });

        var nEditing = null;

        $('#editbutton').click(function(event) {
            debugger;
            var formData = {
                editid: $('#editid').val(),
                Name: $('#editedName').val(),
                Description: $('#editedDescription').val(),
                JobLocation: $('#editedJobLocation').val(),
                RemoteWork: $('#editedRemoteWork').val(),

            };
            $.ajax({
                type: "POST",
                url: config.developmentPath +
                    "/Admin/Controller/jobController.php",
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
                url: config.developmentPath + "/Admin/Controller/jobController.php",
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