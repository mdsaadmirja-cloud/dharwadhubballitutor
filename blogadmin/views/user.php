<?php
include "session.php";
include('header.php');
require_once("../DB Operations/userOps.php");
require_once("../Model/usermodel.php");
?>
<h1 class="h3 mb-4 text-gray-800">User Management</h1>
<!-- DataTales Example -->
<span id="message"></span>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">User List</h6>
            </div>
            <div class="col" align="right">
                <span data-toggle=modal data-target=#userModal>
                    <button type="button" + class="btn btn-success btn-circle btn-sm"><i
                            class="fas fa-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="user_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>User Contact No.</th>
                        <th>User Email</th>
                        <th>User Password</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userList = DBuser::getAllUsers();
                    foreach ($userList as $user) {
                        echo "<tr><td>" . $user->get_username() . "</td>
                        <td>" . $user->get_usercontact() . "</td>
                        <td>" . $user->get_useremail() . "</td>
                        <td>" . $user->get_userpassword() . "</td>
                        <td>" . $user->get_usertype() . "</td>
                        <td>" . $user->get_userstatus() . "</td>
                        <td><a class='btn btn-secondary btn-circle btn-sm tooltip action-btn' data-toggle='modal' data-target='#editUserModal' role='button' data-id='".$user->get_id()."'> 
                        <i class='fas fa-user-edit'></i>
                        <span class='tooltiptext'>Edit User</span>
                        </a>
                        <a class='btn btn-danger btn-circle btn-sm tooltip action-btn' data-toggle='modal' data-target='#deleteUserModal' name='delete_button' role='button' data-id='" . $user->get_id() . "'>
                        <i class='fas fa-trash-alt'></i>
                        <span class='tooltiptext'>Delete User</span>
                        </a>"."</td></tr>
                    </span> </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>


<div class="modal fade" id=userModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" enctype="multipart/form-data" action="../Controller/newuser.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add Data</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="username" id="username" class="form-control" required
                                    data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150"
                                    data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Contact No. <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="usercontact" id="usercontact" class="form-control" required
                                    data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12"
                                    data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="useremail" id="useremail" class="form-control"
                                    data-parsley-type="email" data-parsley-maxlength="150"
                                    data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Password <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="password" name="userpassword" id="userpassword" class="form-control"
                                    required data-parsley-minlength="6" data-parsley-maxlength="16"
                                    data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="usertype" id="usertype" class="form-control" required
                                    data-parsley-trigger="change">
                                    <option value="">Select Type</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
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
        </form>
    </div>
</div>
<div class="modal fade" id=editUserModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" enctype="multipart/form-data" action="../Controller/newuser.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Edit Data</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="username" id="editedusername" class="form-control" required
                                    data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150"
                                    data-parsley-trigger="keyup" />
                                <input type="hidden" name="userid" id="userid" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Contact No. <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="usercontact" id="editedusercontact" class="form-control"
                                    required data-parsley-type="integer" data-parsley-minlength="10"
                                    data-parsley-maxlength="12" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="useremail" id="editeduseremail" class="form-control"
                                    data-parsley-type="email" data-parsley-maxlength="150"
                                    data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Password <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="userpassword" id="editeduserpassword" class="form-control"
                                    required data-parsley-minlength="6" data-parsley-maxlength="16"
                                    data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="usertype" id="editedusertype" class="form-control" required
                                    data-parsley-trigger="change">
                                    <option value="">Select Type</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">User Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="userstatus" id="editeduserstatus" class="form-control" required
                                    data-parsley-trigger="change">
                                    <option value="">Select Type</option>
                                    <option value="Enable">Enable</option>
                                    <option value="Disable">Disable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="submit" name="submit" id="editbutton" class="btn btn-success" value="Save" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id=deleteUserModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="POST" id="delete_user_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Delete User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this user.
                    </p>
                    <input type="hidden" name="userid" id="userid" value="">
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
    $('#editUserModal').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id');
        $('#userid').val(rowid);
    });
    var dataTable = $('#user_table').DataTable({

            });
            var nEditing = null;

    $('#user_table tbody').on('click', 'tr', function() {
        /* Get the row as a parent of the link that was clicked on */
        $('#editedusername').val(this.cells[0].innerHTML);
        $('#editedusercontact').val(this.cells[1].innerHTML);
        $('#editeduseremail').val(this.cells[2].innerHTML);
        $('#editeduserpassword').val(this.cells[3].innerHTML);
        $('#editedusertype').val(this.cells[4].innerHTML);
        $('#editeduserstatus').val(this.cells[5].innerHTML);
        $('#editUserModal').show();
    });
    $('#editbutton').click(function(event) {
        var formData = {
            userid: $('#userid').val(),
            username: $('#editedusername').val(),
            usercontact: $('#editedusercontact').val(),
            useremail: $('#editeduseremail').val(),
            userpassword: $('#editeduserpassword').val(),
            usertype: $('#editedusertype').val(),
            userstatus: $('#editeduserstatus').val()
        };
        $.ajax({
            type: "POST",
            url:  config.developmentPath+"/Admin/Controller/newuser.php/",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function(data) {
            console.log(data);
        });
        $('#editbutton').dispose();
        event.preventDefault();
    });
    $('#deleteUserModal').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id');
        $('#userid').val(rowid);
    });
    $('#deletebutton').click(function() {
        $.ajax({
            url:  config.developmentPath+"/Admin/Controller/newuser.php/",
            method: "POST",
            data: {
                id: $('#userid').val(),
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