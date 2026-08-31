<?php
require_once "../../DB Operations/dbconnection.php";
require_once "../model/placementmodel.php";
class DBPlacement
{
    public static function insert($placement)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "insert into placement (`Admissionid`,`Assistedon`,`Companyname`,`jobpost`,`Result`,`Reason`,`Rejectedby`,`offerletter`,`Createdby`,`Modifiedby`) 
      values ('" . $placement->getAdmissionid() . "',
      '" . $placement->getAssistedon() . "',
      '" . $placement->getCompanyname() . "',
      '" . $placement->getJobpost() . "',
      '" . $placement->getResult() . "',
      '" . $placement->getReason() . "',
      '" . $placement->getRejectedby() . "',
      '" . $placement->getofferletter() . "',
      '" . $placement->getCreatedby() . "',
      '" . $placement->getModifiedby() . "'     
      )";
        error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        }
    }

    public static function getplacementbyId($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM placement WHERE Admissionid =$Id";
        error_log($sql);
        $result = $connectionObj->query($sql);
        $placementList = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $placement = new Placement();
                $placement->setId($row['Id']);
                $placement->setAdmissionid($row['Admissionid']);
                $placement->setAssistedon(date('d-m-y', strtotime($row["Assistedon"])));
                $placement->setCompanyname($row['Companyname']);
                $placement->setJobpost($row['jobpost']);
                $placement->setResult($row['Result']);
                $placement->setReason($row['Reason']);
                $placement->setRejectedby($row['Rejectedby']);
                $placement->setofferletter($row['offerletter']);

                array_push($placementList, $placement);
            }
        }
        return $placementList;
    }

    public static function update($placement)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "UPDATE placement set Assistedon='" . $placement->getAssistedon() . "',
      Companyname='" . $placement->getCompanyname() . "',
      jobpost='" . $placement->getJobpost() . "',
      Result='" . $placement->getResult() . "',
      Reason='" . $placement->getReason() . "',
      Rejectedby='" . $placement->getRejectedby() . "',
      Createdby='" . $placement->getCreatedby() . "',     
      Modifiedby='" . $placement->getModifiedby();

        if (!empty($placement->getofferletter())) {
            $sql .= "',offerletter='" . $placement->getofferletter();
        }
        $sql .= "'WHERE Id='" . $placement->getId() . "' ";
        error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }


    public static function delete($placement)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "DELETE FROM placement WHERE Id='" . $placement . "'";
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
}
