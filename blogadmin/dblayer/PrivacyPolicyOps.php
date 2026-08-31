<?php
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']. "/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']. "/blogadmin/model/PrivacyPolicyModel.php";
class DBPrivacy
{
    public static function insert($PrivacyPolicyObj)
    {
      $db = ConnectDb::getInstance();
      $connectionObj = $db->getConnection();
      $sql = "insert into privacypolicy (`description` ) 
      values ('" . $PrivacyPolicyObj->getdescription() .
        "')";
      error_log($sql);
      if ($connectionObj->query($sql) === TRUE) {
      }
    }
    public static function update($PrivacyPolicyObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE privacypolicy set `description`='" . $PrivacyPolicyObj ->getdescription() . "'";

    $sql .= "WHERE 	id=" . $PrivacyPolicyObj->getid();
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    }
  }
  public static function getPrivacyPolicy()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM privacypolicy";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $Privacy = new Privacy();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $Privacy->setid($row["id"]);
        $Privacy->setdescription($row["description"]);
      }
    } else {
      echo "";
    }
    return $Privacy;
  }
  public static function getPrivacydetails()
  {
    $db = connectDb::getInstance();
    $connectionObj = $db->getconnection();
    $sql = "SELECT * FROM privacypolicy";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $Privacy = new Privacy();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Privacy->setid($row["id"]);
        $Privacy->setdescription($row["description"]);
      }
    }
    return $Privacy;
  }
  
}