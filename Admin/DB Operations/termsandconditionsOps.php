<?php
require_once "../../DB Operations/dbconnection.php";
require_once "../model/termsandconditionsModel.php";
class DBterms
{
  public static function insert($termsandconditionsObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into termsandconditions (`description` ) 
    values ('" . $termsandconditionsObj->getdescription() .
      "')";
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    }
  }
  public static function update($termsandconditionsObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE termsandconditions set `description`='" . $termsandconditionsObj->getdescription() . "'";

    $sql .= "WHERE 	id=" . $termsandconditionsObj->getid();
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    }
  }
  public static function gettermsandconditions()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM termsandconditions";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $terms = new Terms();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      
        $terms->setid($row["id"]);
        $terms->setdescription($row["description"]);
      }
    } else {
      echo "";
    }
    return $terms;
  }

  public static function gettermsdetails()
  {
    $db = connectDb::getInstance();
    $connectionObj = $db->getconnection();
    $sql = "SELECT * FROM termsandconditions";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $terms = new terms();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $terms->setid($row["id"]);
        $terms->setdescription($row["description"]);
      }
    }
    return $terms;
  }
  
}
