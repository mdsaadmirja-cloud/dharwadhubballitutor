<?php
session_start();
include('header.php');
require_once("../dblayer/facebookchatOps.php");
require_once("../model/facebookchatModel.php");
?>
<style>
    img {
        width: 100%;
    }
    .btn {
        
        width: 150px;
    }

    .card-body {
        height: 200px;
        overflow-x: hidden;        

    }
</style>
<div class="card  mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5>Facebook Chat</h5>
            </div>
        </div>
    </div>
    <div class="card" style="width: 79rem; height: 25rem;">
        <form method="post" id="form" action="../controller/facebookchatController.php" enctype="multipart/form-data">
            <div class="row ">
                <div class="card-body" style="width: 79rem; height: 25rem;">
                    <div class="col" align="right"></div>
                    <div class="row">
                        <div class="col-sm-2 p-4">
                            <h6 class="mb-0">Plugin Code</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                            <textarea type="text" name="PluginCode" id="PluginCode" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="col py-5" align="center">
                        <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>
<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $('#hidden_element').val(JSON.stringify(quill.getContents()));
            return true;
        });
    });
</script>