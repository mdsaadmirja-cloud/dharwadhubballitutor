<?php
// require "../Admin/session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../../Admin/model/Admissionsmodel.php";
require_once "../../Admin/model/Feesmodel.php";
class DBfees
{
  public static function insert($feesObj)
  
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    if ($feesObj->get_duedate() == "") {
      $sql = "insert into fees (`Admissionid`,`Courseid`,`TotalFees`, `PaidFees`,`PendingFees`, `Feesplan`,`PaymentMode`,`PaymentDescription`,`feesreceipt`,`GstRate`,`GstAmount`,`BaseAmount`) 
                values ('" . $feesObj->get_admitid() . "','" . $feesObj->get_courseid() . "','" . $feesObj->get_tfees() . "','" . $feesObj->get_pfees() . "','" . $feesObj->get_pendingfees() . "','" . $feesObj->get_feesplan() . "','" . $feesObj->get_pmode() . "','" . $feesObj->get_pdescription() . "','" . $feesObj->get_feereceipt() . "','" . ($feesObj->get_gstrate() ?? 0) . "','" . ($feesObj->get_gstamount() ?? 0) . "','" . ($feesObj->get_baseamount() ?? 0) . "')";
    } else {
      $sql = "insert into fees (`Admissionid`,`Courseid`,`TotalFees`, `PaidFees`,`PendingFees`, `Feesplan`,`DueDate`,`PaymentMode`,`PaymentDescription`,`feesreceipt`,`GstRate`,`GstAmount`,`BaseAmount`) 
                values ('" . $feesObj->get_admitid() . "','" . $feesObj->get_courseid() . "','" . $feesObj->get_tfees() . "','" . $feesObj->get_pfees() . "','" . $feesObj->get_pendingfees() . "','" . $feesObj->get_feesplan() . "','" . $feesObj->get_duedate() . "','" . $feesObj->get_pmode() . "','" . $feesObj->get_pdescription() . "','" . $feesObj->get_feereceipt() . "','" . ($feesObj->get_gstrate() ?? 0) . "','" . ($feesObj->get_gstamount() ?? 0) . "','" . ($feesObj->get_baseamount() ?? 0) . "')";
    }
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function searchadmission()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $searchadmission = "";
    if (isset($_POST['submit']))
      $searchadmission = $_POST["search"];
    echo $searchadmission;
    $sql = "Select A.id, A.Courseid,A.Phone,Name,A.cancellationReason,A.admissionstatus, CoursesOpted , TotalFees, 
         SUM(F.PaidFees) as PaidFees,
         COALESCE(SUM(F.GstAmount),0) as GstAmount
         from admissions as A 
         LEFT JOIN fees as F on A.id=F.Admissionid 
          GROUP BY Name,CoursesOpted,TotalFees,A.cancellationReason,A.admissionstatus";
    error_log($sql);
    $result = mysqli_query($db->getConnection(), $sql);
    $admissionlist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Fees();
        $view->set_admitid($row['id']);
        $view->set_name($row['Name']);
        $view->set_coursesopted($row['CoursesOpted']);
        $view->set_tfees($row['TotalFees']);
        $view->set_gstamount($row['GstAmount']);
        // Paid fees already include GST in Fee entries; keep as-is
        $view->set_pfees($row['PaidFees']);
        // Pending = (Base + GST) - Paid
        $view->set_pendingfees(($row['TotalFees'] + $row['GstAmount']) - $row["PaidFees"]);
        $view->set_CancellationReason($row['cancellationReason']);
        $view->set_Admissionstatus($row['admissionstatus']);
        array_push($admissionlist, $view);
      }
    } else {
      echo "0 results";
    }
    return $admissionlist;
  }

  public static function collectionoffees($viewObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();

    $sql = "SELECT A.id, 
         A.Courseid,
         A.Phone,
         A.Address,
         Name, 
         CoursesOpted, 
         TotalFees, DueDate,
         SUM(PaidFees) AS PaidFees 
         from admissions AS A 
         LEFT JOIN fees AS F ON A.id=F.Admissionid
         WHERE A.id=(" . $viewObj . ")
         GROUP BY Name,CoursesOpted,TotalFees";

    $view = new Fees();
    $result = mysqli_query($db->getConnection(), $sql);
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view->set_phone($row['Phone']);
        $view->setAddress($row['Address']);
        $view->set_tfees($row['TotalFees']);
        $view->set_admitid($row['id']);
        $view->set_courseid($row['Courseid']);
        $view->set_pfees($row['PaidFees']);
        $view->set_duedate($row['DueDate']);
        $view->set_name($row['Name']);
        $view->set_coursesopted($row['CoursesOpted']);
        $view->set_pendingfees($row['TotalFees'] - $row["PaidFees"]);
      }
    } else {
      $view = NULL;
    }
    return $view;
  }

  public static function viewfeesdetails($viewObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "select Modified_Date,Admissionid,PaidFees,PendingFees,PaymentMode,DueDate,feesreceipt,GstRate,GstAmount,TotalFees from fees where Admissionid=$viewObj";
    $result = mysqli_query($db->getConnection(), $sql);
    $feesdetails = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Fees();
        $view->set_modifieddate(date('d-m-y', strtotime($row["Modified_Date"])));
        $view->set_pfees($row['PaidFees']);
        $view->set_pendingfees($row['PendingFees']);
        $view->set_pmode($row['PaymentMode']);
        $view->set_admitid($row['Admissionid']);
        $view->set_duedate($row['DueDate']);
        $view->set_feereceipt($row['feesreceipt']);
        $view->set_gstrate($row['GstRate'] ?? 0);
        $view->set_gstamount($row['GstAmount'] ?? 0);
        $view->set_baseamount($row['TotalFees'] ?? 0);
        array_push($feesdetails, $view);
      }
    } else {
      echo "No entries ";
    }
    return $feesdetails;
  }

  public static function Transactiondetails($viewObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "select Modified_Date,Admissionid,PaidFees,PendingFees,PaymentMode,feesreceipt,DueDate,GstRate,GstAmount,TotalFees from fees where Admissionid=$viewObj";
    //error_log($sql);
    $result = mysqli_query($db->getConnection(), $sql);
    $feesdetails = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Fees();
        $view->set_modifieddate(date('d-m-y', strtotime($row["Modified_Date"])));
        $view->set_pfees($row['PaidFees']);
        $view->set_pendingfees($row['PendingFees']);
        $view->set_pmode($row['PaymentMode']);
        $view->set_admitid($row['Admissionid']);
        $view->set_duedate($row['DueDate']);
        $view->set_feereceipt($row['feesreceipt']);
        $view->set_gstrate($row['GstRate'] ?? 0);
        $view->set_gstamount($row['GstAmount'] ?? 0);
        $view->set_baseamount($row['TotalFees'] ?? 0);
        array_push($feesdetails, $view);

      }
      
      
    } else {
      echo "No entries ";
    }
    foreach($feesdetails as $fees){
     // error_log($fees->get_pfees());
    }
    header('Content-Type: application/json');
    echo json_encode($feesdetails);
  }
  public static function updateFileName($FeeObj)
    {
      $db = ConnectDb::getInstance();
      $connectionObj = $db->getConnection();
      $sql = "UPDATE fees SET ";
    
        $sql.="feesreceipt='".$FeeObj->get_feereceipt();
  
      //  $sql.= "', modifiedby='" . $purchaseObj->get_modifiedby() .
        "' WHERE Admissionid" . $FeeObj->get_admitid();
       // error_log($sql);
      if ($connectionObj->query($sql) === TRUE) {
      } else {
        echo "Error: " . $sql . "<br>" . $connectionObj->error;
      }
    }

}
