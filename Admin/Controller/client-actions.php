<?php

require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../DB Operations/ClientOps.php";
require_once "../model/ClientModel.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/clients.php");
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {

    case "add":
        $client = new Client();

        $client->set_client_code(trim($_POST['client_code']));
        $client->set_branch_id($_POST['branch_id']);
        $client->set_company_name(trim($_POST['company_name']));
        $client->set_client_name(trim($_POST['client_name']));
        $client->set_mobile(trim($_POST['mobile']));
        $client->set_alternate_mobile(trim($_POST['alternate_mobile']));
        $client->set_email(trim($_POST['email']));
        $client->set_website(trim($_POST['website']));
        $client->set_gst_number(trim($_POST['gst_number']));
        $client->set_address(trim($_POST['address']));
        $client->set_city(trim($_POST['city']));
        $client->set_state(trim($_POST['state']));
        $client->set_pincode(trim($_POST['pincode']));
        $client->set_industry(trim($_POST['industry']));
        $client->set_status(trim($_POST['status']));
        $client->set_notes(trim($_POST['notes']));

        /* ==========================
        VALIDATION
        ========================== */

        if (empty(trim($_POST['company_name']))) {
            $_SESSION['error'] = "Company Name is required.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (empty(trim($_POST['client_name']))) {
            $_SESSION['error'] = "Client Name is required.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (!preg_match('/^[0-9]{10}$/', $_POST['mobile'])) {
            $_SESSION['error'] = "Mobile number must contain exactly 10 digits.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (
            !empty($_POST['email']) &&
            !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {

            $_SESSION['error'] = "Invalid email address.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (
            !empty($_POST['website']) &&
            !filter_var($_POST['website'], FILTER_VALIDATE_URL)
        ) {

            $_SESSION['error'] = "Invalid website URL.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (
            !empty($_POST['pincode']) &&
            !preg_match('/^[0-9]{6}$/', $_POST['pincode'])
        ) {

            $_SESSION['error'] = "Invalid pincode.";
            header("Location: ../View/clients.php");
            exit;
        }

        /* Optional Duplicate Mobile Check */

        if (DBclient::mobileExists($_POST['mobile'])) {

            $_SESSION['error'] = "Mobile number already exists.";
            header("Location: ../View/clients.php");
            exit;
        }

        /* ==========================
        SAVE CLIENT
        ========================== */

        if (DBclient::addClient($client)) {

            $_SESSION['success'] = "Client added successfully.";
        } else {

            $_SESSION['error'] = "Unable to add client.";
        }

        break;

    case "update":
        $client = new Client();

        $client->set_id($_POST['id']);
        $client->set_branch_id($_POST['branch_id']);
        $client->set_company_name(trim($_POST['company_name']));
        $client->set_client_name(trim($_POST['client_name']));
        $client->set_mobile(trim($_POST['mobile']));
        $client->set_alternate_mobile(trim($_POST['alternate_mobile']));
        $client->set_email(trim($_POST['email']));
        $client->set_website(trim($_POST['website']));
        $client->set_gst_number(trim($_POST['gst_number']));
        $client->set_address(trim($_POST['address']));
        $client->set_city(trim($_POST['city']));
        $client->set_state(trim($_POST['state']));
        $client->set_pincode(trim($_POST['pincode']));
        $client->set_industry(trim($_POST['industry']));
        $client->set_status(trim($_POST['status']));
        $client->set_notes(trim($_POST['notes']));

        if (empty(trim($_POST['company_name']))) {
            $_SESSION['error'] = "Company Name is required.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (empty(trim($_POST['client_name']))) {
            $_SESSION['error'] = "Client Name is required.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (!preg_match('/^[0-9]{10}$/', $_POST['mobile'])) {
            $_SESSION['error'] = "Mobile number must contain exactly 10 digits.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (
            !empty($_POST['email']) &&
            !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {

            $_SESSION['error'] = "Invalid email address.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (
            !empty($_POST['website']) &&
            !filter_var($_POST['website'], FILTER_VALIDATE_URL)
        ) {

            $_SESSION['error'] = "Invalid website URL.";
            header("Location: ../View/clients.php");
            exit;
        }
        if (
            !empty($_POST['pincode']) &&
            !preg_match('/^[0-9]{6}$/', $_POST['pincode'])
        ) {

            $_SESSION['error'] = "Invalid pincode.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (DBclient::mobileExists($_POST['mobile'], $_POST['id'])) {

            $_SESSION['error'] = "Mobile number already exists.";
            header("Location: ../View/clients.php");
            exit;
        }

        if (DBclient::updateClient($client)) {

            $_SESSION['success'] = "Client updated successfully.";
        } else {

            $_SESSION['error'] = "Unable to update client.";
        }

        break;

    case "delete":
        $id = (int)$_POST['id'];

        if (DBclient::deleteClient($id)) {

            $_SESSION['success'] = "Client deleted successfully.";
        } else {

            $_SESSION['error'] = "Unable to delete client.";
        }

        break;

    default:
        $_SESSION['error'] = "Invalid request.";
        break;
}

header("Location: ../View/clients.php");
exit;
