<?php
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']."/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']."/blogadmin/model/businessModel.php";
class DBbusiness
{
  public static function insert($businessObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into businessdetails (`businessName`,
    `businessAddress`,
    `businessContact`,
    `businessTagLine`,
    `businessEmail`,
    `businessGSTIN`,
    `logoImage`,
    `aboutBusiness`) 
                values ('". $businessObj->getBusinessName() .
      "','" . $businessObj->getBusinessAddress() .
      "','" . $businessObj->getBusinessContact() .
      "','" . $businessObj->getBusinessTag() .
      "','" . $businessObj->getBusinessEmail() . 
      "','" . $businessObj->getBusinessGSTIN() .
      "','" . $businessObj->getBusinessLogoImage().
      "','" . $businessObj->getBusinessAboutBusiness().
      "')";
    if ($connectionObj->query($sql) === TRUE) {
      
    }
  }
  public static function update($businessObj){
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql ="UPDATE businessdetails set `businessName`='".$businessObj->getBusinessName().
    "', `businessAddress`='".$businessObj->getBusinessAddress().
    "', `businessContact`='".$businessObj->getBusinessContact().
    "', `businessTagLine`='".$businessObj->getBusinessTag().
    "', `businessEmail`='".$businessObj->getBusinessEmail().
    "', `businessGSTIN`='".$businessObj->getBusinessGSTIN()."'";

    if(!empty($businessObj->getBusinessLogoImage())){
      $sql .=", `logoImage`='".$businessObj->getBusinessLogoImage()."'";
    }
    $sql .=", `aboutBusiness`='".$businessObj->getBusinessAboutBusiness()."'";
    
    $sql.="WHERE 	businessId=".$businessObj->getBusinessId();
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
      
    }
  }
  public static function getBusinessDetails(){
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql ="SELECT * FROM businessdetails";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $business= new Business();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $business->setBusinessId($row["businessId"]);
        $business->setBusinessName($row["businessName"]);
        $business->setBusinessLogoImage($row["logoImage"]);
        $business->setBusinessTag($row["businessTagLine"]);
        $business->setBusinessAddress($row["businessAddress"]);
        $business->setBusinessContact($row["businessContact"]);
        $business->setBusinessEmail($row["businessEmail"]);
        $business->setBusinessAboutBusiness($row["aboutBusiness"]);
        $business->setBusinessGSTIN($row["businessGSTIN"]);
      }
    }
    return $business;
  }
 
}
