<?php

require "../DB Operations/dbconnection.php";
    class DBadmission
    {
      public static function insert($admissionObj)
      {
        $db=ConnectDb::getInstance();
        $connectionObj=$db->getConnection();
         $sql = "insert into admissions (`Name`, `Phone`, `Email`, `DateofBirth`,`Qualification`,`Guardians_Name`,`Guardians_Phone`,`CoursesOpted`,`Address`,`AdhaarNo`,`AdhaarFile`,`PhotoFile`) 
                values ('".$admissionObj->get_name()."','".$admissionObj->get_phone()."','".$admissionObj->get_email()."','".$admissionObj->get_dateofbirth()."', '".$admissionObj->get_qualification()."','".$admissionObj->get_guardiansname()."','".$admissionObj->get_guardiansphone()."','".$admissionObj->get_coursesopted()."','".$admissionObj->get_address()."','".$admissionObj->get_adhaarno()."','".$admissionObj->get_adhaarfile()."','".$admissionObj->get_photofile()."')";
                
                if ($connectionObj->query($sql) === TRUE) {
        } else {
          echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }
  }
?>