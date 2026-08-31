<?php
require_once "../session.php";
require_once "../../Admin/DB Operations/expenseOps.php";
require_once "../../Admin/model/expenseModel.php";
require_once "header.php"

?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Expense Details</h6>
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
            <table class="table table-bordered" id="expense" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style=display:none>Id</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Category </th>
                        <th>Receipts</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $expenselist = DBExpense::getexpensedetails();
                    foreach ($expenselist as $expense) {
                        echo "<tr> <td style=display:none>" . $expense->getId() . "</td>
                        <td>" . $expense->getDate() . "</td>
                        <td>" . $expense->getName() . "</td> 
                        <td>" . $expense->getDescription() . "</td> 
                        <td>" . $expense->getAmount() . "</td> 
                        <td>" . $expense->getCategory() . "</td> 
                        <td>" . $expense->getReceipts() . "</td>                    
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
                            data-id='" . $expense->getId() . "'>
                            <i class='fas fa-eye'></i>
                            <a class='btn ' href='../View/expenseview.php?id=" . $expense->getId() .
                            "'role='button'>View </a>
                            </button>
                            <button class='btn btn-primary dropdown-item'
                            data-toggle='modal' 
                            data-target='#editModal' 
                            role='button' 
                            data-id='" . $expense->getId() . "'>
                            <i class='fas fa-pencil-alt'></i> 
                                Edit Modal
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-toggle='modal' 
                           data-target='#deleteModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $expense->getId() . "'>
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

<div class="modal fade" id=addModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" action="../Controller/expenseController.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Expense Details</h4>
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
                            <label class="col-md-4 text-right">Title <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Name" id="Name" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text  " name="Description" id="Description" class="form-control" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Amount <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Amount" id="Amount" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Category </label>
                            <div class="col-md-8">
                                <select class="custom-select" id="Category" name="Category">
                                    <option value="">Select</option>
                                    <option value="Rent">Rent</option>
                                    <option value="Bills">Bills</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Receipts <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="file" name="Receipts" id="Receipts" class="form-control" required value="" />
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
                    <h4 class="modal-title" id="modal_title">Expense Details</h4>
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
                            <label class="col-md-4 text-right">Title <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedname" id="editedname" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text  " name="editeddescription" id="editeddescription" class="form-control" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Amount <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="editedamount" id="editedamount" class="form-control" required value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right"> Category </label>
                            <div class="col-md-8">
                                <select class="custom-select" id="editedcategory" name="editedcategory">
                                    <option value="">Select</option>
                                    <option value="Rent">Rent</option>
                                    <option value="Bills">Bills</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Receipts <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="file" name="editedreceipts" id="editedreceipts"  class="form-control" value="" />
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
                    <h4 class="modal-title" id="modal_title">Delete Expense Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Expense Details.
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
        $('#editModal').on('show.bs.modal', function(e) {
            debugger;
            var rowid = $(e.relatedTarget).data('id');
            $('#editedid').val(rowid);
        });


        $('#expense tbody').on('click', 'tr', function() {
            debugger;
            /* Get the row as a parent of the link that was clicked on */
            //  $('#editedid').val(this.cells[0].innerHTML);
            $('#editeddate').val(this.cells[1].innerHTML);
            $('#editedname').val(this.cells[2].innerHTML);
            $('#editeddescription').val(this.cells[3].innerHTML);
            $('#editedamount').val(this.cells[4].innerHTML);
            $('#editedcategory').val(this.cells[5].innerHTML);

        });

        var dataTable = $('#expense').DataTable({});

        var nEditing = null;

        $('#editbutton').click(function(event) {
            debugger;
            var formData = {
                editedid: $('#editedid').val(),
                date: $('#editeddate').val(),
                name: $('#editedname').val(),
                description: $('#editeddescription').val(),
                amount: $('#editedamount').val(),
                category: $('#editedcategory').val(),

            };
            console.log(formData);
            $.ajax({
                type: "POST",
                url: config.developmentPath +
                    "/Admin/Controller/expenseController.php",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function(data) {
                console.log(data);
            });
        });

        $('#deleteModal').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $('#deleteid').val(rowid);
        });
        $('#deletebutton').click(function() {
            $.ajax({
                url: config.developmentPath + "/Admin/Controller/expenseController.php",
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