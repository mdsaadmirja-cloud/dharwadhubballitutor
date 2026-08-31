<?php
// require "../Admin/session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../../Admin/model/modalCoursemodel.php";

class DBCourseModal
{
    public static function insert($coursemodal)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "insert into coursemodal (`coursemodalId`,`Name`,`description`) 
    values ('" . $coursemodal->getcoursemodalId() . "', '" . $coursemodal->getName() . "','" . $coursemodal->getDescription() . "')";
        error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        }
    }

    public static function getcourseModalById($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM coursemodal WHERE coursemodalId =$Id";
        error_log($sql);
        $result = $connectionObj->query($sql);
        $CourseModalList = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $coursemodal = new CourseModal();
                //$coursemodal->setid($row['id']);
                $coursemodal->setcoursemodalId($row['coursemodalId']);
                $coursemodal->setName($row['Name']);
                $coursemodal->setDescription($row['Description']);
                array_push($CourseModalList, $coursemodal);
            }
        }
        return $CourseModalList;
    }

    public static function getcourseModalByCName($Cname)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT C.id as CourseId,
        C.CName as CourseName,
        CM.coursemodalid as CourseModalId,
        CM.Name as Name
        from courses as C
        Join coursemodal CM on CM.coursemodalid=C.id
        where C.CName='".$Cname."'";
        error_log($sql);
        $result = $connectionObj->query($sql);
        $CourseModalList = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $coursemodal = new CourseModal();
                $coursemodal->setName($row['Name']);
                array_push($CourseModalList, $coursemodal);
            }
        }
        return $CourseModalList;
    }

    public static function update($coursemodal)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "update coursemodal SET Name='" . $coursemodal->getName() . "',
        Description='" . $coursemodal->getDescription() . "'            
          where id='" . $coursemodal->getid() . "' ";
        error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }
    
    public static function delete($coursemodal)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "DELETE FROM coursemodal WHERE id='" . $coursemodal . "'";
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
}
