<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/facebookchatModel.php";
class DBfacebook
{
  public static function insert($facebook)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into facebookchatplugin (`PluginCode`)
    values ('" . $facebook->getPluginCode() .
      "')";
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    }
  }
  public static function update($facebook)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE facebookchatplugin SET PluginCode='" . $facebook->getPluginCode() .
      "' WHERE Id=" . $facebook->getId();
    echo $sql;
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }
  public static function delete($facebook)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "DELETE FROM facebookchatplugin WHERE Id='" . $facebook . "'";
    error_log($sql);
    if ($connectionObj->query($sql) === true) {
    }
  }
  
  public static function getPlugin(){
      $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM facebookchatplugin ";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
        if($count>0){
          while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $plugin=new facebook();
            $plugin->setId($row["Id"]);
            $plugin->setPluginCode($row["PluginCode"]);
            
          }
        }
        return $plugin;
  }
  
}
