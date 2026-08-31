<?php
$config= require_once("../../views/config.php");
require "../model/sliderImageModel.php";
require "../Utilities/Sanitization.php";
include "../dblayer/sliderImageOps.php";
require_once "../Utilities/Helper.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["action"] == 'delete') {
    error_log($_POST["id"]);
    DBsliderImageFile::delete($_POST["id"]);
  } else {
    $designFile = new sliderImage();
    $designFile->setImageFileCaption(Sanitization::test_input($_POST["designFileDescription"]));
    $designFile->setCreatedby(Sanitization::test_input($_POST["createdby"]));
    $designFile->setModifiedby(Sanitization::test_input($_POST["modifiedby"]));
    $designFile->setImageAlternateText(Sanitization::test_input($_POST["alternateText"]));
    if (isset($_FILES["designFilePath"])) {
      $filetoupload = $_FILES["designFilePath"];
      Helper::fileupload($filetoupload, "../img/Slider/");
      $designFile->setImage($_FILES["designFilePath"]['name']);
    }
    DBsliderImageFile::insert($designFile);
  }
  header("location:../blogadmin/views/sliderImage.php");
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET['id'])) {
    DBsliderImageFile::readAll($_GET['id']);
  }
}
