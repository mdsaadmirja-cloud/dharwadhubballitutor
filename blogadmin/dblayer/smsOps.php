<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/smsModel.php";
class DBsms
{
    public static function insert($sms)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "TRUNCATE TABLE smsdetails";
        if ($connectionObj->query($sql) === TRUE) {

            $sql = "insert into smsdetails (`username`,
    `APIkey`,
    `key`,
    `test`,
    `ModifiedOn`,
    `CreatedOn`,
    `ModifiedBy`,
    `CreatedBy` )
                values ('" . $sms->getusername() .
                "','" . $sms->getAPIkey() .
                "','" . $sms->getkey() .
                "','" . $sms->gettest() .
                "','" . $sms->getModifiedOn() .
                "','" . $sms->getCreatedOn() .
                "','" . $sms->getModifiedBy() .
                "','" . $sms->getCreatedBy() .
                "')";
                error_log($sql);
            if ($connectionObj->query($sql) === TRUE) {
            }
            error_log($sql);
        }
    }
    public static function getsmsDetails()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM smsdetails";
        $result = $connectionObj->query($sql);
        $count = mysqli_num_rows($result);
        $sms = new Sms();
        if ($count > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                $sms->settest($row["test"]);
                $sms->setModifiedOn($row["ModifiedOn"]);
                $sms->setCreatedOn($row["CreatedOn"]);
                $sms->setModifiedBy($row["ModifiedBy"]);
                $sms->setCreatedBy($row["CreatedBy"]);
            }
        }
        return $sms;
    }

    public static function getAllsmsDetails()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM smsdetails";
        $result = $connectionObj->query($sql);
        $count = mysqli_num_rows($result);
       
        if ($count > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $sms = new Sms();
                $sms->settest($row["test"]);
                $sms->setusername($row["username"]);
                $sms->setAPIkey($row["APIkey"]);
                $sms->setKey($row["key"]);
                
            }
        }
        return $sms;
    }

    public static function update($sms)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "UPDATE smsdetails set `username`='" . $sms->getusername() .
            "', `APIkey`='" . $sms->getAPIkey() .
            "', `key`='" . $sms->getkey() .
            "', `test`='" . $sms->gettest() .
            "', `ModifiedOn`='" . $sms->getModifiedOn() .
            "', `CreatedOn`='" . $sms->getCreatedOn() .
            "', `ModifiedBy`='" . $sms->getModifiedBy() .
            "', `CreatedBy`='" . $sms->getCreatedBy() .
            "'  WHERE `Id`=" . $sms->getId();
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
    public static function delete($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "DELETE FROM smsdetails WHERE Id=" . $Id;
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
}
