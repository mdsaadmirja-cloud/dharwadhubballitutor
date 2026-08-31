<?php
session_start();
include('header.php');
require_once("../dblayer/testimonialsOps.php");
$Id = $_GET['id'];
$testimonials = DBtestimonials::getId($Id);
?>
<div id="main-content" class="card shadow">
    <div class="card-header">Edit Testimonials</div>
    <div class="card-body">
        <form method="post" id="user_form" action="../controller/testimonialsController.php" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Name <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="Name" id="Name" class="form-control" required value="<?php echo $testimonials->getName(); ?>" />
                        <input type="text" name="Id" id="Id" value="<?php echo $testimonials->getId(); ?>" hidden />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Description<span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <textarea name="Description" id="Description" class="form-control"><?php echo $testimonials->getDescription(); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Image <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="file" name="Image" id="Image" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Image Alternate Text <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" name="ImageAlternateText" id="ImageAlternateText" class="form-control" required value="<?php echo $testimonials->getImageAlternateText(); ?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 text-right">Rate Now<span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select id="RateNow" name="RateNow">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($testimonials->getRateNow() == $i) {
                                    echo '<option value=' . $i . ' selected>' . $i . '</>';
                                } else {
                                    echo '<option value=' . $i . ' >' . $i . '</>';
                                }
                            }
                            ?>
                        </select>
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            if ($testimonials->getRateNow() >= $i) {
                                echo  '<i class="fa fa-star" aria-hidden="true" id="st' . $i . '" style="color:yellow"></i>';
                            } else {
                                echo  '<i class="fa fa-star" aria-hidden="true" id="st' . $i . '"></i>';
                            }
                        }
                        ?>
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
            <div class="card-footer" align="right">

                <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Save" />
                <a href="../views/Testimonials.php" class="btn btn-secondary" role="button">Back</a>
            </div>
        </form>
    </div>
</div>
<?php include('footer.php'); ?>

<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $('#hidden').val(JSON.stringify(quill.getContents()));
            return true;
        });
    })

</script>