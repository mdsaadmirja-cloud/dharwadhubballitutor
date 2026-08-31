<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/enqueryOps.php";
require_once "../../Admin/model/Coursesmodel.php";
require_once "../../Admin/DB Operations/CoursesOps.php";
require_once "header.php";
?>
<div class="card">
    <div class="card-header">
        <h6 class="">Service Details </h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="enquery">
            <thead>
                <tr cellspacing="0">

                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Services Opted</th>
                    <th> Action </th>
                </tr>
            </thead>
            <?php

            $enquirylist = DBenquery::getAllEnqueryBySection("Services");
            echo "<tbody>";
            foreach ($enquirylist as $enquiry) {
                echo "<tr>
                        <td> " . $enquiry->get_name() . "</td>
                        <td>" . $enquiry->get_email() . "</td>
                        <td>" . $enquiry->get_phone() . "</td>
                        <td>" . $enquiry->get_enqueryFor() .  "</td>
                        <td>" . '<a class="btn btn-warning" href="../View/serviceinfo.php?id=' . $enquiry->get_id() . '" role="button">View Details </a>' . '</td>
                        </tr>';
            }
            echo "</tbody>";
            ?>
        </table>
    </div>
</div>
<?php include("footer.php") ?>