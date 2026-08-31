<?php

require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../DB Operations/VendorOps.php";
require_once "../model/VendorModel.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/vendors.php");
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {

    /* ==========================
       ADD VENDOR
    ========================== */
    case "add":

        $vendor = new VendorModel();

        $vendor->setName(trim($_POST['name']));
        $vendor->setContactPerson(trim($_POST['contact_person']));
        $vendor->setPhone(trim($_POST['phone']));
        $vendor->setEmail(trim($_POST['email']));
        $vendor->setGstNumber(trim($_POST['gst_number']));
        $vendor->setAddress(trim($_POST['address']));
        $vendor->setCity(trim($_POST['city']));
        $vendor->setState(trim($_POST['state']));
        $vendor->setPincode(trim($_POST['pincode']));
        $vendor->setBranch($_POST['branch']);
        $vendor->setNotes(trim($_POST['notes']));
        $vendor->setStatus($_POST['status']);
        $vendor->setCreatedBy($_SESSION['userid']); // Change if your session variable is different

        if (empty(trim($_POST['name']))) {
            $_SESSION['error'] = "Vendor Name is required.";
            header("Location: ../View/vendors.php");
            exit;
        }

        if (!preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
            $_SESSION['error'] = "Phone number must contain exactly 10 digits.";
            header("Location: ../View/vendors.php");
            exit;
        }

        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email address.";
            header("Location: ../View/vendors.php");
            exit;
        }

        if (!empty($_POST['pincode']) && !preg_match('/^[0-9]{6}$/', $_POST['pincode'])) {
            $_SESSION['error'] = "Invalid pincode.";
            header("Location: ../View/vendors.php");
            exit;
        }

        if (DBVendor::addVendor($vendor)) {
            $_SESSION['success'] = "Vendor added successfully.";
        } else {
            $_SESSION['error'] = "Unable to add vendor.";
        }

        break;


    /* ==========================
       UPDATE VENDOR
    ========================== */
    case "update":

        $vendor = new VendorModel();

        $vendor->setId($_POST['id']);
        $vendor->setName(trim($_POST['name']));
        $vendor->setContactPerson(trim($_POST['contact_person']));
        $vendor->setPhone(trim($_POST['phone']));
        $vendor->setEmail(trim($_POST['email']));
        $vendor->setGstNumber(trim($_POST['gst_number']));
        $vendor->setAddress(trim($_POST['address']));
        $vendor->setCity(trim($_POST['city']));
        $vendor->setState(trim($_POST['state']));
        $vendor->setPincode(trim($_POST['pincode']));
        $vendor->setBranch($_POST['branch']);
        $vendor->setNotes(trim($_POST['notes']));
        $vendor->setStatus($_POST['status']);

        if (DBVendor::updateVendor($vendor)) {
            $_SESSION['success'] = "Vendor updated successfully.";
        } else {
            $_SESSION['error'] = "Unable to update vendor.";
        }

        break;


    /* ==========================
       DELETE VENDOR
    ========================== */
    case "delete":

        if (DBVendor::deleteVendor($_POST['id'])) {
            $_SESSION['success'] = "Vendor deleted successfully.";
        } else {
            $_SESSION['error'] = "Unable to delete vendor.";
        }

        break;


    default:

        $_SESSION['error'] = "Invalid request.";
        break;
}

header("Location: ../View/vendors.php");
exit;
