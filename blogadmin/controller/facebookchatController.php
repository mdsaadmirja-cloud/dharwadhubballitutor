<?php
$config = require_once("../../views/config.php");
require "../model/facebookchatModel.php";
require "../Utilities/Sanitization.php";
include "../dblayer/facebookchatOps.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Id'])) {
        $facebook = new Facebook();
        $facebook->setPluginCode(Sanitization::test_input($_POST["PluginCode"]));

        DBfacebook::update($facebook);
    } else if ($_POST["action"] == 'delete') {
        DBfacebook::delete($_POST["id"]);
    } else {
        $facebook = new Facebook();
        $facebook->setPluginCode(Sanitization::test_input($_POST["PluginCode"]));

        DBfacebook::insert($facebook);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
     DBfacebook::getPlugin();
}
header("location:../views/facebookchat.php");
