<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/testimonialsModel.php";

class DBtestimonials
{
    public static function insert($testimonials)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "INSERT INTO `testimonials` (`Name`,
        `Description`,
        `RateNow`, 
        `Image`, 
        `ImageAlternateText`,         
        `Modifiedby`,
        `createdby`)
        values ('" . $testimonials->getName() .
            "','" . $testimonials->getDescription() .
            "','" . $testimonials->getRateNow() .
            "','" . $testimonials->getImage() .
            "','" . $testimonials->getImageAlternateText() .
            "','" . $testimonials->getModifiedby() .
            "','" . $testimonials->getcreatedby() . "')";
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }
    public static function getTestimonialsList()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT *  FROM testimonials ";
        $result = $connectionObj->query($sql);
        $count = mysqli_num_rows($result);
        $testimonialsList = [];
        if ($count > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $testimonials = new Testimonials();
                $testimonials->setId($row["Id"]);
                $testimonials->setName($row["Name"]);
                $testimonials->setDescription($row["Description"]);
                $testimonials->setRateNow($row["RateNow"]);
                $testimonials->setImage($row["Image"]);
                $testimonials->setImageAlternateText($row["ImageAlternateText"]);
                $testimonials->setModifiedby($row["Modifiedby"]);
                $testimonials->setcreatedby($row["createdby"]);
                $testimonials->setModifiedDate($row["ModifiedDate"]);
                $testimonials->setcreatedDate($row["createdDate"]);

                array_push($testimonialsList, $testimonials);
            }
        }
        return $testimonialsList;
    }
    public static function getId($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT *  FROM testimonials WHERE Id=" . $Id;
        $result = $connectionObj->query($sql);
        $count = mysqli_num_rows($result);
        $testimonials = new Testimonials();
        if ($count > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $testimonials->setId($row["Id"]);
                $testimonials->setName($row["Name"]);
                $testimonials->setDescription($row["Description"]);
                $testimonials->setRateNow($row["RateNow"]);
                $testimonials->setImage($row["Image"]);
                $testimonials->setImageAlternateText($row["ImageAlternateText"]);
                $testimonials->setModifiedby($row["Modifiedby"]);
                $testimonials->setcreatedby($row["createdby"]);
                $testimonials->setModifiedDate($row["ModifiedDate"]);
                $testimonials->setcreatedDate($row["createdDate"]);
            }
        }
        return $testimonials;
    }
    public static function getTestimonialsOnHome()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM   testimonials";            
        $result = $connectionObj->query($sql);
        $count = mysqli_num_rows($result);
        $testimonialsList = [];       
        if ($count > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $testimonials = new Testimonials();
                $testimonials->setId($row["Id"]);
                $testimonials->setName($row["Name"]);
                $testimonials->setDescription($row["Description"]);
                $testimonials->setRateNow($row["RateNow"]);
                $testimonials->setImage($row["Image"]);
                $testimonials->setImageAlternateText($row["ImageAlternateText"]);
                $testimonials->setModifiedby($row["Modifiedby"]);
                $testimonials->setcreatedby($row["createdby"]);
                $testimonials->setModifiedDate($row["ModifiedDate"]);
                $testimonials->setcreatedDate($row["createdDate"]);
                array_push($testimonialsList, $testimonials);
            }
        }
        return $testimonialsList;
    }

    public static function update($testimonials)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "UPDATE `testimonials` SET 
            `Name`='" . $testimonials->getName() .
            "' , `Description`='" . $testimonials->getDescription() .
            "' , `RateNow`='" . $testimonials->getRateNow() .
            "' , `Image`='" . $testimonials->getImage() .
            "' , `ImageAlternateText`='" . $testimonials->getImageAlternateText() .
            "' , `Modifiedby`='" . $testimonials->getModifiedby() .
            "' , `createdby`='" . $testimonials->getcreatedby() .
            "'  WHERE `Id`=" . $testimonials->getId();
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
    public static function delete($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "DELETE FROM testimonials WHERE Id=" . $Id;
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
}
