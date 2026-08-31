<?php
session_start();
include('header.php');
require_once("../dblayer/testimonialsOps.php")
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
<h1 class="h3 mb-4 text-gray-800"></h1>
<!-- DataTales Example -->
<span id="message"></span>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Testimonials</h6>
            </div>
            <div class="col" align="right">
                <span data-bs-toggle=modal data-bs-target=#postModal>
                    <button type="button" + class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </span>

            </div>
        </div>
    </div>
</div>
<?php
echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
$testimonialsList = DBtestimonials::getTestimonialsList();
foreach ($testimonialsList as $testimonials) {
    $card = '<div class="col"><div class="card shadow" style="width: 18rem;">
    <img src="../img/Post/' . $testimonials->getImage() . '" class="card-img-top" width="200" height="200" alt="' . $testimonials->getImageAlternateText() . '">
    <div class="card-body">';
    $string = strip_tags($testimonials->getDescription());
    if (strlen($string) > 150) {
        // truncate string
        $stringCut = substr($string, 0, 150);
        $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }

    $card .= '<h4 class="card-title">' . $testimonials->getName() . '</h4>' . $string . '<br>' . '<br>';

    for ($i = 1; $i <= 5; $i++) {
        if ($testimonials->getRateNow() >= $i) {
            $card .= '<i class="fa fa-star" aria-hidden="true" id="st' . $i . '" style="color:yellow"></i>';
        } else {
            $card .= '<i class="fa fa-star" aria-hidden="true" id="st' . $i . '"></i>';
        }
    }

    $card .= '</div>
  
  <div class="card-footer" align="right">
  <a class="text-center" href="editTestimonials.php?id=' . $testimonials->getId() . '" class="btn btn-Warning"><i class="fas fa-edit"></i></a>
  <a data-bs-toggle=modal data-bs-target=#deletePostModal data-id="' . $testimonials->getId() . '"class="text-center" class="btn btn-Warning"><i class="fas fa-eraser"></i></a>
  </div></div>
  </div>';
    echo $card;
}
echo '</div>';
?>
<?php include('footer.php'); ?>
<div class="modal fade" id="postModal" tabindex=-1 role=dialog aria-hidden=true>
    <div class="modal-dialog modal-xl">
        <form method="post" id="user_form" action="..\controller\testimonialsController.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Testimonials</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_message"></span>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Name <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="Name" id="Name" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Description <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea name="Description" id="Description" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Image <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="file" name="Image" id="Image" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Image Alternate Text <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="ImageAlternateText" id="ImageAlternateText" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 text-right">Rate Now<span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="hidden" name="RateNow" id="RateNow" class="form-control" value="" />
                                <i class="fa fa-star" aria-hidden="true" id="star1"></i>
                                <i class="fa fa-star" aria-hidden="true" id="star2"></i>
                                <i class="fa fa-star" aria-hidden="true" id="star3"></i>
                                <i class="fa fa-star" aria-hidden="true" id="star4"></i>
                                <i class="fa fa-star" aria-hidden="true" id="star5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="hidden" name="createdby" id="createdby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="hidden" name="Modifiedby" id="Modifiedby" class="form-control" required value="<?php echo $_SESSION['login_user']; ?>" />
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
        <form method="post" id="delete_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Delete Category</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure. Would you like to delete this Post.
                    </p>
                    <input type="hidden" name="deleteId" id="deleteId" value="">
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
        $('form').submit(function() {
            $('#hidden_element').val(JSON.stringify(quill.getContents()));
            return true;
        });


        $('#deletePostModal').on('show.bs.modal', function(e) {
            debugger;
            var rowid = $(e.relatedTarget).data('id');
            $('#deleteId').val(rowid);
        });

        $("#star1").click(function() {
            $(".fa-star").css("color", "grey");
            $("#star1").css("color", "yellow");
            $('#RateNow').val(1);
        });
        $("#star2").click(function() {
            $(".fa-star").css("color", "grey");
            $("#star1, #star2").css("color", "yellow");
            $('#RateNow').val(2);
        });
        $("#star3").click(function() {
            $(".fa-star").css("color", "grey")
            $("#star1, #star2, #star3").css("color", "yellow");
            $('#RateNow').val(3);
        });
        $("#star4").click(function() {
            $(".fa-star").css("color", "grey");
            $("#star1, #star2, #star3, #star4").css("color", "yellow");
            $('#RateNow').val(4);
        });
        $("#star5").click(function() {
            $(".fa-star").css("color", "grey");
            $("#star1, #star2, #star3, #star4, #star5").css("color", "yellow");
            $('#RateNow').val(5);
        });

        $('#deletebutton').click(function() {
            $.ajax({
                url: config.developmentPath + "/blogadmin/controller/testimonialsController.php",
                method: "POST",
                data: {
                    id: $('#deleteId').val(),
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