<?php
session_start();
include('header.php');
require_once("../dblayer/postOps.php");
$postId = $_GET['id'];
$post = DBpost::getPostById($postId);
?>

<div id="main-content" class="card shadow">
    <div class="card-header">Edit Post</div>
    <div class="card-body">
        <form method="post" id="user_form" action="../controller/postController.php" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Post Title <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="title" id="title" class="form-control" required value="<?php echo $post->getPostTitle(); ?>" />
                        <input type="text" name="postId" id="postId" value="<?php echo $post->getPostId(); ?>" hidden />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Title Tag <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="titleTag" id="titleTag" class="form-control" value="<?php echo $post->getPostTitle(); ?>" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Post URL <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="postURL" id="postURL" class="form-control" required value="<?php echo $post->getPostUrl(); ?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Keywords <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="Keywords" id="Keywords" class="form-control" required value="<?php echo $post->getKeywords(); ?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Image <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <?php
                        echo '<img src="../img/Post/' . $post->getImage() . '" class="thumbnail img-fluid" alt="' . $post->getAltTextImage() . '">'
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Image <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="file" name="image" id="image" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Image Alternate Text <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="alttext" id="alttext" class="form-control" required value="<?php echo $post->getAltTextImage(); ?>" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right"> Link Under <span class="text-danger">*</span>
                </label>
                    <div class="col-md-10">
                        <select id="LinkUnder" class="form-select" required name="LinkUnder">
                            <!-- <option value="1" >Category</option> -->
                            <option value="2">Sub Category</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="form-check form-check-inline">
                        <div class="col-md-2">
                            <label class="form-check-label" for="onHome">Display On Home Page</label>
                        </div>
                        <div class="col-md-10">
                            <input type="hidden" name="onHome" value="0">
                            <input class="form-check-input" type="checkbox" id="onHome" name="onHome" <?php echo $post->getOnHome() == 0 ? "" :  "checked"; ?> value='<?php echo $post->getOnHome() == 0 ? 0 :  1; ?>'>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Post Content <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <div id="editor-container">
                            <?php echo $post->getPostDescription(); ?>
                        </div>
                        <input type="text" name="hidden_element" id="hidden_element" hidden />
                    </div>
                </div>
            </div>

            <!-- <div class="form-group" id="category">
                <div class="row">
                    <label class="col-md-4" >Map to Categories</label>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10" id="categoryCheckBox">
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div> -->

            <div class="form-group" id="subcategory">
                <div class="row">
                    <label class="col-md-4">Map to Sub Categories</label>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10" id="subcategoryCheckBox">
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-10">
                        <input type="hidden" name="postcreatedby" id="postcreatedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-10">
                        <input type="hidden" name="postmodifiedby" id="postmodifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                    </div>
                </div>
            </div>
    </div>

    <div class="card-footer" align="right">

        <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Save" />
        <a href="../views/post.php" class="btn btn-secondary" role="button">Back</a>
        </form>
    </div>

</div>

<?php include('footer.php'); ?>

<script>
    $(document).ready(function() {
    
        //  $('#subcategory').hide();
        
         $('#categoryCheckBox').click(function(){            
             $('#categoryCheckBox').attr("checked", "checked");
         });
        
        $('#onHome').change(function() {
            if (this.checked) {
                $('#onHome').val(1)
            } else {
                $('#onHome').removeAttr('checked');
                $('#onHome').val(0)
            }
        })
        

       
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike','image'], // toggled buttons
            ['blockquote', 'code-block'],

            [{
                'header': 1
            }, {
                'header': 2
            }], // custom button values
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'script': 'sub'
            }, {
                'script': 'super'
            }], // superscript/subscript
            [{
                'indent': '-1'
            }, {
                'indent': '+1'
            }], // outdent/indent
            [{
                'direction': 'rtl'
            }], // text direction

            [{
                'size': ['small', false, 'large', 'huge']
            }], // custom dropdown
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],

            [{
                'color': []
            }, {
                'background': []
            }], // dropdown with defaults from theme
            [{
                'font': []
            }],
            [{
                'align': []
            }],
             ['link', 'image'],

            ['clean'] // remove formatting button
        ];
        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: toolbarOptions,
                
            },
            placeholder: 'Compose an epic...',
            theme: 'snow'
        });
        $('form').submit(function() {
            debugger;
            console.log(JSON.stringify(quill.getContents()));
            $('#hidden_element').val(JSON.stringify(quill.getContents()));
            return true;
        });

       
        //$('#postModal').on('show.bs.modal', function(e) {
            //  isSubCategorySet = false;
            // if (isSubCategorySet == false) {
            //     debugger;
            //     console.log(config.developmentPath + "/blogadmin/controller/categoryController.php?postId="+$('#postId').val());
            //     $.getJSON(config.developmentPath + "/blogadmin/controller/categoryController.php?postId="+$('#postId').val(), function(data) {
            //         console.log(data);
            //         $.each(data, function(index, value) {
            //             if (value.mappedPost == 0) {
            //                 checked = false;
            //             } else {
            //                 checked = true;
            //             }
            //             $('#categoryCheckBox').append($(document.createElement('div')).prop({
            //                 class: "form-check form-switch form-check-inline"
            //             }));
            //             $('#categoryCheckBox div:last').append($(document.createElement('input')).prop({
            //                 class: "form-check-input",
            //                 type: "checkbox",
            //                 name: "category[]",
            //                 value: value.itemcatid,
            //                 checked: checked
            //             }));
            //             $('#categoryCheckBox div:last').append($(document.createElement('label')).prop({
            //                 class: "form-check-label",
            //                 for: "flexSwitchCheckDefault",
            //                 innerHTML: value.itemcatname
                           
            //             }));
            //         });
            //     });
            //     isSubCategorySet = true;
            // }


            isCategorySet = false;
            if (isCategorySet == false) {
                $.getJSON(config.developmentPath + "/blogadmin/controller/subcategoryController.php?subCatId=" + $('#postId').val(), function(data) {
                    $.each(data, function(index, value) {
                        if (value.mappedPost == 0) {
                            checked = false;
                        } else {
                            checked = true;
                        }
                        $('#subcategoryCheckBox').append($(document.createElement('div')).prop({
                            class: "form-check form-switch form-check-inline"
                        }));
                        $('#subcategoryCheckBox div:last').append($(document.createElement('input')).prop({
                            class: "form-check-input",
                            type: "checkbox",
                            name: "category[]",
                            checked: checked,
                            value: value.itemsubcatid
                        }));
                        $('#subcategoryCheckBox div:last').append($(document.createElement('label')).prop({
                            class: "form-check-label",
                            for: "flexSwitchCheckDefault",
                            innerHTML: value.itemsubcatname
                        }));
                    });
                });
                isCategorySet = true;
            }

       
           
        $('#LinkUnder').change(function() {
            debugger;
            if ($('#LinkUnder').val() == 'Category') {

                $('#subcategory').hide();
                $('#category').show();

            } else {
                $('#category').hide();
                $('#subcategory').show();
            }

        })
    })

    

</script>