<?php
include "session.php";
include('header.php');
require_once("../dblayer/subcategoryOps.php");
require_once("../model/subcategorymodel.php");
?>
<!-- DataTales Example -->
<span id="message"></span>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">SubCategory</h6>
            </div>
            <div class="col" align="right">
                <span data-bs-toggle=modal data-bs-target=#subcatModal>
                    <button type="button" + class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="subcat_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description.</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $itemsubcatlist = DBsubcategory::getAllSubCategory();
                    foreach ($itemsubcatlist as $itemsubcat) {
                        echo "<tr>
                        <td>" . $itemsubcat->getSubCategoryName() . "</td>
                        <td>" . $itemsubcat->getSubCategoryDescription() . "</td>
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
                            data-bs-target='#edititemsubcatModal' 
                            role='button' 
                            data-id='" . $itemsubcat->getSubCategoryId() . "'>
                            <i class='fas fa-user-edit'></i> 
                                Edit SubCategory
                           </button>
                           <button class='btn btn-primary dropdown-item'
                           data-bs-toggle='modal' data-bs-target='#deleteSubCategoryModal' 
                           name='delete_button' 
                           role='button' 
                           data-id='" . $itemsubcat->getSubCategoryId() . "'>
                            <i class='fas fa-trash-alt'></i>
                              Delete SubCategory
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
<div class="modal fade" id=subcatModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog modal-lg">
        <form method="post" id="user_form" action="../controller/subcategoryController.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Add SubCategory</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="itemsubcatname" id="itemsubcatname" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="itemsubcatdescription" id="itemsubcatdescription" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="itemsubcatcreatedby" id="itemsubcatcreatedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-8">
                                <input type="hidden" name="itemsubcatmodifiedby" id="itemsubcatmodifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4">Choose Categories</label>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10" id="categoryCheckBox">
                            </div>
                            <div class="col-md-1"></div>
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
        </form>
    </div>
</div>
<div class="modal fade" id=edititemsubcatModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog modal-lg">
        <form method="post" id="itemsubcat_form" action="../controller/subcategoryController.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Edit SubCategory</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="itemsubcatname" id="editeditemsubcatname" class="form-control" required />
                                <input type="hidden" name="itemsubcatid" id="itemsubcatid" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea type="text" name="itemsubcatdescription" id="editeditemsubcatdescription" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="itemsubcatcreatedby" id="editeditemsubcatcreatedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="itemsubcatmodifiedby" id="editeditemsubcatmodifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-4">Choose Categories</label>
                        </div>
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10" id="editcategoryCheckBox">
                            </div>
                            <div class="col-md-1"></div>
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
        </form>
    </div>
</div>
<div class="modal fade" id=deleteSubCategoryModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="POST" id="delete_subcategory_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Delete Sub Category</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Subcategory record.
                    </p>
                    <input type="hidden" name="itemsubcatid" id="itemsubcatid" value="">
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

        isCategorySet = false;
        $('#edititemsubcatModal').on('shown.bs.modal', function(e) {
            debugger;
            var rowid = $(e.relatedTarget).data('id');
            $('#itemsubcatid').val(rowid);
                
                var checked="";
                console.log(config.developmentPath + "/blogadmin/controller/categoryController.php/?subCatId=" + rowid);
                 $.getJSON(config.developmentPath + "/blogadmin/controller/categoryController.php/?subCatId=" + rowid, function(data) {
                     $('#editcategoryCheckBox').empty();
                     $.each(data, function(index, value) {
                        if(value.MappedSubCategory==0){
                             checked=false;
                        }else{
                             checked=true;
                        }
                        $('#editcategoryCheckBox').append(
                    $(document.createElement('div')).prop({
                        class: 'form-check form-switch form-check-inline'
                    }).append(
                        $(document.createElement('input')).prop({
                            class: 'form-check-input me-1',
                            id: 'editcategoryCheckBox',
                            name: 'category[]',
                            checked: checked,
                            value: value.CategoryId,
                            type: 'checkbox',

                        })).append(
                        $(document.createElement('label')).prop({
                            for: 'myCheckBox'
                        }).html(value.CategoryName)
                    ).append(document.createElement('br')));
                        
                    });
                });
                   
        });
        var dataTable = $('#subcat_table').DataTable({});

        $('#subcatModal').on('show.bs.modal', function(e) {
                  debugger;
            if (isCategorySet == false) {
                console.log(config.developmentPath + "/blogadmin/controller/categoryController.php");
                $.getJSON(config.developmentPath + "/blogadmin/controller/categoryController.php", function(data) {
                    
                    $.each(data, function(index, value) {
                        $('#categoryCheckBox').append($(document.createElement('div')).prop({
                            class: "form-check form-switch form-check-inline"
                        }));
                        $('#categoryCheckBox div:last').append($(document.createElement('input')).prop({
                            class: "form-check-input",
                            type: "checkbox",
                            name: "category[]",
                            value: value.CategoryId
                        }));
                        $('#categoryCheckBox div:last').append($(document.createElement('label')).prop({
                            class: "form-check-label",
                            for: "flexSwitchCheckDefault",
                            innerHTML: value.CategoryName
                        }));
                    });
                });
                isCategorySet = true;
            }
        });

        var nEditing = null;

        $('#subcat_table tbody').on('click', 'tr', function() {
            /* Get the row as a parent of the link that was clicked on */

            $('#editeditemsubcatname').val(this.cells[0].innerHTML);
            $('#editeditemsubcatdescription').val(this.cells[1].innerHTML);

        });
       

        var url = config.developmentPath + "/blogadmin/controller/item_categorycontroller.php";

        $('#deleteSubCategoryModal').on('show.bs.modal', function(e) {

            var rowid = $(e.relatedTarget).data('id');
            $('#itemsubcatid').val(rowid);
        });
        $('#deletebutton').click(function() {

            $.ajax({
                url: config.developmentPath + "/blogadmin/controller/subcategoryController.php",
                method: "POST",
                data: {
                    id: $('#itemsubcatid').val(),
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