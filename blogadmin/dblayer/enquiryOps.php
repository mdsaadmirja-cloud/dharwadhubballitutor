<?php
// require "../Admin/session.php";
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']."/DB Operations/dbconnection.php";
require_once "../model/enquirymodel.php";
class DBenquery
{
    public static function getAllEnquery()
    {
        $db=ConnectDb::getInstance();
        $connectionObj=$db->getConnection();
        $sql = "SELECT * FROM enquiry" ;
        $result = mysqli_query($connectionObj, $sql);
        $enquirylist=[];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $enqueryModel=new enquiry();
                $enqueryModel->set_Id($row["id"]);
                $enqueryModel->set_name($row["Name"]);
                $enqueryModel->set_email($row["Email"]);
                $enqueryModel->set_phone($row["Phone"]);
                $enqueryModel->set_qualification($row["Qualification"]);
                array_push($enquirylist, $enqueryModel);
            }
        } else {
            echo "0 results";
        }
        return $enquirylist;
    }
     public static function getAllEnqueryBySection()
    {
        $db=ConnectDb::getInstance();
        $connectionObj=$db->getConnection();
        $sql = "SELECT * FROM enquiry" ;
        $result = mysqli_query($connectionObj, $sql);
        $enquirylist=[];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $enqueryModel=new enquiry();
                $enqueryModel->set_enqcreatedon(date('d-m-y', strtotime($row["Modified_Date"])));
                $enqueryModel->set_Id($row["id"]);
                $enqueryModel->set_name($row["Name"]);
                $enqueryModel->set_email($row["Email"]);
                $enqueryModel->set_phone($row["Phone"]);
                $enqueryModel->set_qualification($row["Qualification"]);
                $enqueryModel->set_enqueryFor($row["Trainings"]);
                array_push($enquirylist, $enqueryModel);
            }
        } else {
            echo "0 results";
        }
        return $enquirylist;
    }
    public static function insert($registrationObj)
    {
        $db=ConnectDb::getInstance();
        $connectionObj=$db->getConnection();
        $sql = "insert into enquiry (`Name`, 
        `Email`, 
        `Phone`, 
        `Trainings`,
        `Qualification`) 
         values ('".$registrationObj->get_name().
         "','".$registrationObj->get_email().
         "','".$registrationObj->get_phone().
         "','".$registrationObj->get_enqueryFor().
         "','".$registrationObj->get_qualification()."')";
                
        if ($connectionObj->query($sql) === true) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }


    public static function update($reg)
    {
        $db=ConnectDb::getInstance();
        $connectionObj=$db->getConnection();
         $sql = "update enquiry SET Name='".$reg->get_name()."',
          Email='".$reg->get_email()."',
          Phone='".$reg->get_phone()."',
           Trainings= '".$reg->get_trainings()."',
           Internship='".$reg->get_internship()."',
           Services='".$reg->get_services()."',
           Demo='".$reg->get_demo()."',
           Qualification='".$reg->get_qualification()."' 
           where id='".$reg->get_id()."' " ;
            
                if ($connectionObj->query($sql) === TRUE) {
        } else {
          echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
        
    }
}
