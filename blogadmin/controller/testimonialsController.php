<?php
$config = require_once("../../views/config.php");
require_once("../model/testimonialsModel.php");
require_once("../dblayer/testimonialsOps.php");
require_once "../Utilities/Sanitization.php";
require_once "../Utilities/Helper.php";
require_once("../../vendor/autoload.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Id'])) 
    {
        $testimonials = new Testimonials();
        $testimonials->setId(Sanitization::test_input($_POST["Id"]));
        $testimonials->setName(Sanitization::test_input($_POST["Name"]));
        $testimonials->setDescription(Sanitization::test_input($_POST["Description"]));
        $testimonials->setRateNow(Sanitization::test_input($_POST["RateNow"]));
        $testimonials->setModifiedby(Sanitization::test_input($_POST["Modifiedby"]));
        $testimonials->setcreatedby(Sanitization::test_input($_POST["createdby"]));
        if (isset($_FILES["Image"])) {
            $filetoupload = $_FILES["Image"];
            Helper::fileupload($filetoupload, "../img/Post/");
            $testimonials->setImage($_FILES["Image"]['name']);
        } 
        DBtestimonials::update($testimonials);         
    } else if ($_POST['action'] == "delete") {       
        error_log($_POST["id"]);
        DBtestimonials::delete($_POST["id"]);
    } else {        
        $testimonials = new Testimonials();
        $testimonials->setName(Sanitization::test_input($_POST["Name"]));
        $testimonials->setDescription(Sanitization::test_input($_POST["Description"]));
        $testimonials->setRateNow(Sanitization::test_input($_POST["RateNow"]));
        $testimonials->setModifiedby(Sanitization::test_input($_POST["Modifiedby"]));
        $testimonials->setcreatedby(Sanitization::test_input($_POST["createdby"]));
        $testimonials->setImageAlternateText(Sanitization::test_input($_POST["ImageAlternateText"]));
        if (isset($_FILES["Image"])) {
            $filetoupload = $_FILES["Image"];
            Helper::fileupload($filetoupload, "../img/Post/");
            $testimonials->setImage($_FILES["Image"]['name']);
        }
        DBtestimonials::insert($testimonials);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location:../views/Testimonials.php");
