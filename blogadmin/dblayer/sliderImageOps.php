<?php
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']."/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']."/blogadmin/model/sliderImageModel.php";
class DBsliderImageFile{
    public static function insert($sliderImageFile){

        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "INSERT INTO sliderimages (
        `image`, 
        `imageCaption`,
        `alternatetext`, 
        `modifiedBY`, 
        `createdBy`) 
                values ('" . $sliderImageFile->getImage() .
            "','" . $sliderImageFile->getImageFileCaption() .
            "','" . $sliderImageFile->getImageAlternateText() .
            "','" . $sliderImageFile->getCreatedby() .
            "','" . $sliderImageFile->getModifiedby() .
            "')";
            error_log($sql);
        if ($connectionObj->query($sql) === true) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }

    public static function delete($designFileId){
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql="DELETE FROM sliderimages WHERE imageId=".$designFileId;
        if ($connectionObj->query($sql) === true) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }

    public static function readAll(){
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql="SELECT * FROM sliderimages";
        $result = $connectionObj->query($sql);
        $count = mysqli_num_rows($result);
        $designFileList = [];
        if ($count > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $designFile = new sliderImage();
                $designFile->setImageFileId($row['imageId']);
                $designFile->setImage($row["image"]);
                $designFile->setImageFileCaption($row["imageCaption"]);
                $designFile->setImageFileCaption($row["alternatetext"]);
                $designFile->setCreatedby($row["createdBy"]);
                $designFile->setModifiedby($row["modifiedBY"]);
                array_push($designFileList, $designFile);
            }
        } else {
            // echo "0 results";
        }
        return $designFileList;
    }
}
