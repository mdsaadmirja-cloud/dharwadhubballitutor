<?php
require_once "../../DB Operations/dbconnection.php";
class DBadmission
{
  public static function insert($admissionObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into admissions (`enquiry_id`,`Name`, `Phone`, `Email`, `DateofBirth`,`Gender`,`Qualification`,`Branch`,`Guardians_Name`,`Guardians_Phone`,`CoursesOpted`,`Address`,`AdhaarNo`,`AdhaarFile`,`PhotoFile`,`Resume`,`Courseid`) 
                values (" . $admissionObj->get_enqueryId() .
      ",'" . $admissionObj->get_name() .
      "','" . $admissionObj->get_phone() .
      "','" . $admissionObj->get_email() .
      "','" . $admissionObj->get_dateofbirth() .
      "','" . $admissionObj->get_gender() .
      "','" . $admissionObj->get_qualification() .
      "','" . $admissionObj->get_branch() .
      "','" . $admissionObj->get_guardiansname() .
      "','" . $admissionObj->get_guardiansphone() .
      "','" . $admissionObj->get_coursesopted() .
      "','" . $admissionObj->get_address() .
      "','" . $admissionObj->get_adhaarno() .
      "','" . $admissionObj->get_adhaarfile() .
      "','" . $admissionObj->get_photofile() .
      "','" . $admissionObj->get_resume() .
      "','" . $admissionObj->get_courseid() . "')";

    if ($connectionObj->query($sql) === TRUE) {
      if ($admissionObj->get_enqueryId() > 0) {
        $sql = "Update candidates Set status=0 where id=" . $admissionObj->get_enqueryId();
        $result = mysqli_query($db->getConnection(), $sql);
        if ($result) {
          echo "Record updated successfully";
        } else {
          echo "Error updating record: " . $connectionObj->error;
        }
      }
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function getAdmissionByEmail($email)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM admissions WHERE Email = '$email'";
    $result = mysqli_query($connectionObj, $sql);
    error_log($sql);
    if (mysqli_num_rows($result) > 0) {
      return mysqli_fetch_assoc($result);
    } else {
      return null;
    }
  }
  public static function viewadmission($viewObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "select * from admissions where id=$viewObj";
    error_log($sql);
    $result = mysqli_query($db->getConnection(), $sql);
    $view = new Admissions();
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $view->set_courseid($row['Courseid']);
      $view->set_id($row['id']);
      $view->set_name($row['Name']);
      $view->set_phone($row['Phone']);
      $view->set_email($row['Email']);
      $view->set_dateofbirth($row['DateofBirth']);
      $view->set_gender($row['Gender']);
      $view->set_qualification($row['Qualification']);
      $view->set_branch($row['Branch']);
      $view->set_guardiansname($row['Guardians_Name']);
      $view->set_guardiansphone($row['Guardians_Phone']);
      $view->set_coursesopted($row['CoursesOpted']);
      $view->set_address($row['Address']);
      $view->set_adhaarno($row['AdhaarNo']);
      $view->set_adhaarfile($row['AdhaarFile']);
      $view->set_photofile($row['PhotoFile']);
      $view->set_resume($row['Resume']);
      $view->set_CancellationReason($row['cancellationReason']);
      $view->set_Admissionstatus($row['admissionstatus']);
    } else {
      $view = NULL;
    }
    return $view;
  }
  public static function search()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $search = "";
    if (isset($_POST['submit']))
      $search = $_POST["search"];
    $sql = "SELECT id,Name,Email,Phone,Qualification From candidates where Name like '%$search%' ";
    $result = mysqli_query($db->getConnection(), $sql);
    $enquirylist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Admissions();
        $view->set_id($row['id']);
        $view->set_name($row['Name']);
        $view->set_phone($row['Phone']);
        $view->set_email($row['Email']);
        $view->set_qualification($row['Qualification']);
        array_push($enquirylist, $view);
      }
    } else {
      echo "0 results";
    }

    return $enquirylist;
  }

  public static function searchadmission()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $searchadmission = "";
    if (isset($_POST['submit']))
      $searchadmission = $_POST["search"];
    echo $searchadmission;
    $sql = "SELECT id,
       Name,
       createddate,
       Email,
       Phone,
       Qualification,
       Branch,
       Guardians_Phone,
       CoursesOpted,
       AdhaarNo,
       PhotoFile,
       Modified_Date
        From admissions 
       where Name like '%$searchadmission%' 
       ORDER BY id DESC";
    $result = mysqli_query($db->getConnection(), $sql);
    $admissionlist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Admissions();
        $view->set_id($row['id']);
        $view->setCreateddate(date('d-m-y', strtotime($row["createddate"])));
        $view->set_name($row['Name']);
        $view->set_phone($row['Phone']);
        $view->set_email($row['Email']);
        $view->set_qualification($row['Qualification']);
        $view->set_branch($row['Branch']);
        $view->set_guardiansphone($row['Guardians_Phone']);
        $view->set_coursesopted($row['CoursesOpted']);
        $view->set_adhaarno($row['AdhaarNo']);
        $view->set_photofile($row['PhotoFile']);
        $view->setModifiedDate(date('d.m.y', strtotime($row['Modified_Date'])));
        array_push($admissionlist, $view);
      }
    } else {
      echo "0 results";
    }
    return $admissionlist;
  }
  public static function selectall()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = 'SELECT * FROM admissions';
    $result = mysqli_query($db->getConnection(), $sql);
    $admissionlist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Admissions();
        $view->set_id($row['id']);
        $view->set_name($row['Name']);
        $view->set_phone($row['Phone']);
        $view->set_email($row['Email']);
        $view->set_dateofbirth($row['DateofBirth']);
        $view->set_qualification($row['Qualification']);
        $view->set_branch($row['Branch']);
        $view->set_guardiansname($row['Guardians_Name']);
        $view->set_guardiansphone($row['Guardians_Phone']);
        $view->set_coursesopted($row['CoursesOpted']);
        $view->set_address($row['Address']);
        $view->set_adhaarno($row['AdhaarNo']);
        $view->set_adhaarfile($row['AdhaarFile']);
        $view->set_photofile($row['PhotoFile']);
        $view->set_resume($row['Resume']);
        $view->setCreateddate(date('d-m-Y', strtotime($row["createddate"])));
        $view->setModifiedDate(date('d-m-Y', strtotime($row['Modified_Date'])));
        array_push($admissionlist, $view);
      }
    } else {
      echo "0 results";
    }
    return $admissionlist;
  }
  public static function selectByBranch($branch)
  {
    $db = ConnectDb::getInstance();

    if (empty($branch) || strtoupper($branch) == "ALL") {
      $sql = "SELECT * FROM admissions ORDER BY id DESC";
    } else {
      $branch = mysqli_real_escape_string($db->getConnection(), $branch);
      $sql = "SELECT * FROM admissions WHERE Branch='$branch' ORDER BY id DESC";
    }

    $result = mysqli_query($db->getConnection(), $sql);


    $admissionlist = [];

    while ($row = mysqli_fetch_assoc($result)) {

      $view = new Admissions();

      $view->set_id($row['id']);
      $view->set_name($row['Name']);
      $view->set_phone($row['Phone']);
      $view->set_email($row['Email']);
      $view->set_dateofbirth($row['DateofBirth']);
      $view->set_qualification($row['Qualification']);
      $view->set_branch($row['Branch']);
      $view->set_guardiansname($row['Guardians_Name']);
      $view->set_guardiansphone($row['Guardians_Phone']);
      $view->set_coursesopted($row['CoursesOpted']);
      $view->set_address($row['Address']);
      $view->set_adhaarno($row['AdhaarNo']);
      $view->set_adhaarfile($row['AdhaarFile']);
      $view->set_photofile($row['PhotoFile']);
      $view->set_resume($row['Resume']);
      $view->setCreateddate(date('d-m-Y', strtotime($row["createddate"])));
      $view->setModifiedDate(date('d-m-Y', strtotime($row['Modified_Date'])));

      $admissionlist[] = $view;
    }

    return $admissionlist;
  }
  public static function updateadmission($admission)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $status = true;
    $sql = "UPDATE admissions SET Name='" . $admission->get_name() .
      "', Phone='" . $admission->get_phone() .
      "', Email='" . $admission->get_email() .
      "', DateofBirth='" . $admission->get_dateofbirth() .
      "',Qualification='" . $admission->get_qualification() .
      "',Branch='" . $admission->get_branch() .
      "',Guardians_Name='" . $admission->get_guardiansname() .
      "', Guardians_Phone='" . $admission->get_guardiansphone() .
      "',CoursesOpted='" . $admission->get_coursesopted() .
      "',Address='" . $admission->get_address() .
      "',AdhaarNo='" . $admission->get_adhaarno() .
      "',Courseid='" . $admission->get_courseid() .
      "',cancellationReason='" . $admission->get_CancellationReason() .
      "',admissionstatus='" . $admission->get_Admissionstatus();
    if (!empty($admission->get_adhaarfile())) {
      $sql .= "',AdhaarFile='" . $admission->get_adhaarfile();
    }

    if (!empty($admission->get_photofile())) {
      $sql .= "',PhotoFile='" . $admission->get_photofile();
    }

    if (!empty($admission->get_resume())) {
      $sql .= "',Resume ='" . $admission->get_resume();
    }


    $sql .= "' where id='" . $admission->get_id() . "' ";
    error_log($sql);
    $result = mysqli_query($db->getConnection(), $sql);
    if ($connectionObj->query($sql) === TRUE) {
      return $status = true;
    } else {
      return $status = false;
    }
  }
}
