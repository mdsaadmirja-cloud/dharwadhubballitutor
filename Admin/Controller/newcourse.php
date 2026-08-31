<?php
//require "session.php";
require "../model/Coursesmodel.php";
require "../Utilities/Sanitization.php";
require "../Utilities/Helper.php";
// require "../Admin/navbar.php";

require "../DB Operations/CoursesOps.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $admit=new Courses();
    $admit->set_cname(Sanitization::test_input($_POST["cname"]));
    $admit->set_ctype(Sanitization::test_input($_POST["ctype"]));
    $admit->set_cduration(Sanitization::test_input($_POST["cduration"]));
  
    DBcourse::insert($admit);
  }
?>
<html>

    <head>
        <title> New course </title>
    </head>

    <body>
        <?php 
 header("location:../View/courses.php");
?>


    </body>

</html>