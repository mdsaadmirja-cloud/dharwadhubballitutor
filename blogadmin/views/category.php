<?php
include "session.php";
include('header.php');
require_once("../dblayer/categoryOps.php");
require_once("../model/categoryModel.php");
?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Category</h6>
            </div>
            <div class="col" align="right">
                <span data-bs-toggle=modal data-bs-target=#itemcatModal>
                    <button type="button" + class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="itemcat_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Has Subcategory</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $itemcatlist = DBCategory::getAllCategory();
                    foreach ($itemcatlist as $itemcat) {
                        echo "<tr><td>" . $itemcat->getCategoryName() . "</td>
                        <td>" . $itemcat->getCategoryDescription() . "</td>
                        <td>" . $itemcat->getHasSubcategory() . "</td>
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
                            data-bs-target='#editItemcatModal' 
                            role='button' 
                            data-id='" . $itemcat->getCategoryId() . "'>
                            <i class='fas fa-user-edit'></i> 
                                Edit Category
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-bs-toggle='modal' 
                           data-bs-target='#deleteCategoryModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $itemcat->getCategoryId() . "'>
                            <i class='fas fa-trash-alt'></i>
                              Delete Category
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
<?php include('footer.php'); ?>
<div class="modal fade" id=itemcatModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="user_form" action="../controller/categoryController.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Category</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="itemcatname" id="itemcatname" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="itemcatdescription" id="itemcatdescription" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>                   

                    <div class="form-group">
                        <div class="row">
                            <div class="form-check form-check-inline">
                                <div class="col-md-4 text-right">
                                    <label class="form-check-label text-right" >Has sub<br> category</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-check-input" type="checkbox" id="itemcatHasSubcategory" name="HasSubcategory">
                                </div>
                            </div>
                        </div>
                    </div>                   

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="itemcatcreatedby" id="itemcatcreatedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="itemcatmodifiedby" id="itemcatmodifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
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
<div class="modal fade" id=editItemcatModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="post" id="itemcat_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Category</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="itemcatname" id="editedItemcatname" class="form-control" required />
                                <input type="hidden" name="itemcatid" id="itemcatid" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="itemcatdescription" id="editedItemcatdescription" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>                   
                    <div class="form-group">
                        <div class="row">
                            <div class="form-check form-check-inline">
                                <div class="col-md-4 text-right">
                                    <label class="form-check-label text-right">Has sub<br> category</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-check-input" type="checkbox" id="editedHasSubcategory" name="HasSubcategory">
                                </div>
                            </div>
                        </div>
                    </div>                   
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-8">
                                <input type="hidden" name="itemcatcreatedby" id="editedItemcatcreatedby" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="itemcatmodifiedby" id="editedItemcatmodifiedby" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value="<?php echo $_SESSION['login_user']; ?>" />
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
<div class="modal fade" id=deleteCategoryModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="POST" id="delete_category_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Delete Category</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this category.
                    </p>
                    <input type="hidden" name="itemcatid" id="itemcatid" value="">
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
<script>
    $(document).ready(function() {
        $('#editedHasSubcategory').on('change',function(){
        
            if($('#editedHasSubcategory').attr("checked")=='checked'){
                $('#editedHasSubcategory').attr("value","off");
            }else{
                $('#editedHasSubcategory').attr("value","on");
            }
        })
                $('#editItemcatModal').on('show.bs.modal', function(e) {
                    var rowid = $(e.relatedTarget).data('id');
                    $('#itemcatid').val(rowid);

                });
                var dataTable = $('#itemcat_table').DataTable({

                });

                var nEditing = null;

                $('#itemcat_table tbody').on('click', 'tr', function() {
                    debugger;
                    /* Get the row as a parent of the link that was clicked on */
                    $('#editedItemcatname').val(this.cells[0].innerHTML);
                    $('#editedItemcatdescription').val(this.cells[1].innerHTML);
                    
                    if (this.cells[2].innerHTML=="true"){
                            $('#editedHasSubcategory').attr("checked" ,"checked");
                            $('#editedHasSubcategory').val("on");
                    }
                      else{
                            $('#editedHasSubcategory').removeAttr("checked" ,"checked");
                            $('#editedHasSubcategory').val("off");
                        }                                    

                });
                   

                    $('#editbutton').click(function(event) {
                        var formData = {
                            itemcatid: $('#itemcatid').val(),
                            itemcatname: $('#editedItemcatname').val(),
                            itemcatdescription: $('#editedItemcatdescription').val(),
                            HasSubcategory: $('#editedHasSubcategory').val(),
                            itemcatcreatedby: $('#editedItemcatcreatedby').val(),
                            itemcatmodifiedby: $('#editedItemcatmodifiedby').val(),
                        };

                        $.ajax({
                            type: "POST",
                            url: config.developmentPath +
                                "/blogadmin/controller/categoryController.php",
                            data: formData,
                            dataType: "json",
                            encode: true,
                        }).done(function(data) {
                            console.log(data);
                        });
                        $('#editbutton').dispose();
                        event.preventDefault();
                    });
                    $('#deleteCategoryModal').on('show.bs.modal', function(e) {
                        var rowid = $(e.relatedTarget).data('id');
                        $('#itemcatid').val(rowid);
                    });
                    $('#deletebutton').click(function() {
                        $.ajax({
                            url: config.developmentPath + "/blogadmin/controller/categoryController.php/",
                            method: "POST",
                            data: {
                                id: $('#itemcatid').val(),
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