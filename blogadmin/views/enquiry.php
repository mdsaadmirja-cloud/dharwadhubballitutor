<?php
include "session.php";
require_once "header.php";
require_once("../dblayer/enquiryOps.php");
require_once("../model/enquirymodel.php");
?>
<style>
    .form-check-input {
        position: static;
        margin-top: .3rem;
        margin-left: 0rem;
    }
</style>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Enquiry</h6>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <br />
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="trainings" role="tabpanel" aria-labelledby="trainings-tab">
                <div class="card">
                    <div class="card-body">
                        <table id="training" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="display:none">Id</th>
                                    <th>DOE</th>
                                    <th>Name<i class="bi bi-arrow-down-up"></i></th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th style="display:none">Qualification</th>
                                    <th>Query</th>
                                </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            $enquirylist = DBenquery::getAllEnqueryBySection("Trainings");

                            foreach ($enquirylist as $enquiry) {
                                echo "<tr><td style='display:none'> " . $enquiry->get_Id() . "</td>
                                        <td> " . $enquiry->get_enqcreatedon() . "</td>
                                         <td> " . $enquiry->get_name() . "</td>
                                        <td>" . $enquiry->get_email() . "</td>
                                        <td>" . $enquiry->get_phone() . "</td>
                                        <td style='display:none'>" . $enquiry->get_qualification() . "</td>
                                        <td>" . $enquiry->get_enqueryFor() . "</td>
                                      
                                           </tr>";
                            }
                            echo "</tbody>";
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <br />
    </div>
</div>
<?php include('footer.php'); ?>

<script>
    var table = $('#training').DataTable({
        "order": [0, 'desc']
    });
</script>