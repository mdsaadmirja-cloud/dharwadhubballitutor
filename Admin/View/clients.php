<?php
require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../DB Operations/ClientOps.php";
require_once "../DB Operations/ClientProjectOps.php";
require_once "../DB Operations/BranchOps.php";
require_once "../model/Branchmodel.php";

include_once "header.php";

$result = DBclient::getAllClients();
$clients = $result['data'];
$branches = DBbranch::selectbranch();

$dashboard = DBclient::getClientDashboardCounts();
$projectDashboard = DBclientproject::getProjectDashboardCounts();
?>

<div class="container-fluid mt-3">

    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php unset($_SESSION['success']);
    } ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php unset($_SESSION['error']);
    } ?>
    <div class="card shadow-sm mb-3">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fa fa-building"></i>
                Client Management
            </h5>

            <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addClientModal">

                <i class="fa fa-plus"></i>

                Add Client

            </button>

        </div>

    </div>
    <div class="row mb-3">

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Clients
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboard['total_clients']; ?>
                            </div>

                        </div>

                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Clients
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboard['active_clients']; ?>
                            </div>

                        </div>

                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Inactive Clients
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboard['inactive_clients']; ?>
                            </div>

                        </div>

                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Running Projects
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $projectDashboard['running_projects']; ?>
                            </div>

                        </div>

                        <div class="col-auto">
                            <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Client List
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover" id="clientTable" width="100%" cellspacing="0">

                    <thead class="thead-dark">

                        <tr>
                            <th>Client Code</th>
                            <th>Branch</th>
                            <th>Company</th>
                            <th>Client Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Projects</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($clients as $client) { ?>

                            <tr>

                                <<td><?= htmlspecialchars($client->get_client_code()); ?></td>

                                    <td>
                                        <?= htmlspecialchars($client->get_branch_name()); ?>
                                    </td>

                                    <td><?= htmlspecialchars($client->get_company_name()); ?></td>

                                    <td><?= htmlspecialchars($client->get_client_name()); ?></td>

                                    <td><?= htmlspecialchars($client->get_mobile()); ?></td>

                                    <td><?= htmlspecialchars($client->get_email()); ?></td>

                                    <td class="text-center">
                                        <?= $client->get_total_projects(); ?>
                                    </td>

                                    <td class="text-center">

                                        <?php if ($client->get_status() == "Active") { ?>

                                            <span class="badge badge-success">
                                                Active
                                            </span>

                                        <?php } else { ?>

                                            <span class="badge badge-danger">
                                                Inactive
                                            </span>

                                        <?php } ?>

                                    </td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-info btn-sm viewClient"

                                            data-id="<?= $client->get_id(); ?>"
                                            data-code="<?= htmlspecialchars($client->get_client_code()); ?>"
                                            data-branch="<?= htmlspecialchars($client->get_branch_name()); ?>"
                                            data-company="<?= htmlspecialchars($client->get_company_name()); ?>"
                                            data-client="<?= htmlspecialchars($client->get_client_name()); ?>"
                                            data-mobile="<?= htmlspecialchars($client->get_mobile()); ?>"
                                            data-altmobile="<?= htmlspecialchars($client->get_alternate_mobile()); ?>"
                                            data-email="<?= htmlspecialchars($client->get_email()); ?>"
                                            data-website="<?= htmlspecialchars($client->get_website()); ?>"
                                            data-gst="<?= htmlspecialchars($client->get_gst_number()); ?>"
                                            data-address="<?= htmlspecialchars($client->get_address()); ?>"
                                            data-city="<?= htmlspecialchars($client->get_city()); ?>"
                                            data-state="<?= htmlspecialchars($client->get_state()); ?>"
                                            data-pincode="<?= htmlspecialchars($client->get_pincode()); ?>"
                                            data-industry="<?= htmlspecialchars($client->get_industry()); ?>"
                                            data-status="<?= htmlspecialchars($client->get_status()); ?>"
                                            data-notes="<?= htmlspecialchars($client->get_notes()); ?>"
                                            data-projects="<?= $client->get_total_projects(); ?>">

                                            <i class="fas fa-eye"></i>

                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-warning btn-sm editClient"

                                            data-id="<?= $client->get_id(); ?>"
                                            data-code="<?= htmlspecialchars($client->get_client_code()); ?>"
                                            data-branchid="<?= $client->get_branch_id(); ?>"
                                            data-company="<?= htmlspecialchars($client->get_company_name()); ?>"
                                            data-client="<?= htmlspecialchars($client->get_client_name()); ?>"
                                            data-mobile="<?= htmlspecialchars($client->get_mobile()); ?>"
                                            data-altmobile="<?= htmlspecialchars($client->get_alternate_mobile()); ?>"
                                            data-email="<?= htmlspecialchars($client->get_email()); ?>"
                                            data-website="<?= htmlspecialchars($client->get_website()); ?>"
                                            data-gst="<?= htmlspecialchars($client->get_gst_number()); ?>"
                                            data-address="<?= htmlspecialchars($client->get_address()); ?>"
                                            data-city="<?= htmlspecialchars($client->get_city()); ?>"
                                            data-state="<?= htmlspecialchars($client->get_state()); ?>"
                                            data-pincode="<?= htmlspecialchars($client->get_pincode()); ?>"
                                            data-industry="<?= htmlspecialchars($client->get_industry()); ?>"
                                            data-status="<?= htmlspecialchars($client->get_status()); ?>"
                                            data-notes="<?= htmlspecialchars($client->get_notes()); ?>">

                                            <i class="fas fa-edit"></i>

                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm deleteClient"

                                            data-id="<?= $client->get_id(); ?>"
                                            data-company="<?= htmlspecialchars($client->get_company_name()); ?>"
                                            data-client="<?= htmlspecialchars($client->get_client_name()); ?>">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    <!-- ===========================
     ADD CLIENT MODAL
    =========================== -->

    <div class="modal fade" id="addClientModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <form action="../Controller/client-actions.php" method="POST">

                    <input type="hidden" name="action" value="add">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">
                            <i class="fas fa-user-plus"></i>
                            Add Client
                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <!-- Client Code -->

                            <div class="col-md-4 mb-3">

                                <label>Client Code</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="client_code"
                                    value="<?= DBclient::generateClientCode(); ?>"
                                    readonly>

                            </div>
                            <div class="col-md-6">

                                <label>Branch <span class="text-danger">*</span></label>

                                <select
                                    name="branch_id"
                                    class="form-control"
                                    required>

                                    <option value="">Select Branch</option>

                                    <?php foreach ($branches as $branch) { ?>

                                        <option value="<?= $branch->get_id(); ?>">
                                            <?= htmlspecialchars($branch->get_branchname()); ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <!-- Company -->

                            <div class="col-md-4 mb-3">

                                <label>Company Name *</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="company_name"
                                    required>

                            </div>

                            <!-- Client Name -->

                            <div class="col-md-4 mb-3">

                                <label>Client Name *</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="client_name"
                                    required>

                            </div>

                            <!-- Mobile -->

                            <div class="col-md-4 mb-3">

                                <label>Mobile *</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="mobile"
                                    maxlength="10"
                                    required>

                            </div>

                            <!-- Alternate -->

                            <div class="col-md-4 mb-3">

                                <label>Alternate Mobile</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="alternate_mobile">

                            </div>

                            <!-- Email -->

                            <div class="col-md-4 mb-3">

                                <label>Email</label>

                                <input
                                    type="email"
                                    class="form-control"
                                    name="email">

                            </div>

                            <!-- Website -->

                            <div class="col-md-4 mb-3">

                                <label>Website</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="website">

                            </div>

                            <!-- GST -->

                            <div class="col-md-4 mb-3">

                                <label>GST Number</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="gst_number">

                            </div>

                            <!-- Industry -->

                            <div class="col-md-4 mb-3">

                                <label>Industry</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="industry">

                            </div>

                            <!-- Address -->

                            <div class="col-md-12 mb-3">

                                <label>Address</label>

                                <textarea
                                    class="form-control"
                                    name="address"
                                    rows="2"></textarea>

                            </div>

                            <!-- City -->

                            <div class="col-md-4 mb-3">

                                <label>City</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="city">

                            </div>

                            <!-- State -->

                            <div class="col-md-4 mb-3">

                                <label>State</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="state">

                            </div>

                            <!-- Pincode -->

                            <div class="col-md-4 mb-3">

                                <label>Pincode</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="pincode">

                            </div>

                            <!-- Status -->

                            <div class="col-md-4 mb-3">

                                <label>Status</label>

                                <select
                                    class="form-control"
                                    name="status">

                                    <option value="Active">Active</option>

                                    <option value="Inactive">Inactive</option>

                                </select>

                            </div>

                            <!-- Notes -->

                            <div class="col-md-8 mb-3">

                                <label>Notes</label>

                                <textarea
                                    class="form-control"
                                    rows="2"
                                    name="notes"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            class="btn btn-success">

                            <i class="fas fa-save"></i>

                            Save Client

                        </button>

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                            Cancel

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <!-- ==========================================
     EDIT CLIENT MODAL
        =========================================== -->

    <div class="modal fade" id="editClientModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <form action="../Controller/client-actions.php" method="POST">

                    <input type="hidden" name="action" value="update">

                    <input type="hidden" name="id" id="edit_id">

                    <div class="modal-header bg-warning">

                        <h5 class="modal-title">

                            <i class="fas fa-edit"></i>

                            Edit Client

                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label>Client Code</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_client_code"
                                    readonly>

                            </div>
                            <div class="col-md-6">

                                <label>Branch <span class="text-danger">*</span></label>

                                <select
                                    name="branch_id"
                                    id="edit_branch"
                                    class="form-control"
                                    required>

                                    <option value="">Select Branch</option>

                                    <?php foreach ($branches as $branch) { ?>

                                        <option value="<?= $branch->get_id(); ?>">
                                            <?= htmlspecialchars($branch->get_branchname()); ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label>Company Name <span class="text-danger">*</span></label>

                                <input
                                    type="text"
                                    name="company_name"
                                    id="edit_company"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Client Name</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_client_name"
                                    name="client_name"
                                    required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Mobile</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_mobile"
                                    name="mobile"
                                    required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Alternate Mobile</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_alternate_mobile"
                                    name="alternate_mobile">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Email</label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="edit_email"
                                    name="email">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Website</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_website"
                                    name="website">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>GST Number</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_gst"
                                    name="gst_number">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Industry</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_industry"
                                    name="industry">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label>Address</label>

                                <textarea
                                    class="form-control"
                                    id="edit_address"
                                    name="address"
                                    rows="2"></textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>City</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_city"
                                    name="city">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>State</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_state"
                                    name="state">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Pincode</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="edit_pincode"
                                    name="pincode">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label>Status</label>

                                <select
                                    class="form-control"
                                    id="edit_status"
                                    name="status">

                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>

                                </select>

                            </div>

                            <div class="col-md-8 mb-3">

                                <label>Notes</label>

                                <textarea
                                    class="form-control"
                                    id="edit_notes"
                                    name="notes"
                                    rows="2"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="fas fa-save"></i>

                            Update Client

                        </button>

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <!-- ==========================================
     DELETE CLIENT MODAL
        =========================================== -->

    <div class="modal fade" id="deleteClientModal" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="../Controller/client-actions.php" method="POST">

                    <input type="hidden" name="action" value="delete">

                    <input type="hidden" name="id" id="delete_id">

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">
                            <i class="fas fa-trash"></i>
                            Delete Client
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="alert alert-warning mb-3">

                            <strong>Warning!</strong><br>

                            This will permanently delete the client and all associated projects.

                        </div>

                        <h5 id="delete_client_name"></h5>

                        <p class="text-muted mb-0">
                            This action cannot be undone.
                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            class="btn btn-danger">

                            <i class="fas fa-trash"></i>

                            Delete

                        </button>

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <!-- =====================================================
     VIEW CLIENT MODAL
        ====================================================== -->

    <div class="modal fade" id="viewClientModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header bg-info text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-building"></i>

                        Client Profile

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <!-- LEFT -->

                        <div class="col-lg-8">

                            <table class="table table-bordered">

                                <tr>
                                    <th width="30%">Client Code</th>
                                    <td id="view_client_code"></td>
                                </tr>
                                <tr>
                                    <th>Branch</th>
                                    <td id="view_branch"></td>
                                </tr>

                                <tr>
                                    <th>Company</th>
                                    <td id="view_company"></td>
                                </tr>

                                <tr>
                                    <th>Client Name</th>
                                    <td id="view_client"></td>
                                </tr>

                                <tr>
                                    <th>Mobile</th>
                                    <td id="view_mobile"></td>
                                </tr>

                                <tr>
                                    <th>Email</th>
                                    <td id="view_email"></td>
                                </tr>

                                <tr>
                                    <th>Website</th>
                                    <td id="view_website"></td>
                                </tr>

                                <tr>
                                    <th>GST Number</th>
                                    <td id="view_gst"></td>
                                </tr>

                                <tr>
                                    <th>Industry</th>
                                    <td id="view_industry"></td>
                                </tr>

                                <tr>
                                    <th>Address</th>
                                    <td id="view_address"></td>
                                </tr>

                                <tr>
                                    <th>Notes</th>
                                    <td id="view_notes"></td>
                                </tr>

                            </table>

                        </div>

                        <!-- RIGHT -->

                        <div class="col-lg-4">

                            <div class="card border-info">

                                <div class="card-header bg-info text-white">

                                    Summary

                                </div>

                                <div class="card-body">

                                    <h6>Status</h6>

                                    <h5 id="view_status"></h5>

                                    <hr>

                                    <h6>Total Projects</h6>

                                    <h3 id="view_projects">0</h3>

                                    <hr>

                                    <small class="text-muted">

                                        Project statistics will automatically
                                        appear here once the Project Module
                                        is completed.

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.querySelectorAll(".editClient").forEach(function(btn) {

            btn.addEventListener("click", function() {

                document.getElementById("edit_id").value = this.dataset.id;
                document.getElementById("edit_branch").value = this.dataset.branchid;
                document.getElementById("edit_client_code").value = this.dataset.code;
                document.getElementById("edit_company").value = this.dataset.company;
                document.getElementById("edit_client_name").value = this.dataset.client;
                document.getElementById("edit_mobile").value = this.dataset.mobile;
                document.getElementById("edit_alternate_mobile").value = this.dataset.altmobile;
                document.getElementById("edit_email").value = this.dataset.email;
                document.getElementById("edit_website").value = this.dataset.website;
                document.getElementById("edit_gst").value = this.dataset.gst;
                document.getElementById("edit_address").value = this.dataset.address;
                document.getElementById("edit_city").value = this.dataset.city;
                document.getElementById("edit_state").value = this.dataset.state;
                document.getElementById("edit_pincode").value = this.dataset.pincode;
                document.getElementById("edit_industry").value = this.dataset.industry;
                document.getElementById("edit_status").value = this.dataset.status;
                document.getElementById("edit_notes").value = this.dataset.notes;

                const modal = new bootstrap.Modal(document.getElementById("editClientModal"));
                modal.show();

            });

        });
    </script>
    <script>
        document.querySelectorAll(".deleteClient").forEach(function(btn) {

            btn.addEventListener("click", function() {

                document.getElementById("delete_id").value = this.dataset.id;

                document.getElementById("delete_client_name").innerHTML =

                    "<strong>" + this.dataset.company + "</strong><br>" + this.dataset.client;

                const modal = new bootstrap.Modal(document.getElementById("deleteClientModal"));

                modal.show();

            });

        });
    </script>
    <script>
        document.querySelectorAll(".viewClient").forEach(function(btn) {

            btn.addEventListener("click", function() {

                document.getElementById("view_client_code").textContent = this.dataset.code;
                document.getElementById("view_branch").textContent = this.dataset.branch;
                document.getElementById("view_company").textContent = this.dataset.company;
                document.getElementById("view_client").textContent = this.dataset.client;
                document.getElementById("view_mobile").textContent = this.dataset.mobile;
                document.getElementById("view_email").textContent = this.dataset.email;
                document.getElementById("view_website").textContent = this.dataset.website;
                document.getElementById("view_gst").textContent = this.dataset.gst;
                document.getElementById("view_industry").textContent = this.dataset.industry;

                document.getElementById("view_address").textContent =
                    this.dataset.address + ", " +
                    this.dataset.city + ", " +
                    this.dataset.state + " - " +
                    this.dataset.pincode;

                document.getElementById("view_notes").textContent = this.dataset.notes;

                // Status Badge
                let status = this.dataset.status;

                document.getElementById("view_status").innerHTML =
                    status === "Active" ?
                    '<span class="badge bg-success">Active</span>' :
                    '<span class="badge bg-danger">Inactive</span>';

                document.getElementById("view_projects").textContent =
                    this.dataset.projects;

                const modal = new bootstrap.Modal(
                    document.getElementById("viewClientModal")
                );

                modal.show();

            });

        });
    </script>

    <?php include_once "footer.php";  ?>