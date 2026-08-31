<?php
$config= require_once("../../views/config.php");
require "../model/subcategorymodel.php";
require "../Utilities/Sanitization.php";
include "../dblayer/subcategoryOps.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['itemsubcatid'])) {
    $subcat = new Subcategory();
    $subcat->setSubCategoryName(Sanitization::test_input($_POST["itemsubcatname"]));
    $subcat->setSubCategoryDescription(Sanitization::test_input($_POST["itemsubcatdescription"]));
    $subcat->setSubCategoryCreatedBy(Sanitization::test_input($_POST["itemsubcatcreatedby"]));
    $subcat->setSubCategoryModifiedBy(Sanitization::test_input($_POST["itemsubcatmodifiedby"]));
    $subcat->setSubCategoryId(Sanitization::test_input($_POST["itemsubcatid"]));
    $subcat->setMappedCategories($_POST["category"]);
    error_log($_POST["category"]);
    DBsubcategory::update($subcat);
  } elseif ($_POST["action"] == 'delete') {
    error_log($_POST["id"]);
    DBsubcategory::delete($_POST['id']);
  } else {
    $subcat = new Subcategory();
    $subcat->setSubCategoryName(Sanitization::test_input($_POST["itemsubcatname"]));
    $subcat->setSubCategoryDescription(Sanitization::test_input($_POST["itemsubcatdescription"]));
    $subcat->setMappedCategories($_POST["category"]);
    $subcat->setSubCategoryCreatedBy(Sanitization::test_input($_POST["itemsubcatcreatedby"]));
    $subcat->setSubCategoryModifiedBy(Sanitization::test_input($_POST["itemsubcatmodifiedby"]));
    DBsubcategory::insert($subcat);
    error_log($_POST["category"]);
  }
  header("location:../views/subcategory.php");
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["subCatId"])) {
    error_log($_GET["subCatId"]);
    DBsubcategory::getMappedSubCategories($_GET["subCatId"]);
  } else {
    DBsubcategory::selectsubcategory();
  }
}
