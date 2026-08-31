<?php

/**
 * chatbotlead-actions.php
 * Redirect-based controller for the Chatbot Leads module — same pattern as
 * the rest of Admin/Controller (e.g. newfollowup.php / newenquiry.php):
 * receive POST, validate CSRF, run the DB op, redirect back to the view.
 */

require_once "../session.php";
require_once "../../Admin/DB Operations/ChatbotLeadOps.php";

// ---- CSRF check (same convention as the rest of the app) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request. Please refresh the page and try again.");
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'update_status':
        $id     = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'new';
        DBchatbotlead::updateStatus($id, $status);
        $_SESSION['toast'] = "Lead status updated.";
        break;

    case 'update_notes':
        $id    = (int) ($_POST['id'] ?? 0);
        $notes = $_POST['notes'] ?? '';
        DBchatbotlead::updateNotes($id, $notes);
        $_SESSION['toast'] = "Note saved.";
        break;

    case 'update_lead':

        $id = (int)$_POST['id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $interest = $_POST['interest'];

        DBchatbotlead::updateLead($id, $name, $phone, $interest);

        $_SESSION['toast'] = "Lead Updated Successfully";

        header("Location: ../View/enquiries.php");
        exit;

    case 'add_lead':
        $name     = $_POST['name'] ?? '';
        $phone    = $_POST['phone'] ?? '';
        $interest = $_POST['interest'] ?? '';
        $source   = $_POST['source'] ?? 'Manual Entry';
        if (trim($name) !== '' && trim($phone) !== '') {
            DBchatbotlead::insertLead($name, $phone, $interest, $source);
            $_SESSION['toast'] = "New lead added.";
        } else {
            $_SESSION['toast'] = "Name and phone are required — lead not added.";
        }
        break;

    case 'delete_lead':
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        DBchatbotlead::deleteLead($id);
        $_SESSION['toast'] = "Lead deleted.";
        break;

    case 'move_to_enquiry':

        $id = (int)$_GET['id'];

        if (DBchatbotlead::moveToEnquiry($id)) {
            $_SESSION['toast'] = "Lead moved successfully.";
        } else {
            $_SESSION['toast'] = "Lead already exists.";
        }

        break;

    case 'converted':
        echo '<span class="badge bg-success">
            <i class="fas fa-check-circle"></i>
            Moved to Enquiry
          </span>';
        break;
    default:
        $_SESSION['toast'] = "Unknown action.";
        break;
}

header("Location: ../View/enquiries.php?tab=chatbot");
exit;
