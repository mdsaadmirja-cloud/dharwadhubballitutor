<?php
// require "../Admin/session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../../Admin/model/jobModel.php";

class DBJob
{
    public static function insert($job)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "insert into jobdetails (`jobid`,`Name`,`Description`, `JobLocation`,`RemoteWork`) 
    values ( '" . $job->getJobid() . "',
     '" . $job->getName() . "',
     '" . $job->getDescription() . "',
     '" . $job->getJobLocation() . "',
     '". $job->getRemoteWork(). "'
     )";
        error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        }
    }

    public static function getjobById($Id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM jobdetails WHERE jobid =$Id";
        error_log($sql);
        $result = $connectionObj->query($sql);
        $jobList = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job = new Job();
                $job->setId($row['id']);
                $job->setJobid($row['jobid']);
                $job->setName($row['Name']);
                $job->setDescription($row['Description']);
                $job->setJobLocation($row['JobLocation']);
                $job->setRemoteWork($row['RemoteWork']);
                array_push($jobList, $job);
            }
        }
        return $jobList;
    }


    public static function update($job)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "update jobdetails SET Name='" . $job->getName() . "',
        Description='" . $job->getDescription() . "',  
        JobLocation='" .$job->getJoblocation() . "',
        RemoteWork='" .$job->getRemoteWork(). "'           
          where id='" . $job->getId() . "' ";
        error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }
    public static function delete($job)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "DELETE FROM jobdetails WHERE id='" . $job . "'";
        error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
}
