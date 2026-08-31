<?php
session_start();
include('header.php');
require_once("../dblayer/smsOps.php");
require_once("../dblayer/templateOps.php");
require_once("../model/templateModel.php");
?>
<style>
    img {
        width: 100%;
    }

    .card-body {
        height: 200px;
        overflow-x: hidden;

    }
</style>
<?php
$sms = DBsms::getsmsDetails();
?>
<div class="card  mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5>SMS Details</h5>
            </div>
            <div class="col" align="right">
                <input type="radio" name="editsmsbtn" id="editbtn" class="btn-check" />
                <label class="btn" for="editbtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 19 19">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                    </svg>
                    <i class="bi bi-pencil-square"></i>
                </label>
            </div>
        </div>
    </div>
    <div class="card">
        <form method="post" id="userform" action="../controller/smsController.php" enctype="multipart/form-data">
            <div class="row ">
                <div class="card-body">
                    <div class="col" align="right">
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <h6 class="mb-0">Username</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" class="col-md-6" name="username" value="<?php echo $sms->getusername() ?>">
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-2">
                            <h6 class="mb-0">API Key</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="APIkey" class="col-md-6" name="APIkey" value="<?php echo $sms->getAPIkey() ?>">
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-2">
                            <h6 class="mb-0">Key</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="key" class="col-md-6" name="key" value="<?php echo $sms->getkey() ?>">
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-2">
                            <h6 class="mb-0">Test</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="test" class="col-md-6" name="test" value="<?php echo $sms->gettest() ?>">
                        </div>
                    </div>
                    <br />
                    <div class="modal-footer">
                        <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<br />
<br />
<?php
$template = DBtemplate::getAlltemplate();
?>
<div class="row container-fluid">
    <div class="card">
        <div class="row ">
            <div class="card-header">Template</div>
            <div class="col" align="right">
                <span data-bs-toggle=modal data-bs-target=#addModal>
                    <button type="button" + class="btn btn-warning btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="temp_table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Header</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $templatelist = DBtemplate::getAlltemplate();
                            foreach ($templatelist as $template) {
                                echo "<tr><td>" . $template->getsender() . "</td>
                        <td>" . $template->getmessage() . "</td>                       
                        <td>
                        <div class='dropdown'>
                        <button class='btn btn-secondary dropdown-toggle' 
                        type='button' 
                        id='dropdownMenu2' 
                        data-bs-toggle='dropdown' 
                        aria-expanded='false'>
                        Actions
                        </button>
                        <div class='dropdown-menu' 
                        aria-labelledby='dropdownMenu2'>
                            <button class='btn btn-primary dropdown-item'
                            data-bs-toggle='modal' 
                            data-bs-target='#editModal' 
                            role='button' 
                            data-id='" . $template->getmessageId() . "'>
                            <i class='fas fa-user-edit'></i> 
                                Edit template
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-bs-toggle='modal' 
                           data-bs-target='#deleteModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $template->getmessageId() . "'>
                            <i class='fas fa-trash-alt'></i>
                              Delete template
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
    </div>
</div>

<div class="modal fade" id="addModal" tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog modal-xl">
        <form method="post" id="user_form" action="../controller/templateController.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Template</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Header <span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                <input type="text" name="sender" id="sender" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Message<span class="text-danger">*</span></label>
                            <div class="col-md-5">
                                <textarea type="text" name="message" id="message" class="form-control" required></textarea>
                            </div>                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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
                    <h4 class="modal-title" id="modal_title">Template</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Header <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="sender" id="editedsender" class="form-control" required />
                                <input type="hidden" name="messageId" id="messageId" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Message <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="message" id="editedmessage" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="CreatedBy" id="editedCreatedBy" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="ModifiedBy" id="editedModifiedBy" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="submit" name="submit" id="editbutton" class="btn btn-success" value="Save" />
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
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
                    <h4 class="modal-title" id="modal_title">Delete Template</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Template.
                    </p>
                    <input type="hidden" name="messageId" id="messageId" value="">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="submit" name="submit" id="deletebutton" class="btn btn-danger" value="Confirmed" />
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include('footer.php'); ?>
<script>
    $(document).ready(function() {

        $("#userform :input").prop("disabled", true);
        $('input[type=radio][name=editsmsbtn]').click(function() {
            $('#userform :input').prop('disabled', false);
        });

        $('#editsmsModal').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $('#editedId').val(rowid);
        });

        $('#editModal').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $('#messageId').val(rowid);

        });

        var dataTable = $('#table').DataTable({

        });
        var nEditing = null;

        $('#temp_table tbody').on('click', 'tr', function() {
            debugger;
            /* Get the row as a parent of the link that was clicked on */
            $('#editedsender').val(this.cells[0].innerHTML);
            $('#editedmessage').val(this.cells[1].innerHTML);
        });

        $('#editbutton').click(function(event) {
            var formData = {
                messageId: $('#messageId').val(),
                sender: $('#editedsender').val(),
                message: $('#editedmessage').val(),
                CreatedBy: $('#editedCreatedBy').val(),
                ModifiedBy: $('#editedModifiedBy').val(),
            };

            $.ajax({
                type: "POST",
                url: config.developmentPath +
                    "/blogadmin/controller/templateController.php",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function(data) {
                console.log(data);
            });
            $('#editbutton').dispose();
            event.preventDefault();
        });
        $('#deleteModal').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $('#messageId').val(rowid);
        });

        $('#deletebutton').click(function() {
            $.ajax({
                url: config.developmentPath + "/blogadmin/controller/templateController.php/",
                method: "POST",
                data: {
                    id: $('#messageId').val(),
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