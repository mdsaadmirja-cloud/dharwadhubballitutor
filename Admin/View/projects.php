<?php
require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";

require_once "../DB Operations/ClientProjectOps.php";
require_once "../DB Operations/ClientOps.php";

require_once "../model/ClientProjectModel.php";
require_once "../model/ClientModel.php";

include_once "header.php";

$result = DBclientproject::getAllProjects();
$projects = $result['data'];

$projectDashboard = DBclientproject::getProjectDashboardCounts();

$clients = DBclient::getAllClients()['data'];
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
                <i class="fas fa-project-diagram"></i>
                Project Management
            </h5>

            <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addProjectModal">

                <i class="fa fa-plus"></i>
                Add Project

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
                                Total Projects
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $projectDashboard['total_projects']; ?>
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
                                Running Projects
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $projectDashboard['running_projects']; ?>
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
                                Completed Projects
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $projectDashboard['completed_projects']; ?>
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
                                Revenue ₹
                            </div>

                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($projectDashboard['total_revenue'], 2); ?>
                            </div>

                        </div>

                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Project List
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover" id="projectTable" width="100%" cellspacing="0">

                    <thead class="thead-dark">

                        <tr>

                            <th>Project</th>
                            <th>Branch</th>

                            <th>Client</th>

                            <th>Technology</th>

                            <th>Budget</th>

                            <th>Priority</th>

                            <th>Status</th>

                            <th width="170">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($projects as $project) { ?>

                            <tr>

                                <td><?= htmlspecialchars($project->get_project_name()); ?></td>
                                <td>
                                    <?= htmlspecialchars($project->get_branch_name()); ?>
                                </td>

                                <td><?= htmlspecialchars($project->get_company_name()); ?></td>

                                <td><?= htmlspecialchars($project->get_technology()); ?></td>

                                <td class="text-end">
                                    ₹ <?= number_format($project->get_budget(), 2); ?>
                                </td>

                                <td class="text-center">
                                    <?php
                                    switch ($project->get_priority()) {

                                        case "High":
                                            echo '<span class="badge badge-danger">High</span>';
                                            break;

                                        case "Medium":
                                            echo '<span class="badge badge-warning">Medium</span>';
                                            break;

                                        default:
                                            echo '<span class="badge badge-success">Low</span>';
                                    }
                                    ?>
                                </td>

                                <td class="text-center">

                                    <?php
                                    switch ($project->get_project_status()) {

                                        case "Planning":
                                            echo '<span class="badge badge-secondary">Planning</span>';
                                            break;

                                        case "Development":
                                            echo '<span class="badge badge-primary">Development</span>';
                                            break;

                                        case "Testing":
                                            echo '<span class="badge badge-warning">Testing</span>';
                                            break;

                                        case "Completed":
                                            echo '<span class="badge badge-success">Completed</span>';
                                            break;

                                        case "Maintenance":
                                            echo '<span class="badge badge-info">Maintenance</span>';
                                            break;

                                        case "Cancelled":
                                            echo '<span class="badge badge-danger">Cancelled</span>';
                                            break;
                                    }
                                    ?>

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="btn btn-info btn-sm viewProject"

                                        data-id="<?= $project->get_id(); ?>"
                                        data-branch="<?= htmlspecialchars($project->get_branch_name()); ?>"
                                        data-client="<?= htmlspecialchars($project->get_company_name()); ?>"
                                        data-project="<?= htmlspecialchars($project->get_project_name()); ?>"
                                        data-type="<?= htmlspecialchars($project->get_project_type()); ?>"
                                        data-technology="<?= htmlspecialchars($project->get_technology()); ?>"
                                        data-budget="<?= $project->get_budget(); ?>"
                                        data-advance="<?= $project->get_advance_amount(); ?>"
                                        data-pending="<?= $project->get_pending_amount(); ?>"
                                        data-priority="<?= htmlspecialchars($project->get_priority()); ?>"
                                        data-status="<?= htmlspecialchars($project->get_project_status()); ?>"
                                        data-progress="<?= $project->get_progress(); ?>"
                                        data-start="<?= htmlspecialchars($project->get_start_date()); ?>"
                                        data-delivery="<?= htmlspecialchars($project->get_expected_delivery()); ?>"
                                        data-completed="<?= htmlspecialchars($project->get_completed_date()); ?>"
                                        data-description="<?= htmlspecialchars($project->get_description()); ?>"
                                        data-remarks="<?= htmlspecialchars($project->get_remarks()); ?>">

                                        <i class="fas fa-eye"></i>

                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm editProject"

                                        data-id="<?= $project->get_id(); ?>"
                                        data-clientid="<?= $project->get_client_id(); ?>"
                                        data-project="<?= htmlspecialchars($project->get_project_name()); ?>"
                                        data-type="<?= htmlspecialchars($project->get_project_type()); ?>"
                                        data-technology="<?= htmlspecialchars($project->get_technology()); ?>"
                                        data-description="<?= htmlspecialchars($project->get_description()); ?>"
                                        data-budget="<?= $project->get_budget(); ?>"
                                        data-advance="<?= $project->get_advance_amount(); ?>"
                                        data-pending="<?= $project->get_pending_amount(); ?>"
                                        data-priority="<?= htmlspecialchars($project->get_priority()); ?>"
                                        data-status="<?= htmlspecialchars($project->get_project_status()); ?>"
                                        data-progress="<?= $project->get_progress(); ?>"
                                        data-start="<?= $project->get_start_date(); ?>"
                                        data-delivery="<?= $project->get_expected_delivery(); ?>"
                                        data-completed="<?= $project->get_completed_date(); ?>"
                                        data-remarks="<?= htmlspecialchars($project->get_remarks()); ?>">

                                        <i class="fas fa-edit"></i>

                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm deleteProject"

                                        data-id="<?= $project->get_id(); ?>"
                                        data-project="<?= htmlspecialchars($project->get_project_name()); ?>">

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
    <!-- ===========================================================
                ADD PROJECT MODAL
    =========================================================== -->

    <div class="modal fade" id="addProjectModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <form method="POST"
                action="../Controller/clientproject-actions.php">

                <input type="hidden"
                    name="action"
                    value="add">

                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            <i class="fas fa-project-diagram"></i>

                            Add Project

                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6">

                                <label>Client *</label>

                                <select
                                    name="client_id"
                                    id="client_id"
                                    class="form-control"
                                    required>

                                    <option value="">Select Client</option>

                                    <?php foreach ($clients as $client) { ?>

                                        <option
                                            value="<?= $client->get_id(); ?>"
                                            data-branch="<?= htmlspecialchars($client->get_branch_name()); ?>">

                                            <?= htmlspecialchars($client->get_company_name()); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label>Branch</label>

                                <input
                                    type="text"
                                    id="project_branch"
                                    class="form-control"
                                    readonly>

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-12">

                                <label>Project Name *</label>

                                <input
                                    type="text"
                                    name="project_name"
                                    class="form-control"
                                    required>

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">

                                <label>Project Type</label>

                                <input
                                    type="text"
                                    name="project_type"
                                    class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Technology</label>

                                <select
                                    name="technology"
                                    class="form-control">

                                    <option>PHP</option>
                                    <option>Laravel</option>
                                    <option>CodeIgniter</option>
                                    <option>React</option>
                                    <option>Flutter</option>
                                    <option>WordPress</option>
                                    <option>Python</option>
                                    <option>Django</option>
                                    <option>Java</option>
                                    <option>Other</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label>Priority</label>

                                <select
                                    name="priority"
                                    class="form-control">

                                    <option>Low</option>
                                    <option selected>Medium</option>
                                    <option>High</option>

                                </select>

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">

                                <label>Budget</label>

                                <input
                                    type="number"
                                    id="budget"
                                    step="0.01"
                                    name="budget"
                                    value="0"
                                    class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Advance</label>

                                <input
                                    type="number"
                                    id="advance"
                                    step="0.01"
                                    name="advance_amount"
                                    value="0"
                                    class="form-control">

                            </div>
                            <div class="row mt-3">

                                <div class="col-md-4">

                                    <label>Advance Payment Mode</label>

                                    <select
                                        name="advance_payment_mode"
                                        class="form-control">

                                        <option value="Cash">Cash</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Card">Card</option>

                                    </select>

                                </div>

                                <div class="col-md-4">

                                    <label>Advance Payment Date</label>

                                    <input
                                        type="date"
                                        name="advance_payment_date"
                                        value="<?= date('Y-m-d'); ?>"
                                        class="form-control">

                                </div>

                                <div class="col-md-4">

                                    <label>Transaction No.</label>

                                    <input
                                        type="text"
                                        name="advance_transaction_no"
                                        class="form-control">

                                </div>

                            </div>

                            <div class="mt-3">

                                <label>Advance Payment Remarks</label>

                                <textarea
                                    name="advance_payment_remarks"
                                    rows="2"
                                    class="form-control">Initial Advance</textarea>

                            </div>

                            <div class="col-md-4">

                                <label>Pending</label>

                                <input
                                    type="number"
                                    id="pending"
                                    name="pending_amount"
                                    readonly
                                    class="form-control">

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">

                                <label>Status</label>

                                <select
                                    name="project_status"
                                    class="form-control">

                                    <option>Planning</option>
                                    <option>Development</option>
                                    <option>Testing</option>
                                    <option>Completed</option>
                                    <option>Maintenance</option>
                                    <option>Cancelled</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label>Progress</label>

                                <input
                                    type="number"
                                    min="0"
                                    max="100"
                                    name="progress"
                                    value="0"
                                    class="form-control">

                            </div>
                            <div class="row mt-3">

                                <div class="col-md-4">

                                    <label>Start Date</label>

                                    <input
                                        type="date"
                                        name="start_date"
                                        class="form-control">

                                </div>

                                <div class="col-md-4">

                                    <label>Completed Date</label>

                                    <input
                                        type="date"
                                        name="completed_date"
                                        class="form-control">

                                </div>

                            </div>

                            <div class="col-md-4">

                                <label>Expected Delivery</label>

                                <input
                                    type="date"
                                    name="expected_delivery"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="mt-3">

                            <label>Description</label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                        <div class="mt-3">

                            <label>Remarks</label>

                            <textarea
                                name="remarks"
                                rows="3"
                                class="form-control"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            class="btn btn-success">

                            Save Project

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <!-- ===========================================================
                    VIEW PROJECT MODAL
        =========================================================== -->

    <div class="modal fade"
        id="viewProjectModal"
        tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header bg-info text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-project-diagram"></i>

                        Project Details

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label><strong>Branch</strong></label>

                            <input
                                type="text"
                                id="view_project_branch"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label><strong>Client</strong></label>

                            <input
                                type="text"
                                id="view_client"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label><strong>Project</strong></label>

                            <input
                                type="text"
                                id="view_project"
                                class="form-control"
                                readonly>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label><strong>Type</strong></label>

                            <input
                                type="text"
                                id="view_type"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label><strong>Technology</strong></label>

                            <input
                                type="text"
                                id="view_technology"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label><strong>Priority</strong></label>

                            <input
                                type="text"
                                id="view_priority"
                                class="form-control"
                                readonly>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4">

                            <label><strong>Budget</strong></label>

                            <input
                                type="text"
                                id="view_budget"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label><strong>Advance</strong></label>

                            <input
                                type="text"
                                id="view_advance"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label><strong>Pending</strong></label>

                            <input
                                type="text"
                                id="view_pending"
                                class="form-control"
                                readonly>

                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-4">

                            <label><strong>Status</strong></label>

                            <input
                                type="text"
                                id="view_status"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label><strong>Progress</strong></label>

                            <div class="progress mt-2">

                                <div
                                    id="view_progress_bar"
                                    class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width:0%">
                                    0%
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-4">

                            <label><strong>Start Date</strong></label>

                            <input
                                type="text"
                                id="view_start"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label><strong>Expected Delivery</strong></label>

                            <input
                                type="text"
                                id="view_delivery"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label><strong>Completed Date</strong></label>

                            <input
                                type="text"
                                id="view_completed"
                                class="form-control"
                                readonly>

                        </div>

                    </div>

                    <div class="mt-3">

                        <label><strong>Description</strong></label>

                        <textarea
                            id="view_description"
                            class="form-control"
                            rows="4"
                            readonly></textarea>

                    </div>

                    <div class="mt-3">

                        <label><strong>Remarks</strong></label>

                        <textarea
                            id="view_remarks"
                            class="form-control"
                            rows="3"
                            readonly></textarea>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- ===========================================================
                    EDIT PROJECT MODAL
=========================================================== -->

    <div class="modal fade" id="editProjectModal" tabindex="-1">

        <div class="modal-dialog modal-xl">

            <form method="POST"
                action="../Controller/clientproject-actions.php">

                <input type="hidden" name="action" value="update">

                <input type="hidden"
                    name="id"
                    id="edit_id">

                <div class="modal-content">

                    <div class="modal-header bg-warning text-dark">

                        <h5 class="modal-title">

                            <i class="fas fa-edit"></i>

                            Edit Project

                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6">

                                <label>Client *</label>

                                <select
                                    id="edit_client_id"
                                    name="client_id"
                                    class="form-control"
                                    required>

                                    <option value="">Select Client</option>

                                    <?php foreach ($clients as $client) { ?>

                                        <option
                                            value="<?= $client->get_id(); ?>"
                                            data-branch="<?= htmlspecialchars($client->get_branch_name()); ?>">

                                            <?= htmlspecialchars($client->get_company_name()); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>
                            <div class="col-md-6">

                                <label>Branch</label>

                                <input
                                    type="text"
                                    id="edit_branch"
                                    class="form-control"
                                    readonly>

                            </div>


                            <div class="col-md-6">

                                <label>Project Name *</label>

                                <input
                                    type="text"
                                    id="edit_project_name"
                                    name="project_name"
                                    class="form-control"
                                    required>

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">

                                <label>Project Type</label>

                                <input
                                    type="text"
                                    id="edit_project_type"
                                    name="project_type"
                                    class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Technology</label>

                                <select
                                    id="edit_technology"
                                    name="technology"
                                    class="form-control">

                                    <option>PHP</option>
                                    <option>Laravel</option>
                                    <option>CodeIgniter</option>
                                    <option>React</option>
                                    <option>Flutter</option>
                                    <option>WordPress</option>
                                    <option>Python</option>
                                    <option>Django</option>
                                    <option>Java</option>
                                    <option>Other</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label>Priority</label>

                                <select
                                    id="edit_priority"
                                    name="priority"
                                    class="form-control">

                                    <option>Low</option>
                                    <option>Medium</option>
                                    <option>High</option>

                                </select>

                            </div>

                        </div>
                        <div class="row mt-3">

                            <div class="col-md-4">

                                <label>Budget</label>

                                <input
                                    type="number"
                                    id="edit_budget"
                                    step="0.01"
                                    name="budget"
                                    class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Advance</label>

                                <input
                                    type="number"
                                    id="edit_advance"
                                    step="0.01"
                                    name="advance_amount"
                                    class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Pending</label>

                                <input
                                    type="number"
                                    id="edit_pending"
                                    name="pending_amount"
                                    class="form-control"
                                    readonly>

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">

                                <label>Status</label>

                                <select
                                    id="edit_status"
                                    name="project_status"
                                    class="form-control">

                                    <option>Planning</option>
                                    <option>Development</option>
                                    <option>Testing</option>
                                    <option>Completed</option>
                                    <option>Maintenance</option>
                                    <option>Cancelled</option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <label>Progress (%)</label>

                                <input
                                    type="number"
                                    id="edit_progress"
                                    name="progress"
                                    min="0"
                                    max="100"
                                    class="form-control">

                            </div>

                            <div class="col-md-4">

                                <label>Expected Delivery</label>

                                <input
                                    type="date"
                                    id="edit_expected_delivery"
                                    name="expected_delivery"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">

                                <label>Start Date</label>

                                <input
                                    type="date"
                                    id="edit_start_date"
                                    name="start_date"
                                    class="form-control">

                            </div>

                            <div class="col-md-6">

                                <label>Completed Date</label>

                                <input
                                    type="date"
                                    id="edit_completed_date"
                                    name="completed_date"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="mt-3">

                            <label>Description</label>

                            <textarea
                                id="edit_description"
                                name="description"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                        <div class="mt-3">

                            <label>Remarks</label>

                            <textarea
                                id="edit_remarks"
                                name="remarks"
                                rows="3"
                                class="form-control"></textarea>

                        </div>
                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            class="btn btn-warning">

                            <i class="fas fa-save"></i>

                            Update Project

                        </button>

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <div class="modal fade" id="deleteProjectModal" tabindex="-1">

        <div class="modal-dialog">

            <form method="POST"
                action="../Controller/clientproject-actions.php">

                <input type="hidden"
                    name="action"
                    value="delete">

                <input type="hidden"
                    name="id"
                    id="delete_project_id">

                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">

                            Delete Project

                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <p>

                            Are you sure you want to delete

                            <strong id="delete_project_name"></strong> ?

                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            class="btn btn-danger">

                            Delete

                        </button>

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <script>
        function calculatePending() {

            let budget = parseFloat(document.getElementById("budget").value) || 0;
            let advance = parseFloat(document.getElementById("advance").value) || 0;

            let pending = budget - advance;

            if (pending < 0) {
                pending = 0;
            }

            document.getElementById("pending").value = pending.toFixed(2);
        }

        document.getElementById("budget").addEventListener("input", calculatePending);
        document.getElementById("advance").addEventListener("input", calculatePending);

        calculatePending();
    </script>
    <script>
        document.querySelectorAll(".viewProject").forEach(function(btn) {

            btn.addEventListener("click", function() {

                document.getElementById("view_client").value =
                    this.dataset.client;

                document.getElementById("view_project_branch").value =
                    this.dataset.branch;

                document.getElementById("view_project").value =
                    this.dataset.project;

                document.getElementById("view_type").value =
                    this.dataset.type;

                document.getElementById("view_technology").value =
                    this.dataset.technology;

                document.getElementById("view_budget").value =
                    this.dataset.budget;

                document.getElementById("view_advance").value =
                    this.dataset.advance;

                document.getElementById("view_pending").value =
                    this.dataset.pending;

                document.getElementById("view_priority").value =
                    this.dataset.priority;

                document.getElementById("view_status").value =
                    this.dataset.status;

                document.getElementById("view_start").value =
                    this.dataset.start;

                document.getElementById("view_delivery").value =
                    this.dataset.delivery;

                document.getElementById("view_completed").value =
                    this.dataset.completed;

                document.getElementById("view_description").value =
                    this.dataset.description;

                document.getElementById("view_remarks").value =
                    this.dataset.remarks;

                let progress = parseInt(this.dataset.progress) || 0;

                let bar = document.getElementById("view_progress_bar");

                bar.style.width = progress + "%";

                bar.innerHTML = progress + "%";

                new bootstrap.Modal(
                    document.getElementById("viewProjectModal")
                ).show();

            });

        });
    </script>
    <script>
        document.querySelectorAll(".editProject").forEach(function(btn) {

            btn.addEventListener("click", function() {

                document.getElementById("edit_id").value = this.dataset.id;

                document.getElementById("edit_client_id").value = this.dataset.clientid;
                let client = document.getElementById("edit_client_id");

                document.getElementById("edit_branch").value =
                    client.options[client.selectedIndex].dataset.branch || "";

                document.getElementById("edit_project_name").value = this.dataset.project;

                document.getElementById("edit_project_type").value = this.dataset.type;

                document.getElementById("edit_technology").value = this.dataset.technology;

                document.getElementById("edit_budget").value = this.dataset.budget;

                document.getElementById("edit_advance").value = this.dataset.advance;

                document.getElementById("edit_pending").value = this.dataset.pending;

                document.getElementById("edit_priority").value = this.dataset.priority;

                document.getElementById("edit_status").value = this.dataset.status;

                document.getElementById("edit_progress").value = this.dataset.progress;

                document.getElementById("edit_start_date").value = this.dataset.start;

                document.getElementById("edit_expected_delivery").value = this.dataset.delivery;

                document.getElementById("edit_completed_date").value = this.dataset.completed;

                document.getElementById("edit_description").value = this.dataset.description;

                document.getElementById("edit_remarks").value = this.dataset.remarks;

                const modal = new bootstrap.Modal(
                    document.getElementById("editProjectModal")
                );

                modal.show();

            });

        });


        function calculateEditPending() {

            let budget = parseFloat(document.getElementById("edit_budget").value) || 0;

            let advance = parseFloat(document.getElementById("edit_advance").value) || 0;

            let pending = budget - advance;

            if (pending < 0) {
                pending = 0;
            }

            document.getElementById("edit_pending").value =
                pending.toFixed(2);

        }

        document.getElementById("edit_budget")
            .addEventListener("input", calculateEditPending);

        document.getElementById("edit_advance")
            .addEventListener("input", calculateEditPending);
    </script>
    <script>
        document.getElementById("edit_client_id").addEventListener("change", function() {

            let selected = this.options[this.selectedIndex];

            document.getElementById("edit_branch").value =
                selected.dataset.branch || "";

        });
    </script>
    <script>
        document.getElementById("client_id").addEventListener("change", function() {

            let selected = this.options[this.selectedIndex];

            document.getElementById("project_branch").value =
                selected.dataset.branch || "";

        });
    </script>
    <script>
        document.querySelectorAll(".deleteProject").forEach(function(btn) {

            btn.addEventListener("click", function() {

                document.getElementById("delete_project_id").value =
                    this.dataset.id;

                document.getElementById("delete_project_name").innerText =
                    this.dataset.project;

                new bootstrap.Modal(
                    document.getElementById("deleteProjectModal")
                ).show();

            });

        });
    </script>

    <?php include_once "footer.php";  ?>