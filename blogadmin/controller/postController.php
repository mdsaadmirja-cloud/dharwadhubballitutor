<?php
$config= require_once("../../views/config.php");
require_once("../model/postModel.php");
require_once("../dblayer/postOps.php");
require_once "../Utilities/Sanitization.php";
require_once "../Utilities/Helper.php";
require_once("../../vendor/autoload.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['postId'])) {
    $post = new Post();
    $post->setPostId(Sanitization::test_input($_POST["postId"]));
    $post->setPostTitle(Sanitization::test_input($_POST["title"]));
    $post->setTitleTag(Sanitization::test_input($_POST["titleTag"]));
    $post->setLinkUnder(Sanitization::test_input($_POST["LinkUnder"]));
$onHome = isset($_POST["onHome"]) ? Sanitization::test_input($_POST["onHome"]) : 0;
error_log($onHome);
$post->setOnHome($onHome);
    $post->setPostUrl(Sanitization::test_input($_POST["postURL"]));
    $quill_json = str_replace("'", "''", $_POST['hidden_element']);
    //error_log($quill_json);
    try {
      $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
      $result = $quill->render();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    error_log($result);
    $post->setPostDescription($result);
    $post->setKeywords(Sanitization::test_input($_POST["Keywords"]));
$categories = isset($_POST["category"]) ? $_POST["category"] : [];
$post->setMappedSubCategory($categories);
    $post->setPostCreatedBy(Sanitization::test_input($_POST["postcreatedby"]));
    $post->setModifiedBy(Sanitization::test_input($_POST["postmodifiedby"]));
    $post->setAltTextImage(Sanitization::test_input($_POST["alttext"]));
    if (isset($_FILES["image"])) {
      $filetoupload = $_FILES["image"];
      Helper::fileupload($filetoupload, "../img/Post/");
      $post->setImage($_FILES["image"]['name']);
    }
    DBpost::update($post);
  } else if ($_POST['action'] == "delete") {
    error_log($_POST["id"]);
    DBpost::delete($_POST["id"]);
  } else {
    $post = new Post();
    $post->setPostTitle(Sanitization::test_input($_POST["title"]));
    $post->setTitleTag(Sanitization::test_input($_POST["titleTag"]));
    $post->setPostUrl(Sanitization::test_input($_POST["postURL"]));

    error_log($_POST["onHome"]);
    $post->setOnHome(Sanitization::test_input($_POST["onHome"]));

    $quill_json = str_replace("'", "''", $_POST['hidden_element']);
    try {
      $quill = new DBlackborough\Quill\Render($quill_json, 'HTML');
      $result = $quill->render();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $post->setPostDescription($result);
    $post->setKeywords(Sanitization::test_input($_POST["Keywords"]));
    $post->setMappedSubCategory($_POST["category"]);
    $post->setLinkUnder(Sanitization::test_input($_POST["LinkUnder"]));

    $post->setPostCreatedBy(Sanitization::test_input($_POST["postcreatedby"]));
    $post->setModifiedBy(Sanitization::test_input($_POST["postmodifiedby"]));
    $post->setAltTextImage(Sanitization::test_input($_POST["alttext"]));
    if (isset($_FILES["image"])) {
      $filetoupload = $_FILES["image"];
      Helper::fileupload($filetoupload, "../img/Post/");
      $post->setImage($_FILES["image"]['name']);
    }
    DBpost::insert($post);
  }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
}
header("location:../views/post.php");
