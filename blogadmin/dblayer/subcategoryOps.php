<?php
require_once $_SERVER['DOCUMENT_ROOT'].$configs['app_info']['appName']."/DB Operations/dbconnection.php";
require_once $_SERVER['DOCUMENT_ROOT'] .$configs['app_info']['appName'] ."/blogadmin/model/subcategorymodel.php";
class DBsubcategory
{
  public static function insert($subcatObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into subcategory (`subCategoryName`,
    `subCategoryDescription`,
     `subCategoryCreatedBy`,
     `subCategoryModifiedBy`) 
                values ('"  . $subcatObj->getSubCategoryName() .
      "','" . $subcatObj->getSubCategoryDescription() .
      "','" . $subcatObj->getSubCategoryCreatedBy() .
      "','" . $subcatObj->getSubCategoryModifiedBy() . "')";

    if ($connectionObj->query($sql) === true) {
      $lastInsertedId =  $connectionObj->insert_id;
      $count = count($subcatObj->getMappedCategories());
      $mapped = $subcatObj->getMappedCategories();
      for ($i = 0; $i < $count; $i++) {
        $sql = "INSERT INTO `catsubcatmapping`(`sucatId`, `catId`) VALUES (" .
          $lastInsertedId .
          "," . $mapped[$i] . ")";
        if ($connectionObj->query($sql) === true) {
        }
      }
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }
  public static function getMappedSubCategories($postId)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT
    sub.subCategoryId,
    sub.subCategoryName,
    CASE WHEN TEMP.postId IS NULL THEN
    FALSE
    ELSE
    TRUE
    END AS postId
    FROM subcategory AS sub
    LEFT JOIN (SELECT p.postId AS postId,
         M.subCatId AS subCatId
     FROM
         post AS p
     LEFT JOIN postsubcatmapping AS M
     ON
         p.postId = M.postId
     WHERE
         p.postId = " . $postId . "
 ) AS TEMP
 ON
     sub.subCategoryId = TEMP.subCatId";
     error_log($sql);
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $subCategorylist = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $subCategory = new Subcategory();
        $subCategory->setSubCategoryName($row['subCategoryName']);
        $subCategory->setSubCategoryId($row['subCategoryId']);
        $subCategory->setMappedPost($row["postId"]);
        array_push($subCategorylist, $subCategory);
      }
    } else {
      // echo "0 results";
    }
    header('Content-Type: application/json');
    echo json_encode($subCategorylist);
  }
  public static function getAllSubCategory()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM subcategory";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $subCategorylist = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $subCategory = new Subcategory();
        $subCategory->setSubCategoryName($row['subCategoryName']);
        $subCategory->setSubCategoryId($row['subCategoryId']);
        $subCategory->setSubCategoryDescription($row["subCategoryDescription"]);
        $subCategory->setSubCategoryCreatedBy($row["subCategoryCreatedBy"]);
        $subCategory->setSubCategoryModifiedBy($row["subCategoryModifiedBy"]);
        array_push($subCategorylist, $subCategory);
      }
    } else {
      // echo "0 results";
    }

    return $subCategorylist;
  }

  public static function update($subcatObj)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE subcategory SET subCategoryName='" . $subcatObj->getSubCategoryName() .
      "', subCategoryDescription='" . $subcatObj->getSubCategoryDescription() .
      "', subCategoryModifiedBy='" . $subcatObj->getSubCategoryModifiedBy() .
      "' WHERE subCategoryId=" . $subcatObj->getSubCategoryId();
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
      $sql = "DELETE FROM `catsubcatmapping` WHERE sucatId=" . $subcatObj->getSubCategoryId();
      error_log($sql);
      if ($connectionObj->query($sql) === TRUE) {
        if (is_array($subcatObj->getMappedCategories())) {
          $count = count($subcatObj->getMappedCategories());
          $mapped = $subcatObj->getMappedCategories();
          for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO `catsubcatmapping`(`sucatId`, `catId`) VALUES (" .
              $subcatObj->getSubCategoryId() .
              "," . $mapped[$i] . ")";
            error_log($sql);
            if ($connectionObj->query($sql) === true) {
            }
          }
        } else {
          $sql = "INSERT INTO `catsubcatmapping`(`sucatId`, `catId`) VALUES (" .
            $subcatObj->getSubCategoryId() .
            "," . $subcatObj->getMappedCategories() . ")";
          error_log($sql);
          if ($connectionObj->query($sql) === true) {
          }
        }
      }
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function selectsubcategory()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $result = mysqli_query($connectionObj, 'SELECT subCategoryId,
    subCategoryName FROM subcategory');
    $itemsubcatlist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $view = new Subcategory();
        $view->setSubCategoryId($row['subCategoryId']);
        $view->setSubCategoryName($row['subCategoryName']);
        array_push($itemsubcatlist, $view);
      }
    } else {
      echo "0 results";
    }
    header('Content-Type: application/json');
    echo json_encode($itemsubcatlist);
  }

  public static function delete($subcatObj)
  {
    try {
      $db = ConnectDb::getInstance();
      $connectionObj = $db->getConnection();
      $sql = "DELETE FROM catsubcatmapping WHERE sucatId='" . $subcatObj . "'";
      if ($connectionObj->query($sql) === TRUE) {
        $sql = "DELETE from subcategory where subCategoryId='" . $subcatObj . "'";
        if ($connectionObj->query($sql) === TRUE) {
          $sql = "DELETE from postsubcatmapping where subCatId='" . $subcatObj . "'";
          if ($connectionObj->query($sql) === TRUE) {
          } else {
            error_log("Error: " . $sql . "<br>" . $connectionObj->error);
          }
        } else {
          error_log("Error: " . $sql . "<br>" . $connectionObj->error);
        }
      } else {
        error_log("Error: " . $sql . "<br>" . $connectionObj->error);
      }
    } catch (Exception $e) {
    }
  }
}
