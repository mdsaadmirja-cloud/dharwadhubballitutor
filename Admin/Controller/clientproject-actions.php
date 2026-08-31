<?php

require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../DB Operations/ClientProjectOps.php";
require_once "../model/ClientProjectModel.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/projects.php");
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {

    case "add":

        $project = new ClientProject();

        $project->set_client_id($_POST['client_id']);
        $project->set_project_name(trim($_POST['project_name']));
        $project->set_project_type(trim($_POST['project_type']));
        $project->set_technology(trim($_POST['technology']));
        $project->set_description(trim($_POST['description']));
        $project->set_start_date($_POST['start_date']);
        $project->set_expected_delivery($_POST['expected_delivery']);
        $project->set_completed_date($_POST['completed_date']);
        $project->set_budget((float)$_POST['budget']);
        $project->set_advance_amount((float)$_POST['advance_amount']);
        $advancePaymentMode    = $_POST['advance_payment_mode'];
        $advancePaymentDate    = $_POST['advance_payment_date'];
        $advanceTransactionNo  = trim($_POST['advance_transaction_no']);
        $advanceRemarks        = trim($_POST['advance_payment_remarks']);

        // Server-side calculation
        $project->set_pending_amount(
            (float)$_POST['budget'] - (float)$_POST['advance_amount']
        );

        $project->set_priority($_POST['priority']);
        $project->set_project_status($_POST['project_status']);

        if (method_exists($project, 'set_progress')) {
            $project->set_progress((int)$_POST['progress']);
        }

        if (method_exists($project, 'set_remarks')) {
            $project->set_remarks(trim($_POST['remarks']));
        }

        // Validation

        if (empty($_POST['client_id'])) {

            $_SESSION['error'] = "Please select a client.";

            break;
        }

        if (empty(trim($_POST['project_name']))) {

            $_SESSION['error'] = "Project Name is required.";

            break;
        }

        if ((float)$_POST['advance_amount'] > (float)$_POST['budget']) {

            $_SESSION['error'] = "Advance Amount cannot exceed Budget.";

            break;
        }

        if (
            DBclientproject::addProject(
                $project,
                $advancePaymentMode,
                $advancePaymentDate,
                $advanceTransactionNo,
                $advanceRemarks
            )
        ) {

            $_SESSION['success'] = "Project added successfully.";
        } else {

            $_SESSION['error'] = "Unable to add project.";
        }

        break;

    case "update":

        $project = new ClientProject();

        $project->set_id($_POST['id']);
        $project->set_client_id($_POST['client_id']);
        $project->set_project_name(trim($_POST['project_name']));
        $project->set_project_type(trim($_POST['project_type']));
        $project->set_technology(trim($_POST['technology']));
        $project->set_description(trim($_POST['description']));
        $project->set_start_date($_POST['start_date']);
        $project->set_expected_delivery($_POST['expected_delivery']);
        $project->set_completed_date($_POST['completed_date']);
        $project->set_budget((float)$_POST['budget']);
        $project->set_advance_amount((float)$_POST['advance_amount']);


        // Auto Calculate Pending
        $project->set_pending_amount(
            (float)$_POST['budget'] - (float)$_POST['advance_amount']
        );

        $project->set_priority($_POST['priority']);
        $project->set_project_status($_POST['project_status']);

        if (method_exists($project, 'set_progress')) {
            $project->set_progress((int)$_POST['progress']);
        }

        if (method_exists($project, 'set_remarks')) {
            $project->set_remarks(trim($_POST['remarks']));
        }

        // Validation

        if (empty($_POST['client_id'])) {

            $_SESSION['error'] = "Please select a client.";

            break;
        }

        if (empty(trim($_POST['project_name']))) {

            $_SESSION['error'] = "Project Name is required.";

            break;
        }

        if ((float)$_POST['advance_amount'] > (float)$_POST['budget']) {

            $_SESSION['error'] = "Advance Amount cannot exceed Budget.";

            break;
        }

        if (DBclientproject::updateProject($project)) {

            $_SESSION['success'] = "Project updated successfully.";
        } else {

            $_SESSION['error'] = "Unable to update project.";
        }

        break;

    case "delete":

        if (DBclientproject::deleteProject($_POST['id'])) {

            $_SESSION['success'] = "Project deleted successfully.";
        } else {

            $_SESSION['error'] = "Unable to delete project.";
        }

        break;

    default:

        $_SESSION['error'] = "Invalid Request.";

        break;
}

header("Location: ../View/projects.php");
exit;
