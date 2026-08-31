<?php
require "../session.php";
include "../../Admin/DB Operations/enqueryOps.php";
require_once "../../Admin/model/Coursesmodel.php";
require_once "../../Admin/DB Operations/CoursesOps.php";
include "../../Admin/DB Operations/followupOps.php";

require_once "../../Admin/model/ChatbotLeadModel.php";
require_once "../../Admin/DB Operations/ChatbotLeadOps.php";

// Ensure required columns exist
DBchatbotlead::ensureSchema();

// Fetch chatbot leads
$chatbotLeads = DBchatbotlead::getAllLeads();
$today = date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('monday this week'));

$chatbotStats = [
    'total'      => 0,
    'today'      => 0,
    'week'       => 0,
    'converted'  => 0,
    'followup'   => 0,
    'spam'       => 0,
];

foreach ($chatbotLeads as $lead) {

    $chatbotStats['total']++;

    $created = substr($lead->get_createdOn(), 0, 10);

    if ($created == $today)
        $chatbotStats['today']++;

    if ($created >= $weekStart)
        $chatbotStats['week']++;

    switch ($lead->get_Status()) {

        case 'converted':
            $chatbotStats['converted']++;
            break;

        case 'followup':
            $chatbotStats['followup']++;
            break;

        case 'spam':
            $chatbotStats['spam']++;
            break;
    }
}

require_once "header.php";
$enquirylist = DBenquery::getAllEnquery();
// Count enquiries by status
$enquiriesCountByStatus = [];
foreach ($enquirylist as $enquiry) {
    $status = $enquiry->get_Status();
    if (!isset($enquiriesCountByStatus[$status])) {
        $enquiriesCountByStatus[$status] = 0;
    }
    $enquiriesCountByStatus[$status]++;
}
?>
<style>
    td.details-control {
        background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png') no-repeat center center;
    }

    .followup-comments {
        max-width: 500px;
        white-space: normal !important;
        word-wrap: break-word;
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.5;
    }
</style>
<div class="mb-3">
    <h5>Enquiry Count by Status</h5>
    <div class="row justify-content-center">
        <?php foreach ($enquiriesCountByStatus as $status => $count): ?>
            <div class="col-md-2 mb-3">
                <div class="card shadow-lg  h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-2 text-primary" style="font-weight:700; letter-spacing:1px;"><?php echo htmlspecialchars($status); ?></h5>
                        <span class="badge bg-primary text-white fs-4 px-4 py-3 rounded-pill" style="font-size:2rem; box-shadow:0 0 10px #0d6efd;"><?php echo $count; ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h6 class="">Enquiries</h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="trainings-tab" data-toggle="tab" data-target="#trainings-tab-content" type="button" role="tab" aria-controls="trainings" aria-selected="true"><b>Trainings</b></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                    id="chatbot-tab"
                    data-toggle="tab"
                    data-target="#chatbot-tab-content"
                    type="button"
                    role="tab">

                    <b>Chatbot Leads</b>

                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="internship-tab" data-toggle="tab" data-target="#Internship-tab-content" type="button" role="tab" aria-controls="internship" aria-selected="false"><b>Internship</b></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="democlass-tab" data-toggle="tab" data-target="#democlass-tab-content" type="button" role="tab" aria-controls="democlass" aria-selected="false"><b>Demo
                        Class</b></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-toggle="tab" data-target="#services-tab-content" type="button" role="tab" aria-controls="services" aria-selected="false"><b>Services</b></button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="followup-tab" data-toggle="tab" data-target="#followup-tab-content" type="button" role="tab" aria-controls="followup" aria-selected="false"><b>FollowUp</b></button>
            </li>

            <li class="nav-item " role="presentation">
                <button class="nav-link " id="enquiry-tab" data-toggle="tab" data-target="#enquiry" type="button" role="tab" aria-controls="enquiry" aria-selected="false"><b>Add
                        Enquiry</b></button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active table-responsive" id="trainings-tab-content" role="tabpanel" aria-labelledby="trainings-tab">
                <table id="training" class="display table table-bordered ">
                    <thead>
                        <tr>
                            <th class="details-control"></th>
                            <th style="display:none">Id</th>
                            <th>DOE</th>
                            <th>FupD</th>
                            <th>Name<i class="bi bi-arrow-down-up"></i></th>
                            <th style="display:none">Email</th>
                            <th>Phone</th>
                            <th style="display:none">Qualification</th>
                            <th>Status</th>
                            <th>Branch</th>
                            <th style="display:none">Source</th>
                            <th>Trainings</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $enquirylist = DBenquery::getAllEnquery();
                    foreach ($enquirylist as $enquiry) {
                        if ($enquiry->get_followupDate() && $enquiry->get_followupDate() != '00-00-0000') {

                            if (date_diff(date_create(date('d-m-Y', strtotime($enquiry->get_followupDate()))), date_create(date("d-m-Y")))->format("%R%a") > 0) {
                                $rowClass = "table-danger";
                            } else {
                                $rowClass = " ";
                            }
                        } else {
                            $rowClass = " ";
                        }
                        echo "<tr class=" . $rowClass . ">
                        <td class='details-control'></td>
                        <td style=display:none> " . $enquiry->get_id() . "</td>
                        
                        <td> " . $enquiry->get_enqcreatedon() . "</td>
                        <td> " . $enquiry->get_followupDate() . "</td>
                        <td> " . $enquiry->get_name() . "</td>
                        <td style=display:none>" . $enquiry->get_email() . "</td>
                        <td>" . $enquiry->get_phone() . "</td>
                        <td style=display:none>" . $enquiry->get_qualification() . "</td>
                        <td>" . $enquiry->get_Status() . "</td>
                        <td>" . $enquiry->getBranch() . "</td>    
                        <td style=display:none>" . $enquiry->get_Source() . "  </td>
                        <td>" . $enquiry->get_enqueryFor() . "</td>          
                                <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenu2' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-info-circle'></i>
                                        </button>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenu2'>
                                        <a class='btn  dropdown-item' role='button' href='editenquiry.php?id=" . $enquiry->get_id() . "'> 
                                            <i class='fas fa-info'></i>
                                            Edit Enquiry
                                        </a>
                                    </div> 
                                    </div>
                                </td></tr>";
                    }
                    echo  "</tbody>";
                    ?>
                </table>
            </div>
            <div class="tab-pane fade" id="chatbot-tab-content" role="tabpanel" aria-labelledby="chatbot-tab">

                <!-- Statistics -->
                <div class="row mb-4">

                    <div class="col-md-2">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h6>Total</h6>
                                <h3><?= $chatbotStats['total']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h6>Today</h6>
                                <h3><?= $chatbotStats['today']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h6>This Week</h6>
                                <h3><?= $chatbotStats['week']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h6>Converted</h6>
                                <h3><?= $chatbotStats['converted']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h6>Follow-up</h6>
                                <h3><?= $chatbotStats['followup']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h6>Spam</h6>
                                <h3><?= $chatbotStats['spam']; ?></h3>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Filters -->

                <div class="row mb-3">

                    <div class="col-md-4">
                        <input type="text"
                            id="chatbotSearch"
                            class="form-control"
                            placeholder="Search Name / Phone / Interest">
                    </div>

                    <div class="col-md-3">
                        <select id="chatbotStatus" class="form-control">
                            <option value="">All Status</option>
                            <option>new</option>
                            <option>followup</option>
                            <option>converted</option>
                            <option>spam</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text"
                            id="chatbotInterest"
                            class="form-control"
                            placeholder="Interest">
                    </div>

                    <div class="col-md-2">
                        <input type="date"
                            id="chatbotDate"
                            class="form-control">
                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-striped"
                        id="chatbotTable">

                        <thead>

                            <tr>

                                <th>Name</th>

                                <th>Phone</th>

                                <th>Interest</th>

                                <th>Source</th>

                                <th>Date</th>

                                <th>Status</th>

                                <th width="150">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($chatbotLeads as $lead): ?>

                                <tr>

                                    <td><?= htmlspecialchars($lead->get_name()); ?></td>

                                    <td><?= htmlspecialchars($lead->get_phone()); ?></td>

                                    <td><?= htmlspecialchars($lead->get_interest()); ?></td>

                                    <td><?= htmlspecialchars($lead->get_Source()); ?></td>

                                    <td data-order="<?= date('Y-m-d', strtotime($lead->get_createdOn())); ?>">
                                        <?= date('d-m-Y', strtotime($lead->get_createdOn())); ?>
                                    </td>

                                    <td>

                                        <?php

                                        switch ($lead->get_Status()) {

                                            case 'converted':
                                                echo '<span class="badge bg-success">Converted</span>';
                                                break;

                                            case 'followup':
                                                echo '<span class="badge bg-warning text-dark">Follow-up</span>';
                                                break;

                                            case 'spam':
                                                echo '<span class="badge bg-danger">Spam</span>';
                                                break;

                                            default:
                                                echo '<span class="badge bg-primary">New</span>';
                                        }

                                        ?>

                                    </td>

                                    <td>

                                        <div class="dropdown">

                                            <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown">

                                                <i class="fas fa-info-circle"></i>

                                            </button>

                                            <div class="dropdown-menu">

                                                <a class="dropdown-item chatbot-view"
                                                    href="#"

                                                    data-id="<?= $lead->get_id(); ?>"
                                                    data-name="<?= htmlspecialchars($lead->get_name()); ?>"
                                                    data-phone="<?= htmlspecialchars($lead->get_phone()); ?>"
                                                    data-interest="<?= htmlspecialchars($lead->get_interest()); ?>"
                                                    data-source="<?= htmlspecialchars($lead->get_Source()); ?>"
                                                    data-status="<?= htmlspecialchars($lead->get_Status()); ?>"
                                                    data-notes="<?= htmlspecialchars($lead->get_notes()); ?>"
                                                    data-created="<?= htmlspecialchars($lead->get_createdOn()); ?>">

                                                    👁 View

                                                </a>

                                                <a class="dropdown-item chatbot-edit"
                                                    href="#"

                                                    data-id="<?= $lead->get_id(); ?>"
                                                    data-name="<?= htmlspecialchars($lead->get_name()); ?>"
                                                    data-phone="<?= htmlspecialchars($lead->get_phone()); ?>"
                                                    data-interest="<?= htmlspecialchars($lead->get_interest()); ?>">

                                                    ✏ Edit

                                                </a>
                                                <?php if ($lead->get_phoneDigits()): ?>

                                                    <a class="dropdown-item"
                                                        target="_blank"
                                                        href="https://wa.me/91<?= $lead->get_phoneDigits(); ?>">

                                                        💬 WhatsApp

                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="tel:<?= $lead->get_phoneDigits(); ?>">

                                                        📞 Call

                                                    </a>

                                                <?php endif; ?>
                                                <?php if ($lead->get_Status() != 'converted'): ?>

                                                    <a class="dropdown-item text-success"
                                                        href="../Controller/chatbotlead-actions.php?action=move_to_enquiry&id=<?= $lead->get_id(); ?>"
                                                        onclick="return confirm('Move this lead to Enquiry?');">

                                                        <i class="fas fa-share"></i>
                                                        Move to Enquiry

                                                    </a>

                                                <?php else: ?>

                                                    <span class="dropdown-item text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                        Already Moved
                                                    </span>

                                                <?php endif; ?>

                                                <a class="dropdown-item text-danger"
                                                    href="../Controller/chatbotlead-actions.php?action=delete_lead&id=<?= $lead->get_id(); ?>"
                                                    onclick="return confirm('Delete this lead?');">

                                                    🗑 Delete

                                                </a>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>
            <div class="tab-pane fade table-responsive" id="Internship-tab-content" role="tabpanel" aria-labelledby="internship-tab">
                <table id="Internship" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th style="display:none">Id</th>
                            <th>DOE</th>
                            <th>Followup Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th style="display:none">Qualification</th>
                            <th>Internship</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $enquirylist = DBenquery::getAllEnqueryBySection("Internship");
                    foreach ($enquirylist as $enquiry) {
                        if ($enquiry->get_followupDate() && $enquiry->get_followupDate() != '0000-00-00 00:00:00') {

                            if (date_diff(date_create(date('d-m-Y', strtotime($enquiry->get_followupDate()))), date_create(date("d-m-Y")))->format("%R%a") > 0) {
                                $rowClass = "table-danger";
                            } else {
                                $rowClass = "";
                            }
                        } else {
                            $rowClass = "";
                        }
                        echo "<tr class=" . $rowClass . ">
                        <td style=display:none> " . $enquiry->get_id() . "</td>
                        <td> " . $enquiry->get_enqcreatedon() . "</td>
                        <td> " . $enquiry->get_followupDate() . "</td>
                        <td> " . $enquiry->get_name() . "</td>
                        <td>" . $enquiry->get_email() . "</td>
                        <td>" . $enquiry->get_phone() . "</td>
                        <td style=display:none>" . $enquiry->get_qualification() . "</td>
                        <td>" . $enquiry->get_enqueryFor() . "</td>          
                        <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenu2' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-info-circle'></i>
                                        </button>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenu2'>
        
                                        <a class='btn  dropdown-item'  role='button' href='followup.php?id=" . $enquiry->get_id() . "'> 
                                            <i class='fas fa-comment-dots'></i>
                                            Follow Up
                                        </a>
                                        <a class='btn  dropdown-item' role='button' href='editenquiry.php?id=" . $enquiry->get_id() . "  &name=  " . $enquiry->get_name() . "  &email= " . $enquiry->get_email() . " &phone=  " . $enquiry->get_phone() . "''> 
                                            <i class='fas fa-info'></i>
                                            Edit Enquiry
                                        </a>
                                    </div> 
                                    </div>
                                </td></tr>";
                    }
                    echo  "</tbody>";
                    ?>
                </table>
            </div>
            <div class="tab-pane fade" id="democlass-tab-content" role="tabpanel" aria-labelledby="democlass-tab">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="Tabledemoclass">
                            <thead>
                                <tr>
                                    <th style="display:none">Id</th>
                                    <th>Created Date</th>
                                    <th>Followup Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Enquired For</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            echo  "<tbody>";
                            $enquirylist = DBenquery::getAllEnqueryBySection("Demo");
                            foreach ($enquirylist as $enquiry) {
                                if ($enquiry->get_followupDate() && $enquiry->get_followupDate() != '0000-00-00 00:00:00') {

                                    if (date_diff(date_create(date('d-m-Y', strtotime($enquiry->get_followupDate()))), date_create(date("d-m-Y")))->format("%R%a") > 0) {
                                        $rowClass = "table-danger";
                                    } else {
                                        $rowClass = " ";
                                    }
                                } else {
                                    $rowClass = " ";
                                }
                                echo "<tr class=" . $rowClass . ">
                                <td style=display:none> " . $enquiry->get_id() . "</td>
                        <td> " . $enquiry->get_enqcreatedon() . "</td>
                        <td> " . $enquiry->get_followupDate() . "</td>
                        <td> " . $enquiry->get_name() . "</td>
                        <td>" . $enquiry->get_email() . "</td>
                        <td>" . $enquiry->get_phone() . "</td>
                    
                        <td>" . $enquiry->get_enqueryFor() . "</td>          
                        <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenu2' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-info-circle'></i>
                                        </button>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenu2'>
        
                                        <a class='btn  dropdown-item'  role='button' href='followup.php?id=" . $enquiry->get_id() . "'> 
                                            <i class='fas fa-comment-dots'></i>
                                            Follow Up
                                        </a>
                                        <a class='btn  dropdown-item' role='button' href='editenquiry.php?id=" . $enquiry->get_id() . "  &name=  " . $enquiry->get_name() . "  &email= " . $enquiry->get_email() . " &phone=  " . $enquiry->get_phone() . "''> 
                                            <i class='fas fa-info'></i>
                                            Edit Enquiry
                                        </a>
                                    </div> 
                                    </div>
                                </td></tr>";
                            }
                            echo  "</tbody>";
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="services-tab-content" role="tabpanel" aria-labelledby="services-tab">
                <table class="table table-bordered " id="Services">
                    <thead>
                        <tr>
                            <th style="display:none">Id</th>
                            <th>DOE</th>
                            <th>followupDate</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Services</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $enquirylist = DBenquery::getAllEnqueryBySection("Services");
                    foreach ($enquirylist as $enquiry) {
                        if ($enquiry->get_followupDate() && $enquiry->get_followupDate() != '0000-00-00 00:00:00') {

                            if (date_diff(date_create(date('d-m-Y', strtotime($enquiry->get_followupDate()))), date_create(date("d-m-Y")))->format("%R%a") > 0) {
                                $rowClass = "table-danger";
                            } else {
                                $rowClass = " ";
                            }
                        } else {
                            $rowClass = " ";
                        }
                        echo "<tr class=" . $rowClass . ">
                        <td style=display:none> " . $enquiry->get_id() . "</td>
                        <td> " . $enquiry->get_enqcreatedon() . "</td>
                        <td> " . $enquiry->get_followupDate() . "</td>
                        <td> " . $enquiry->get_name() . "</td>
                        <td>" . $enquiry->get_email() . "</td>
                        <td>" . $enquiry->get_phone() . "</td>
                       
                        <td>" . $enquiry->get_enqueryFor() . "</td>          
                                <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenu2' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-info-circle'></i>
                                        </button>
                                    <div class='dropdown-menu' aria-labelledby='dropdownMenu2'>
        
                                        <a class='btn  dropdown-item'  role='button' href='followup.php?id=" . $enquiry->get_id() . "'> 
                                            <i class='fas fa-comment-dots'></i>
                                            Follow Up
                                        </a>
                                       <a class='btn  dropdown-item' role='button' href='editenquiry.php?id=" . $enquiry->get_id() . "  &name=  " . $enquiry->get_name() . "  &email= " . $enquiry->get_email() . " &phone=  " . $enquiry->get_phone() . "''> 
                                            <i class='fas fa-info'></i>
                                            Edit Enquiry
                                        </a>
                                    </div> 
                                    </div>
                                </td></tr>";
                    }
                    echo  "</tbody>";
                    ?>

                </table>
            </div>
            <div class="tab-pane fade table-responsive" id="followup-tab-content" role="tabpanel" aria-labelledby="followup-tab">

                <table class="table table-bordered w-100" id="TableFollowup">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="display:none">Id</th>
                            <th>DOE</th>
                            <th>FupD</th>
                            <th>Name<i class="bi bi-arrow-down-up"></i></th>
                            <th style="display:none">Email</th>
                            <th>Phone</th>
                            <th style="display:none">Qualification</th>
                            <th>Status</th>
                            <th>Branch</th>
                            <th style="display:none">Source</th>
                            <th>Trainings</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                    echo  "<tbody>";
                    $enquirylist = DBenquery::getTodaysFollowUps();
                    foreach ($enquirylist as $enquiry) {
                        if ($enquiry->get_followupDate() && $enquiry->get_followupDate() != '00-00-0000') {

                            if (date_diff(date_create(date('d-m-Y', strtotime($enquiry->get_followupDate()))), date_create(date("d-m-Y")))->format("%R%a") > 0) {
                                $rowClass = "table-danger";
                            } else {
                                $rowClass = " ";
                            }
                        } else {
                            $rowClass = " ";
                        }
                        echo "<tr class=" . $rowClass . ">
                                <td class='details-control'></td>
                                <td style=display:none> " . $enquiry->get_id() . "</td>
                                
                                <td> " . $enquiry->get_enqcreatedon() . "</td>
                                <td> " . $enquiry->get_followupDate() . "</td>
                                <td> " . $enquiry->get_name() . "</td>
                                <td style=display:none>" . $enquiry->get_email() . "</td>
                                <td>" . $enquiry->get_phone() . "</td>
                                <td style=display:none>" . $enquiry->get_qualification() . "</td>
                                <td>" . $enquiry->get_Status() . "</td>
                                <td>" . $enquiry->getBranch() . "</td>    
                                <td style=display:none>" . $enquiry->get_Source() . "  </td>
                                <td>" . $enquiry->get_enqueryFor() . "</td>          
                                        <td>
                                            <div class='dropdown'>
                                                <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenu2' data-bs-toggle='dropdown' aria-expanded='false'>
                                                <i class='fas fa-info-circle'></i>
                                                </button>
                                            <div class='dropdown-menu' aria-labelledby='dropdownMenu2'>
                                                <a class='btn  dropdown-item' role='button' href='editenquiry.php?id=" . $enquiry->get_id() . "'> 
                                                    <i class='fas fa-info'></i>
                                                    Edit Enquiry
                                                </a>
                                            </div> 
                                            </div>
                                        </td></tr>";
                    }
                    echo  "</tbody>";
                    ?>
                </table>

            </div>
            <div class="tab-pane fade" id="enquiry" role="tabpanel" aria-labelledby="enquiry-tab">

                <form class="form-horizontal" id="addenquiry_form" action="../Controller/newenquiry.php" method="POST" role="form" autocomplete="off" enctype="multipart/form-data" name="enquiryform" onsubmit="return FormValidation()">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="row g-3">
                        <div class="alert alert-danger alert-dismissable" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Select one among Trainings/Internships/Demo/Services
                        </div>
                        <div class="col-md-3">
                            <label class=label for=name2>Name</label>
                            <div class="col-sm-12">
                                <input type=text name=name2 class=form-control id=name2 placeholder=Name required />
                            </div>
                        </div>
                        <br />
                        <div class="col-md-3">
                            <label class=label for=email2>Email</label>
                            <div class="col-sm-12">
                                <input type=email name=email2 class=form-control id=email2 placeholder=name@example.com />
                            </div>
                        </div>

                        <br />
                        <div class="col-md-3">
                            <label class=label for=phone2>Phone </label>
                            <div class="col-sm-12">
                                <input type=tel name=phone2 class=form-control id=phone2 placeholder=Number required maxlength="10" />
                            </div>
                        </div>
                        <br />
                        <div class="col-md-3">
                            <label class=label for=source>Source </label>
                            <div class="col-sm-12">
                                <select class="custom-select" id="source" name="source">
                                    <option value="">Select </option>
                                    <option value="Referral">Referral</option>
                                    <option value="Website">Website</option>
                                    <option value="Google">Google </option>
                                    <option value="Walk-in">Walk-in </option>
                                    <option value="Facebook">Facebook </option>
                                    <option value="JustDail">JustDail </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class=label for=trainings2>Trainings</label>
                            <div class="col-sm-12">
                                <select class="custom-select" id="trainings2" name="trainings2">
                                    <option value="">Select your Interest</option>
                                    <option value="Web Designing and Development">Web Designing and Development
                                    </option>
                                    <?php
                                    $option = "";
                                    $courselist = DBcourse::selectall();
                                    foreach ($courselist as $course) {
                                        $option .= "<option 
                                                         >" . $course->get_cname() . "</option>";
                                    }
                                    echo $option;
                                    ?>
                                </select><br />
                            </div>
                        </div>
                        <br />
                        <div class="col-md-3">
                            <label class=label for=democlass>Demo Class</label>
                            <div class="col-sm-12">
                                <select class=custom-select id=democlass name=democlass>
                                    <option value="">Select your Interest</option>
                                    <option value="Web Designing and Development">Web Designing and Development
                                    </option>
                                    <option value="Python Programming">Python Programming</option>
                                    <option value="Digital Marketing">Digital Marketing</option>
                                    <option value="Android Development">Android Development</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class=label for=internship2>Internships</label>
                            <div class="col-sm-12">
                                <select class=custom-select id=internship2 name=internship2>
                                    <option value="">Select your Interest</option>
                                    <option value="Web Designing and Development">Web Designing and Development
                                    </option>
                                    <option value="Python Programming">Python Programming</option>
                                    <option value="Digital Marketing">Digital Marketing</option>
                                    <option value="Android Development">Android Development</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class=label for=services>Services</label>
                            <div class="col-sm-12">
                                <select class=custom-select id=services name=services>
                                    <option value="">Select your Interest</option>
                                    <option value="Web Designing and Development">Web Designing and Development
                                    </option>
                                    <option value="Python Programming">Business Process Setup</option>
                                    <option value="Digital Marketing">Digital Marketing</option>
                                    <option value="Mobile Development">Mobile Development</option>
                                    <option value="Graphic Designing">Graphic Designing</option>
                                    <option value="Branding">Branding</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <br />
                        <div class="col-md-3">
                            <label class=label for=branch>Branch</label>
                            <div class="col-sm-12">
                                <select class=custom-select id=branch name=branch>
                                    <option value="Belagavi">Belagavi</option>
                                    <option value="Dharwad">Dharwad</option>
                                    <option value="Hubballi">Hubballi</option>
                                    <option value="Online">Online</option>

                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="recaptcha-token" name="recaptcha-token">
                        <div class="form-group">
                            <div class="col-sm-12 ">
                                <button type="submit" class="btn btn-primary float-right" name="post" id="post">Register</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg"></script>
<script>
    function onSubmit(token) {
        document.getElementById("contactForm").submit();
    }

    function prepareRecaptcha() {
        grecaptcha.ready(function() {
            grecaptcha.execute('6LeUqr8qAAAAACuw4V1CXyY4tQMb1T1qo5EFWAbg', {
                action: 'submit'
            }).then(function(token) {
                document.getElementById('recaptcha-token').value = token;
            });
        });
    }

    // Trigger on load
    window.onload = prepareRecaptcha;
</script>
<!-- Chatbot View Modal -->

<div class="modal fade" id="viewLeadModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Chatbot Lead Details</h5>

                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <tr>
                        <th width="180">Name</th>
                        <td id="view_name"></td>
                    </tr>

                    <tr>
                        <th>Phone</th>
                        <td id="view_phone"></td>
                    </tr>

                    <tr>
                        <th>Interest</th>
                        <td id="view_interest"></td>
                    </tr>

                    <tr>
                        <th>Source</th>
                        <td id="view_source"></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td id="view_status"></td>
                    </tr>

                    <tr>
                        <th>Notes</th>
                        <td id="view_notes"></td>
                    </tr>

                    <tr>
                        <th>Created On</th>
                        <td id="view_created"></td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="editLeadModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="../Controller/chatbotlead-actions.php"
                method="POST">
                <input type="hidden"
                    name="action"
                    value="update_lead">

                <input type="hidden"
                    name="csrf_token"
                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Chatbot Lead
                    </h5>

                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <div class="modal-body">

                    <input type="hidden"
                        name="id"
                        id="edit_id">

                    <div class="mb-3">

                        <label>Name</label>

                        <input type="text"
                            class="form-control"
                            id="edit_name"
                            name="name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Phone</label>

                        <input type="text"
                            class="form-control"
                            id="edit_phone"
                            name="phone"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Interest</label>

                        <input type="text"
                            class="form-control"
                            id="edit_interest"
                            name="interest"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit" class="btn btn-primary">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php require_once("footer.php") ?>

<br />

<script>
    function FormValidation() {
        debugger;
        if (document.enquiryform.trainings2.value == '' && document.enquiryform.democlass.value == '' &&
            document.enquiryform.internship2.value == '' && document.enquiryform.services.value == '') {
            $('.alert').show();
            return false;

        } else {
            return true;
        }

    }
    $(document).ready(function() {
        $.ajaxSetup({
            cache: false
        });
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = dd + '-' + mm + '-' + yyyy;
        $('#today').text(today);


        $('.toast').toast('show');


        // var table = $('#training').DataTable({
        //     "order": [0, 'desc']
        // });
        var internship = $('#Internship').DataTable();
        var DemoClass = $('#Tabledemoclass').DataTable();
        var Services = $('#Services').DataTable();

        $('#column3_search').on('keyup', function() {
            table.columns(0).search(this.value).draw();
            internship.columns(0).search(this.value).draw();
            DemoClass.columns(0).search(this.value).draw();
            Services.columns(0).search(this.value).draw();
        });


        var FollowUp = $('#TableFollowup').DataTable({});
        var table = $('#training').DataTable({

        });
        $('#training_filter input').off('input');
        var chatbotTable = $('#chatbotTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [
                [4, "desc"]
            ]
        });
        // Search
        $('#chatbotSearch').on('keyup', function() {
            chatbotTable.search(this.value).draw();
        });

        // Status Filter
        $('#chatbotStatus').on('change', function() {
            chatbotTable.column(5).search(this.value).draw();
        });

        // Interest Filter
        $('#chatbotInterest').on('keyup', function() {
            chatbotTable.column(2).search(this.value).draw();
        });

        // Date Filter
        $('#chatbotDate').on('change', function() {
            chatbotTable.column(4).search(this.value).draw();
        });

        // Add event listener for opening and closing details
        $('#training tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                let template = `<table class="table table-bordered" id="followuptable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Follwed By</th>
                        <th> FollowUp Date</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead><tbody>`;

                let followUpUrl = config.productionPath + "/Admin/Controller/newfollowup.php?id=" + row.data()[1];

                $.getJSON(followUpUrl).done(function(data) {
                    console.log(data);
                    $.each(data, function(index, value) {

                        template += `<tr><td>${value.followup_by}</td>
                        <td>${value.followupDate}</td>
                        <td class="followup-comments">${value.comments}</td>
                        <td>${value.status}</td>
                        <td>${value.followup_on}</td>
                      
                        </tr>`;
                    });

                    template += `</tbody></table><br><br><form method="post" id="followup_form" action="../Controller/newfollowup.php">
          
          <div class="form-group">
              <div class="row">
                  <div class="col-md-6">
                      <label for="followupDate" class="col-md-6 control-label">FollowUp Date</label>
                      <div class="col-sm-12">
                          <input type="date" id="followupDate" name="followupDate" class="form-control" required>
                      </div>
                  </div>

                  <div class="col-md-6">
                      <label for="status" class="col-md-6 control-label">Status</label>
                      <div class="col-sm-12">
                          <select class="custom-select" id="status" name="status">
                              <option value="">Select</option>
                              <option value="In Progress">In Progress</option>
                              <option value="Converted">Converted</option>
                              <option value="Bad">Bad</option>
                              <option value="Demo Class">Demo Class</option>

                          </select>
                      </div>
                  </div>
              </div>
          </div>
          <div class="form-group">
              <div class="row">
                  <div class="col-md-12">
                      <fieldset>
                          <legend>Comments:</legend>
                          <div class="form-floating">
                              <textarea class="form-control" placeholder="Leave a comment here" id="followcomment" style="height: 100px" data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" name="followcomment"></textarea>
                              <label for="followcomment">Comments</label>
                          </div>
                          <input type="hidden" name="followenqid" id="followenqid" value="${row.data()[1]}">
                          <fieldset>
                  </div>
              </div>
          </div>
          <div class="form-group">
              <div class="row">
                  <div class="col-md-8">
                      <input type="hidden" name="followupBy" id="followupBy" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value=<?php echo $_SESSION['login_user']; ?> />
                  </div>
              </div>
          </div>
          <button type="submit" class="btn btn-warning">FollowUp</button>
          </form>`;
                    row.child(template).show();
                });

                tr.addClass('shown');
            }
        });
        $('#TableFollowup tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = FollowUp.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                let template = `<table class="table table-bordered" id="followuptable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Follwed By</th>
                        <th> FollowUp Date</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead><tbody>`;

                let followUpUrl = config.productionPath + "/Admin/Controller/newfollowup.php?id=" + row.data()[1];

                $.getJSON(followUpUrl).done(function(data) {
                    console.log(data);
                    $.each(data, function(index, value) {

                        template += `<tr>
<td>${value.followup_by}</td>
<td>${value.followupDate}</td>

<td style="
    white-space: normal;
    word-break: break-word;
    overflow-wrap: anywhere;
    max-width:500px;
">
    ${value.comments}
</td>

<td>${value.status}</td>
<td>${value.followup_on}</td>
</tr>`;
                    });

                    template += `</tbody></table><br><br><form method="post" id="followup_form" action="../Controller/newfollowup.php">
          
          <div class="form-group">
              <div class="row">
                  <div class="col-md-6">
                      <label for="followupDate" class="col-md-6 control-label">FollowUp Date</label>
                      <div class="col-sm-12">
                          <input type="date" id="followupDate" name="followupDate" class="form-control" required>
                      </div>
                  </div>

                  <div class="col-md-6">
                      <label for="status" class="col-md-6 control-label">Status</label>
                      <div class="col-sm-12">
                          <select class="custom-select" id="status" name="status">
                              <option value="">Select</option>
                              <option value="In Progress">In Progress</option>
                              <option value="Converted">Converted</option>
                              <option value="Bad">Bad</option>
                              <option value="Demo Class">Demo Class</option>

                          </select>
                      </div>
                  </div>
              </div>
          </div>
          <div class="form-group">
              <div class="row">
                  <div class="col-md-12">
                      <fieldset>
                          <legend>Comments:</legend>
                          <div class="form-floating">
                              <textarea class="form-control" placeholder="Leave a comment here" id="followcomment" style="height: 100px" data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" name="followcomment"></textarea>
                              <label for="followcomment">Comments</label>
                          </div>
                          <input type="hidden" name="followenqid" id="followenqid" value="${row.data()[1]}">
                          <fieldset>
                  </div>
              </div>
          </div>
          <div class="form-group">
              <div class="row">
                  <div class="col-md-8">
                      <input type="hidden" name="followupBy" id="followupBy" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value=<?php echo $_SESSION['login_user']; ?> />
                  </div>
              </div>
          </div>
          <button type="submit" class="btn btn-warning">FollowUp</button>
          </form>`;
                    row.child(template).show();
                });

                tr.addClass('shown');
            }
        });

        $(document).on('click', '.chatbot-view', function(e) {

            e.preventDefault();

            $('#view_name').text($(this).data('name'));
            $('#view_phone').text($(this).data('phone'));
            $('#view_interest').text($(this).data('interest'));
            $('#view_source').text($(this).data('source'));
            $('#view_status').text($(this).data('status'));
            $('#view_notes').text($(this).data('notes'));
            $('#view_created').text($(this).data('created'));

            $('#viewLeadModal').modal('show');

        });
        $(document).on('click', '.chatbot-edit', function(e) {

            e.preventDefault();

            $('#edit_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_phone').val($(this).data('phone'));
            $('#edit_interest').val($(this).data('interest'));

            $('#editLeadModal').modal('show');

        });
    });
</script>