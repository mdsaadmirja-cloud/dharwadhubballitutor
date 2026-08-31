<?php

session_start();

require_once "../../DB Operations/dbconnection.php";

require_once "../DB Operations/ProjectPaymentOps.php";
require_once "../model/ProjectPaymentModel.php";

if (!isset($_POST['action'])) {
    header("Location: ../View/payments.php");
    exit;
}

switch ($_POST['action']) {

    case "add":

        $payment = new ProjectPayment();

        $payment->set_project_id($_POST['project_id']);
        $payment->set_payment_date($_POST['payment_date']);
        $payment->set_amount((float)$_POST['amount']);
        $payment->set_payment_mode($_POST['payment_mode']);
        $payment->set_payment_type($_POST['payment_type']);
        $payment->set_transaction_no(trim($_POST['transaction_no']));
        $payment->set_remarks(trim($_POST['remarks']));

        // Validation

        if (empty($_POST['project_id'])) {

            $_SESSION['error'] = "Please select a project.";

            break;
        }

        if ((float)$_POST['amount'] <= 0) {

            $_SESSION['error'] = "Invalid payment amount.";

            break;
        }

        if (DBprojectpayment::addPayment($payment)) {

            $_SESSION['success'] = "Payment added successfully.";
        } else {

            $_SESSION['error'] = "Unable to save payment.";
        }

        break;

    case "update":

        $payment = new ProjectPayment();

        $payment->set_id($_POST['id']);
        $payment->set_payment_date($_POST['payment_date']);
        $payment->set_amount((float)$_POST['amount']);
        $payment->set_payment_mode($_POST['payment_mode']);
        $payment->set_payment_type($_POST['payment_type']);
        $payment->set_transaction_no(trim($_POST['transaction_no']));
        $payment->set_remarks(trim($_POST['remarks']));

        if (DBprojectpayment::updatePayment($payment)) {

            $_SESSION['success'] = "Payment updated successfully.";
        } else {

            $_SESSION['error'] = "Unable to update payment.";
        }

        break;

    case "delete":

        if (DBprojectpayment::deletePayment($_POST['id'])) {

            $_SESSION['success'] = "Payment deleted successfully.";
        } else {

            $_SESSION['error'] = "Unable to delete payment.";
        }

        break;

    default:

        $_SESSION['error'] = "Invalid request.";

        break;
}

header("Location: ../View/payments.php");
exit;
