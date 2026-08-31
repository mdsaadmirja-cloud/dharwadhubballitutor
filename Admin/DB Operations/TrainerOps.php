<?php
require_once "../../DB Operations/dbconnection.php";
class DBtrainer
{
  public static function insert($trainerObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $shiftID = !empty($trainerObj->get_shiftid()) ? "'" . (int)$trainerObj->get_shiftid() . "'" : "NULL";

    $sql = "INSERT INTO trainers
    (
        StaffCode,
        Name,
        Phone,
        Email,
        Qualification,
        BranchId,
        Department,
        Designation,
        FingerprintID,
        JoiningDate,
        Address,
        AdhaarNo,
        AdhaarFile,
        PhotoFile,
        Resume,
        Status,
        WorkingHours,
        ShiftID,
        bank_name,
        account_holder_name,
        account_number,
        account_type,
        ifsc_code,
        branch_name,
        bank_address
    )
    VALUES
(
    '" . $trainerObj->get_staffcode() . "',
    '" . $trainerObj->get_name() . "',
    '" . $trainerObj->get_phone() . "',
    '" . $trainerObj->get_email() . "',
    '" . $trainerObj->get_qualification() . "',
    '" . $trainerObj->get_branchid() . "',
    '" . $trainerObj->get_department() . "',
    '" . $trainerObj->get_designation() . "',
    '" . $trainerObj->get_fingerprintid() . "',
    '" . $trainerObj->get_joiningdate() . "',
    '" . $trainerObj->get_address() . "',
    '" . $trainerObj->get_adhaarno() . "',
    '" . $trainerObj->get_adhaarfile() . "',
    '" . $trainerObj->get_photofile() . "',
    '" . $trainerObj->get_resume() . "',
    '" . $trainerObj->get_status() . "',
    '" . $trainerObj->get_workinghours() . "',
    $shiftID,
    '" . $trainerObj->get_bank_name() . "',
    '" . $trainerObj->get_account_holder_name() . "',
    '" . $trainerObj->get_account_number() . "',
    '" . $trainerObj->get_account_type() . "',
    '" . $trainerObj->get_ifsc_code() . "',
    '" . $trainerObj->get_branch_name() . "',
    '" . $trainerObj->get_bank_address() . "'
)";

    if ($connectionObj->query($sql) === TRUE) {
      return mysqli_insert_id($connectionObj);
    } elseif ($connectionObj->errno == 1062) {
      return "DUPLICATE";
    } else {
      die("SQL Error : " . $connectionObj->error);
    }
  }
  public static function getAllTrainers()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $sql = "SELECT id, Name, BranchId, ShiftID FROM trainers WHERE Status = 'Active' ORDER BY Name ASC";
    $result = mysqli_query($connectionObj, $sql);

    $records = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $records[] = $row;
    }
    return $records;
  }

  public static function getShiftsList()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $sql = "SELECT * FROM shifts WHERE Status = 'Active' ORDER BY StartTime ASC";
    $result = mysqli_query($connectionObj, $sql);

    $records = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $records[] = $row;
    }
    return $records;
  }
  public static function updateStaffCode($id, $staffCode)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $sql = "UPDATE trainers
            SET StaffCode='$staffCode'
            WHERE id='$id'";

    return $connectionObj->query($sql);
  }
  public static function searchtrainer()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $searchtrainer = "";
    if (isset($_POST['submit']))
      $searchtrainer = $_POST["search"];
    echo $searchtrainer;
    $sql = "SELECT
id,
StaffCode,
Name,
Email,
Phone,
Qualification,
BranchId,
Department,
Designation,
FingerprintID,
Status,
AdhaarNo,
PhotoFile
FROM trainers
WHERE Name LIKE '%$searchtrainer%'";
    $result = mysqli_query($db->getConnection(), $sql);
    $trainerslist = [];
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Trainer();
        $view->set_id($row['id']);
        $view->set_name($row['Name']);
        $view->set_phone($row['Phone']);
        $view->set_email($row['Email']);
        $view->set_qualification($row['Qualification']);
        $view->set_adhaarno($row['AdhaarNo']);
        $view->set_photofile($row['PhotoFile']);
        $view->set_staffcode($row['StaffCode']);
        $view->set_branchid($row['BranchId']);
        $view->set_department($row['Department']);
        $view->set_designation($row['Designation']);
        $view->set_fingerprintid($row['FingerprintID']);
        $view->set_status($row['Status']);
        array_push($trainerslist, $view);
      }
    } else {
      echo "0 results";
    }
    return $trainerslist;
  }
  public static function viewtrainer($viewObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "select * from trainers where id=$viewObj";
    $result = mysqli_query($db->getConnection(), $sql);
    $view = new Trainer();
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $view->set_id($row['id']);
      $view->set_name($row['Name']);
      $view->set_phone($row['Phone']);
      $view->set_email($row['Email']);
      $view->set_qualification($row['Qualification']);
      $view->set_address($row['Address']);
      $view->set_adhaarno($row['AdhaarNo']);
      $view->set_adhaarfile($row['AdhaarFile']);
      $view->set_photofile($row['PhotoFile']);
      $view->set_resume($row['Resume']);
      $view->set_bank_name($row['bank_name']);
      $view->set_account_holder_name($row['account_holder_name']);
      $view->set_account_number($row['account_number']);
      $view->set_account_type($row['account_type']);
      $view->set_ifsc_code($row['ifsc_code']);
      $view->set_branch_name($row['branch_name']);
      $view->set_bank_address($row['bank_address']);
      $view->set_staffcode($row['StaffCode']);
      $view->set_branchid($row['BranchId']);
      $view->set_department($row['Department']);
      $view->set_designation($row['Designation']);
      $view->set_fingerprintid($row['FingerprintID']);
      $view->set_joiningdate($row['JoiningDate']);
      $view->set_status($row['Status']);
      $sql = "select CName from courses as C join trainercoursemapping as TCM on C.id=TCM.courseid where TCM.trainerid=(" . $viewObj . ")";
      $result = mysqli_query($db->getConnection(), $sql);
      if (mysqli_num_rows($result) > 0) {
        $courseslist = [];
        while ($row = mysqli_fetch_assoc($result)) {
          array_push($courseslist, $row['CName']);
        }
        $view->set_coursesassigned($courseslist);
      }
    } else {
      $view = NULL;
    }
    return $view;
  }
  public static function update($trainerObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $sql = "UPDATE trainers SET
        Name='" . $trainerObj->get_name() . "',
        Phone='" . $trainerObj->get_phone() . "',
        Email='" . $trainerObj->get_email() . "',
        Qualification='" . $trainerObj->get_qualification() . "',
        Address='" . $trainerObj->get_address() . "',
        AdhaarNo='" . $trainerObj->get_adhaarno() . "',
        AdhaarFile='" . $trainerObj->get_adhaarfile() . "',
        Resume='" . $trainerObj->get_resume() . "',
        bank_name='" . $trainerObj->get_bank_name() . "',
        account_holder_name='" . $trainerObj->get_account_holder_name() . "',
        account_number='" . $trainerObj->get_account_number() . "',
        account_type='" . $trainerObj->get_account_type() . "',
        ifsc_code='" . $trainerObj->get_ifsc_code() . "',
        branch_name='" . $trainerObj->get_branch_name() . "',
        bank_address='" . $trainerObj->get_bank_address() . "'
        WHERE id='" . $trainerObj->get_id() . "'";

    return $connectionObj->query($sql);
  }
}
