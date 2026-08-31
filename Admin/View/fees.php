<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/FeesOps.php";
require_once "header.php";
?>
<div class="card">
    <div class="card-header">
        <h6 class="">Fees</h6>
    </div>
    <div class="card-body">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-fees-tab" data-bs-toggle="pill" data-bs-target="#pills-fees" type="button" role="tab" aria-controls="pills-fees" aria-selected="true">Fees Details</button>
            </li>

        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-fees" role="tabpanel" aria-labelledby="pills-fees-tab">
                <table class="table table-bordered" id="enquery">
                    <thead>
                        <tr cellspacing="0">
                            <th style='display:none'> ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Total Fees (Base)</th>
                            <th>GST Collected</th>
                            <th>Total with GST</th>
                            <th>Paid (Incl GST)</th>
                            <th>Pending (Incl GST)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
echo "<tbody>";
$admissionlist = DBfees::searchadmission();
foreach ($admissionlist as $admission) {
    $status = $admission->get_Admissionstatus() == 1 ? 'Active' : 'Cancelled';
    echo "<tr>
            <td style='display:none'>" . $admission->get_admitid() . "</td>
            <td>" . $admission->get_name() . "</td>
            <td>" . $admission->get_coursesopted() . "</td>
            <td>" . $admission->get_tfees() . "</td>
            <td>" . ($admission->get_gstamount() ?? 0) . "</td>
            <td>" . ($admission->get_tfees() + ($admission->get_gstamount() ?? 0)) . "</td>
            <td>" . $admission->get_pfees() . "</td>
            <td>" . $admission->get_pendingfees() . "</td>
            <td>" . $status . "</td>
            <td>
                <div class='dropdown'>
                    <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton" . $admission->get_admitid() . "' data-bs-toggle='dropdown' aria-expanded='false'>
                        Actions
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton" . $admission->get_admitid() . "'>
                        <li>
                            <a class='dropdown-item text-warning fw-bold' href='../View/collectfees.php?id=" . $admission->get_admitid() . "'>
                                <i class='bi bi-info-circle'></i> Details
                            </a>
                        </li>
                        <li>
                            <form method='POST' action='../View/cancel.php' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $admission->get_admitid() . "' />
                                <button type='submit' class='dropdown-item text-danger fw-bold'>
                                    <i class='bi bi-x-circle'></i> Cancel
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </td>
          </tr>";
}
echo "</tbody>";
?>


                </table>
            </div>

        </div>
    </div>

</div>
<?php include_once "footer.php"; ?>
<script>
    var table = $('#enquery').DataTable({
        "order": [0, 'desc']
    });
    var addmissionList = $('#addmissionlist').DataTable();
</script>