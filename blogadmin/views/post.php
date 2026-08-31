<?php
session_start();
include('header.php');
require_once("../dblayer/postOps.php")
?>
<style>
img {
    width: 100%;
}

.card-body {
    height: 200;
    overflow-y: hidden;

}
</style>

<h1 class="h3 mb-4 text-gray-800"></h1>
<!-- DataTales Example -->
<span id="message"></span>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Post</h6>
            </div>
            <div class="col" align="right">
                <span data-bs-toggle=modal data-bs-target=#postModal>
                    <button type="button" + class="btn btn-success btn-circle btn-sm"><i
                            class="fas fa-plus"></i></button>
                </span>

            </div>
        </div>
    </div>
</div>
<?php
echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
$postList = DBpost::getPostList();
foreach ($postList as $post) {
    $card = '<div class="col">
    <div class="card shadow" style="width: 18rem;">
    <div id="main">
  <img src="../img/Post/' . $post->getImage() . '" class="card-img-top" alt="' . $post->getAltTextImage() . '" width="" height="">
  </div>
  <div class="card-body">';
    foreach ($post->getMappedSubCategory() as $subcategory) {
        $card .= '<span class="badge bg-primary">' . $subcategory->getSubCategoryName() . '</span>';
    }
    $string = strip_tags($post->getPostDescription());
    if (strlen($string) > 100) {
        // truncate string
        $stringCut = substr($string, 0, 100);
        $endPoint = strrpos($stringCut, ' ');

        $string = strip_tags($post->getPostDescription());
    };
    if (strlen($string) > 50) {

          // truncate string
          $stringCut = substr($string, 0, 50);
          $endPoint = strrpos($stringCut, ' ');

          //if the string doesn't contain any space then it will cut without word basis.
          $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
          $string .= '...';
    }
   

    $card .= '<h5 class="card-title">' . $post->getPostTitle() . '</h5>' . $string .  '
    </div>

  <div class="card-footer" align="right">
  <a class="text-center" href="postDetails.php?id=' . $post->getPostId() . '" class="btn btn-primary"><i class="fas fa-info-circle"></i></a>
  <a class="text-center" href="editPost.php?id=' . $post->getPostId() . '" class="btn btn-Warning"><i class="fas fa-edit"></i></a>
  <a data-bs-toggle=modal data-bs-target=#deletePostModal data-id="' . $post->getPostId() . '"class="text-center" class="btn btn-Warning"><i class="fas fa-eraser"></i></a>
  
  </div>
</div>
</div>';
    echo $card;
}
echo '</div>';
?>

<?php include('footer.php'); ?>
<div class="modal fade" id="postModal" tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog modal-xl">
        <form method="post" id="user_form" action="../controller/postController.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Post</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Post Title <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="title" id="title" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Title Tag <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="titleTag" id="titleTag" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Post URL <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="postURL" id="postURL" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Keywords <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="Keywords" id="Keywords" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Image <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="file" name="image" id="image" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Image Alternate Text <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="alttext" id="alttext" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Link Under<span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select id="LinkUnder" class="form-select" required name="LinkUnder">
                                    <option value="1">Category</option>
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
                                    <input class="form-check-input" type="checkbox" id="onHome" name="onHome">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Post Content <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <div id="editor-container"></div>
                                <input type="text" name="hidden_element" id="hidden_element" hidden />
                            </div>
                        </div>
                    </div>
                   

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
                                <input type="hidden" name="postcreatedby" id="postcreatedby" class="form-control"
                                    required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="hidden" name="postmodifiedby" id="postmodifiedby" class="form-control"
                                    required value="<?php echo $_SESSION['login_user']; ?>" />
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
<div class="modal fade" id=deletePostModal tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog">
        <form method="POST" id="delete_post_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Delete Category</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Post.
                    </p>
                    <input type="hidden" name="deletepostId" id="deletepostId" value="">
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
    debugger;
    // $('#subcategory').hide();
    $('#categoryCheckBox').click(function() {
        $('#categoryCheckBox').attr("checked", "checked");
    });

    $('#onHome').change(function() {
        debugger;
        if (this.checked) {
            $('#onHome').val(1)
        } else {
            $('#onHome').val(0)
        }
    });
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike', 'image'], // toggled buttons
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
        [['link', 'image']],

        ['clean'] // remove formatting button
    ];
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: toolbarOptions,

        },
        placeholder: 'Compose an epic...',
        theme: 'snow',

    });
    $('form').submit(function() {
        console.log(JSON.stringify(quill.getContents()));
        $('#hidden_element').val(JSON.stringify(quill.getContents()));
        return true;
    });





    $('#postModal').on('show.bs.modal', function(e) {
        isCategorySet = false;
        if (isCategorySet == false) {
            
            $.getJSON(config.developmentPath + "/blogadmin/controller/subcategoryController.php?",
                function(data) {
                    $('#subcategoryCheckBox').empty();
                    $.each(data, function(index, value) {
                        $('#subcategoryCheckBox').append($(document.createElement('div'))
                            .prop({
                                class: "form-check form-switch form-check-inline"
                            }));
                        $('#subcategoryCheckBox div:last').append($(document.createElement(
                            'input')).prop({
                            class: "form-check-input",
                            type: "checkbox",
                            name: "category[]",
                            value: value.itemsubcatid
                        }));
                        $('#subcategoryCheckBox div:last').append($(document.createElement(
                            'label')).prop({
                            class: "form-check-label",
                            for: "flexSwitchCheckDefault",
                            innerHTML: value.itemsubcatname
                        }));
                    });
                });
            isCategorySet = true;
        }
    });

    $('#deletePostModal').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id');
        $('#deletepostId').val(rowid);
    });
    $('#deletebutton').click(function() {
        $.ajax({
            url: config.developmentPath + "/blogadmin/controller/postController.php",
            method: "POST",
            data: {
                id: $('#deletepostId').val(),
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


    $('#LinkUnder').change(function() {
        debugger;
        if ($('#LinkUnder').val() == 'Category') {
            $('#subcategory').hide();
            $('#category').show();

        } else {
            $('#category').hide();
            $('#subcategory').show();
        }

    });

    $('#LinkUnder').change(function() {
        debugger;
        if ($('#LinkUnder').val() == 'Category') {

            $('#subcategory').hide();
            $('#category').show();

        } else {
            $('#category').hide();
            $('#subcategory').show();
        }

    });

});
</script>