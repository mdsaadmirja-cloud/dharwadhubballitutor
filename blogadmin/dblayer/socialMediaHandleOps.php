<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/socialMediaHandleModel.php";

class DBsocialMediaHandle
{
    public static function insert($socialMediaHandle)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "INSERT INTO `socialmediahandle`(`name`,
     `handle`,
     `icon`,  
     `createdBy`, 
     `modifiedBy`
     ) VALUES ('" .
            $socialMediaHandle->getName() .
            "','" . $socialMediaHandle->getHandle() .
            "','" . $socialMediaHandle->getIcon() .
            "','" . $socialMediaHandle->getCreatedBy() .
            "','" . $socialMediaHandle->getCreatedBy() .
            "')";
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
    public static function update($socialMediaHandle)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "UPDATE `socialmediahandle` SET `name`='" . $socialMediaHandle->getName() .
            "' , `handle`='" . $socialMediaHandle->getHandle() .
            "' , `modifiedBy`='" . $socialMediaHandle->getModifiedBy() .
            "' WHERE Id=" . $socialMediaHandle->getId();
        if ($connectionObj->query($sql) === true) {
        }
    }
    public static function read()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM socialmediahandle";
        $result = $connectionObj->query($sql);
        $socialMediaHandleList = [];
        if (mysqli_num_rows($result)>0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $socialMedia = new socialMediaHandle();
                $socialMedia->setId($row["Id"]);
                $socialMedia->setName($row["name"]);
                $socialMedia->setHandle($row["handle"]);
                $socialMedia->setIcon($row["icon"]);
                $socialMedia->setCreatedBy($row["createdBy"]);
                array_push($socialMediaHandleList, $socialMedia);
            }
        }
        return $socialMediaHandleList;
    }
    public static function delete($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "DELETE FROM socialmediahandle WHERE Id=" . $Id;
        if ($connectionObj->query($sql) === true) {
        }
    }
}
