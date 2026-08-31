<?php
require "../session.php";
require_once "../../Admin/DB Operations/companyOps.php";
require_once "../../Admin/model/companyModel.php";
require_once "header.php"

?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Company Details</h6>
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
            <table class="table table-bordered" id="company" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style=display:none>Id</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone </th>
                        <th>Location</th>
                        <th>Approached By</th>
                        <th>Company Name</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $companylist = DBCompany::getcompanydetails();
                    foreach ($companylist as $company) {
                        echo "<tr> <td style=display:none>" . $company->getId() . "</td>
                        <td>" . $company->getDate() . "</td>
                        <td>" . $company->getName() . "</td> 
                        <td>" . $company->getEmail() . "</td> 
                        <td>" . $company->getPhone() . "</td> 
                        <td>" . $company->getLocation() . "</td> 
                        <td>" . $company->getApproachedby() . "</td> 
                        <td>" . $company->getCompanyname() . "</td> 
                        
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
                           name='view_button' 
                           role='button' 
                           data-id='" . $company->getId() . "'>
                           <i class='fas fa-eye'></i>
                            <a class='btn ' href='../View/job.php?id=" . $company->getId() .
                            "'role='button'>View </a>
                              </button>
                            <button class='btn btn-primary dropdown-item'
                            data-toggle='modal' 
                            data-target='#editModal' 
                            role='button' 
                            data-id='" . $company->getId() . "'>
                            <i class='fas fa-pencil-alt'></i> 
                                Edit Modal
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-toggle='modal' 
                           data-target='#deleteModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $company->getId() . "'>
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
        <form method="post" id="user_form" action="../Controller/companyController.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Company Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Date<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="Date" id="Date" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="name" id="name" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Email<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="email  " name="email" id="email" class="form-control" placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Phone<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <input type="number" name="phone" id="phone" class="form-control" placeholder="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Location <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="location" id="location" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Approached By</label>
                            <div class="col-md-8">
                                <select class="custom-select" id="Approachedby" name="Approachedby">
                                    <option value="">Select</option>
                                    <option value="Company">Company</option>
                                    <option value="Dharwadhubballitutor">Dharwadhubballitutor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Company Name <span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <input type="text" name="companyname" id="companyname" class="form-control" value="" />
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
                    <h4 class="modal-title" id="modal_title">Company Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Date<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="editeddate" id="editeddate" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                                <input type="hidden" name="editedid" id="editedid" value="">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedname" id="editedname" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Email<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="email" name="editedemail" id="editedemail" class="form-control" />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Phone<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" name="editedphone" id="editedphone" class="form-control" placeholder="" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Location <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedlocation" id="editedlocation" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Approached By</label>
                            <div class="col-md-8">
                                <select class="custom-select" id="editedApproachedby" name="Approachedby">
                                    <option value="">Select</option>
                                    <option value="Company">Company</option>
                                    <option value="Dharwadhubballitutor">Dharwadhubballitutor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </br>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Company Name <span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedcompanyname" id="editedcompanyname" class="form-control" value="" />
                            </div>
                        </div>
                    </div>
                    <br />

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
                    <h4 class="modal-title" id="modal_title">Delete company Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this company Details.
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

        // $("#Approachedby").change(function() {
        //     debugger;
        //     if($(this).val() == "Company"){
        //         $("#companyname").show();
        //     }else{
        //         $("#companyname").hide();
        //     }
        // });


        // $("#editedApproachedby").change(function() {
        //     debugger;
        //     if($(this).val() == "Company"){
        //         $("#editcompany").show();
        //     }else{
        //         $("#editcompany").hide();
        //     }
        // });

        $('#editModal').on('show.bs.modal', function(e) {
            debugger;
            var rowid = $(e.relatedTarget).data('id');
            $('#editedid').val(rowid);
        });


        $('#company tbody').on('click', 'tr', function() {
            debugger;
            /* Get the row as a parent of the link that was clicked on */
            //  $('#editedid').val(this.cells[0].innerHTML);
            $('#editeddate').val(this.cells[1].innerHTML);
            $('#editedname').val(this.cells[2].innerHTML);
            $('#editedemail').val(this.cells[3].innerHTML);
            $('#editedphone').val(this.cells[4].innerHTML);
            $('#editedlocation').val(this.cells[5].innerHTML);
            $('#editedapproachedby').val(this.cells[6].innerHTML);
            $('#editedcompanyname').val(this.cells[7].innerHTML);

        });

        var dataTable = $('#company').DataTable({});

        var nEditing = null;

        $('#editbutton').click(function(event) {
            debugger;
            var formData = {
                editedid: $('#editedid').val(),
                date: $('#editeddate').val(),
                name: $('#editedname').val(),
                email: $('#editedemail').val(),
                phone: $('#editedphone').val(),
                location: $('#editedlocation').val(),
                approachedby: $('#editedApproachedby').val(),
                companyname: $('#editedcompanyname').val(),

            };
            console.log(formData);
            $.ajax({
                type: "POST",
                url: config.developmentPath +
                    "/Admin/Controller/companyController.php",
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
                url: config.developmentPath + "/Admin/Controller/companyController.php",
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