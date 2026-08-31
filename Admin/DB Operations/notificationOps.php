<?php
// require "../Admin/session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../../Admin/model/notificationModel.php";

class DBnotification
{
  public static function insert($notification)
  {

    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into notification (`Message`,`Status`,`Category`)
    values ('" . $notification->getMessage() .
      "','" . $notification->getStatus() .
      "','" . $notification->getCategory() .

      "')";
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function getAllnotifications()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = 'SELECT * FROM notification ORDER BY Id DESC';
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $notificationlist = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $notification = new Notification();
        $notification->setId($row['Id']);
        $notification->setMessage($row['Message']);
        $notification->setStatus($row['Status']);
        $notification->setCategory($row['Category']);
        array_push($notificationlist, $notification);
      }
    } else {
      echo "";
    }
    return $notificationlist;
  }

  public static function getnotifications()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM notification";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $notification = new Notification();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $notification->setId($row["Id"]);
      }
    } else {
      echo "";
    }
    return $notification;
  }


  public static function update($notification)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE notification SET Message='" . $notification->getMessage() .
      "', Status='" . $notification->getStatus() .
      "', Category='" . $notification->getCategory() .
      "' WHERE Id=" . $notification->getId();
    echo $sql;
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function delete($notification)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "DELETE FROM notification WHERE Id='" . $notification . "'";
    error_log($sql);
    if ($connectionObj->query($sql) === true) {
    }
  }
}
