<?php
include "../../Admin/session.php";
include "header.php";
include "../../DB Operations/dbconnection.php";
require_once "../dblayer/PrivacyPolicyOps.php";
?>
<!-- outer Row -->
<html>
<body>
    <style type="text/css">
        body {
            color: #1a202c;
            text-align: left;
            background-color: #e2e8f0;
        }

        .main body {
            padding: 15px;
        }

        .card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #ffff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: 0.3rem;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1 rem;
        }

        .gutters-sm {
            margin-right: -8px;
            margin-left: -8px;
        }

        .gutters-sm>.col,
        .gutters-sm>[class*=col-] {
            padding-right: 8px;
            padding-left: 8px;
        }

        .mb-3 .my-3 {
            margin-bottom: 1rem !important;
        }

        .bg-gray-300 {
            background-color: #e2e8f0;
        }

        .h-100 {
            height: 100% !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        <?php
        $Privacy = DBPrivacy::getPrivacyPolicy();
        ?>
    </style>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">Privacy Policy</h6>
                </div>
                <div class="col" align="right">
                    <span data-bs-toggle=modal data-bs-target=#addModal>
                        <button type="button" + class="btn btn-warning btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                    </span>
                    <span data-bs-toggle=modal data-bs-target=#editModal>
                        <button type="button" + class="btn btn-success btn-circle btn-sm"><i class="fas fa-pencil-alt"></i></button>
                    </span>
                </div>
            </div>
        </div>


        <div class="card-body ">
            <div class="row gutters-sm">
                <div class="col-md-12">
                    <div class="form-group">
                        <h3></h3>
                        <?php
                        $Privacy = DBPrivacy::getPrivacyPolicy();
                        echo $Privacy->getdescription();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id=addModal tabindex=-1 role=dialog aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <form method="post" id="user_form" action="../controller/PrivacyPolicyController.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title">Privacy Policy</h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label></label>
                                    <div id="editor-container"></div>
                                    <input type="text" name="hidden_element" id="hidden_element" hidden />
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <button type="submit" name="register_button" id="register_button" class="btn btn-primary btn-user">Save</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php $Privacy = DBPrivacy::getPrivacyPolicy(); ?>

    <div class="modal fade" id=editModal tabindex=-1 role=dialog aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="../controller/PrivacyPolicyController.php">

                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title">Privacy Policy</h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label></label>
                                    <div id="editededitor-container">
                                        <?php echo $Privacy->getdescription() ?>
                                    </div>
                                    <input type="text" name="hidden_element" id="editedhidden_element" id="editedhidden_element" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                    </br>
                    </br>
                    </br>
                    <div class=" modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="action" id="action" value="Add" />
                        <button type="submit" name="register_button" id="register_button" class="btn btn-primary btn-user">Save</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include("footer.php"); ?>
    <script>
        $(document).ready(function() {
            $("#companybranches").change(function() {
                if (parseInt($(this).val()) > 0) {
                    $("#branch").show();
                    $("#branchaddress").show();
                }
            });
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'], // toggled buttons
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

                ['clean'] // remove formatting button
            ];
            var quill = new Quill('#editor-container', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Compose an epic...',
                theme: 'snow'
            });

            var editedquill = new Quill('#editededitor-container', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Compose an epic...',
                theme: 'snow'
            });
            $('#deleteSocialMediaModal').on('show.bs.modal', function(e) {
                var rowid = $(e.relatedTarget).data('id');
                $('#socialMediaId').val(rowid);
            });
            $('form').submit(function() {
                $('#hidden_element').val(JSON.stringify(quill.getContents()));
                $('#editedhidden_element').val(JSON.stringify(editedquill.getContents()));
                return true;
            });
        });
    </script>
</body>

</html>