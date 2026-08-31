<?php
require "../model/companyModel.php";
require "../Utilities/Sanitization.php";
include "../../Admin/DB Operations/companyOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['editedid'])) {
        
        $company = new Company();
        $company->setId(Sanitization::test_input($_POST["editedid"]));
        $company->setDate(Sanitization::test_input($_POST["date"]));
        $company->setName(Sanitization::test_input($_POST["name"]));
        $company->setEmail(Sanitization::test_input($_POST["email"]));
        $company->setPhone(Sanitization::test_input($_POST["phone"]));
        $company->setLocation(Sanitization::test_input($_POST["location"]));
        $company->setApproachedby(Sanitization::test_input($_POST["approachedby"]));
        $company->setCompanyname(Sanitization::test_input($_POST["companyname"]));
        
        DBCompany::update($company);
    } else if ($_POST["action"] == 'delete') {
        DBCompany::delete($_POST["id"]);
    } else {
        $company = new Company();
        $company->setDate(Sanitization::test_input($_POST["Date"]));
        $company->setName(Sanitization::test_input($_POST["name"]));
        $company->setEmail(Sanitization::test_input($_POST["email"]));
        $company->setPhone(Sanitization::test_input($_POST["phone"]));
        $company->setLocation(Sanitization::test_input($_POST["location"]));
        $company->setApproachedby(Sanitization::test_input($_POST["Approachedby"]));
        $company->setCompanyname(Sanitization::test_input($_POST["companyname"]));

        DBCompany::insert($company);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {

}


header("location: ../View/company.php");