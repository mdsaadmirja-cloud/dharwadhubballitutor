<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/templateModel.php";
class DBtemplate
{
  public static function insert($template)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into template (`sender`,
    `message`)
    values ('" . $template->getsender() .
      "','" . $template->getmessage() .
      "')";
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    }
  }

  public static function getAlltemplate()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM template";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $templatelist = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $template = new template();
        $template->setmessageId($row['messageId']);
        $template->setsender($row['sender']);
        $template->setmessage($row['message']);
        array_push($templatelist, $template);
      }
    } else {
      echo "";
    }
    return $templatelist;
  }


  public static function getSenderandMessage()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM template";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $template = new template();
        $template->setmessageId($row['messageId']);
        $template->setsender($row['sender']);
        $template->setmessage($row['message']);
      }
    } else {
      echo "";
    }
    return $template;
  }

  public static function update($templateobj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE template SET sender='" . $templateobj->getsender() .
      "', message='" . $templateobj->getmessage() .
      "' WHERE messageId=" . $templateobj->getmessageId();
    error_log($sql);
    echo $sql;
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }
  public static function delete($template)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "DELETE FROM template WHERE messageId='" . $template . "'";
    error_log($sql);
    if ($connectionObj->query($sql) === true) {
    }
  }
}
