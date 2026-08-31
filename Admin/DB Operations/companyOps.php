<?php
require_once "../../DB Operations/dbconnection.php";
require_once "../model/companyModel.php";
class DBCompany
{
  public static function insert($company)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into company (`Date`,`Name`,`Email`,`Phone`,`Location`,`Approachedby`,`Companyname`) 
      values (
      '" . $company->getDate() . "',
      '" . $company->getName() . "',
      '" . $company->getEmail() . "',
      '" . $company->getPhone() . "',
      '" . $company->getLocation() . "',
      '" . $company->getApproachedby() . "',
      '" . $company->getCompanyname() .
      "')";
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function getcompanydetails()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = 'SELECT * FROM company';
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $companylist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $company = new Company();
        $company->setId($row['id']);
        $company->setDate($row['Date']);
        $company->setName($row['Name']);
        $company->setEmail($row['Email']);
        $company->setPhone($row['Phone']);
        $company->setLocation($row['Location']);
        $company->setApproachedby($row['Approachedby']);
        $company->setCompanyname($row['Companyname']);

        array_push($companylist, $company);
      }
    } else {
      echo "";
    }
    return $companylist;
  }

  public static function update($company)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE company set Date ='" . $company->getDate() . "', 
    Name='" . $company->getName() . "', 
    Email='" . $company->getEmail() . "',
    Phone='" . $company->getPhone() . "',
    Location='" . $company->getLocation() . "',
    Approachedby='" . $company->getApproachedby() . "',
    Companyname='" . $company->getCompanyname() ."'";
     $sql .="WHERE id=" . $company->getId();    
        error_log($sql);

    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function delete($company)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "DELETE FROM company WHERE id='" . $company . "'";
    error_log($sql);
    if ($connectionObj->query($sql) === true) {
    }
  }
}
