<?php
include "../../Admin/session.php";
require "../../Admin/model/Trainermodel.php";
require "../../Utilities/Sanitization.php";
require "../../Admin/Utilities/Helper.php";
require "../../Admin/DB Operations/TrainerOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get existing trainer
    $trainer = DBtrainer::viewtrainer((int)$_POST["id"]);
    $trainer->set_id((int)$_POST["id"]);

    $trainer->set_name(Sanitization::test_input($_POST["name"]));
    $trainer->set_phone(Sanitization::test_input($_POST["phone"]));
    $trainer->set_email(Sanitization::test_input($_POST["email"]));
    $trainer->set_qualification(Sanitization::test_input($_POST["qualification"]));
    $trainer->set_address(Sanitization::test_input($_POST["address"]));
    $trainer->set_adhaarno(Sanitization::test_input($_POST["adhaarno"]));
    $trainer->set_bank_name($_POST['bank_name']);
    $trainer->set_account_holder_name($_POST['account_holder_name']);
    $trainer->set_account_number($_POST['account_number']);
    $trainer->set_account_type($_POST['account_type']);
    $trainer->set_ifsc_code($_POST['ifsc_code']);
    $trainer->set_branch_name($_POST['branch_name']);
    $trainer->set_bank_address($_POST['bank_address']);

    // Upload new Aadhaar if selected
    if ($_FILES['adhaarfile']['size'] != 0 && $_FILES['adhaarfile']['error'] == 0) {

        if (Helper::fileupload($_FILES["adhaarfile"])) {
            $trainer->set_adhaarfile($_FILES["adhaarfile"]["name"]);
        }
    }

    // Upload new Resume if selected
    if ($_FILES['resume']['size'] != 0 && $_FILES['resume']['error'] == 0) {

        if (Helper::fileupload($_FILES["resume"])) {
            $trainer->set_resume($_FILES["resume"]["name"]);
        }
    }
    if ($_FILES['photofile']['size'] != 0 && $_FILES['photofile']['error'] == 0) {

        if (Helper::fileupload($_FILES["photofile"])) {
            $trainer->set_photofile($_FILES["photofile"]["name"]);
        }
    }


    if (DBtrainer::update($trainer)) {
        header("Location: viewtrainer.php?id=" . $trainer->get_id() . "&updated=1");
        exit;
    } else {
        die("Unable to update trainer.");
    }
}
