<?php
require_once($_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/postModel.php");
require_once($_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/blogadmin/model/subcategorymodel.php");
require_once $_SERVER['DOCUMENT_ROOT'] . $configs['app_info']['appName'] . "/DB Operations/dbconnection.php";
class DBpost
{
  public static function insert($post)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "INSERT INTO `post`(
         `postTitle`,
         `postUrl`,
         `appearOnHome`, 
         `postDescription`,
         `LinkUnder`, 
         `postCreatedBy`, 
         `titleTag`, 
         `keywords`,
         `modifiedBy`) 
         values ('" . $post->getPostTitle() .
      "','" . $post->getPostUrl() .
      "','" . $post->getOnHome() .
      "','" . $post->getPostDescription() .
      "','" . $post->getLinkUnder ().
      "','" . $post->getPostCreatedBy() .
      "','" . $post->getTitleTag() .
      "','" . $post->getKeywords() .
      "','" . $post->getModifiedBy() . "')";
    if ($connectionObj->query($sql) === true) {
      $lastInsertedId =  $connectionObj->insert_id;
      $sql = "INSERT INTO `postimages`( `postImage`,
        `createdBy`,
        `modifiedBy`,
        `imageAlternateText`,
        `postId`)
        VALUES('" . $post->getImage() .
        "','" . $post->getPostCreatedBy() .
        "','" . $post->getModifiedBy() .
        "','" . $post->getAltTextImage() .
        "'," . $lastInsertedId .
        ")";
      if ($connectionObj->query($sql) === true) {
      } else {
        echo "Error: " . $sql . "<br>" . $connectionObj->error;
      }
      if ($post->getLinkUnder() == "1") {
        if (is_array($post->getMappedSubCategory())) {
          $count = count($post->getMappedSubCategory());
          $mapped = $post->getMappedSubCategory();
          for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO `postcatmapping`(`postId`, `CatId`) VALUES (" .
              $lastInsertedId .
              "," . $mapped[$i] . ")";
            if ($connectionObj->query($sql) === true) {
            }
          }
        }
      } else {
        if (is_array($post->getMappedSubCategory())) {
          $count = count($post->getMappedSubCategory());
          $mapped = $post->getMappedSubCategory();
          for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO `postsubcatmapping`(`postId`, `subCatId`) VALUES (" .
              $lastInsertedId .
              "," . $mapped[$i] . ")";
            if ($connectionObj->query($sql) === true) {
            }
          }
        } else {
          $sql = "INSERT INTO `postsubcatmapping`(`postId`, `subCatId`) VALUES (" .
            $lastInsertedId .
            "," . $post->getMappedSubCategory() . ")";
          if ($connectionObj->query($sql) === true) {
          }
        }
      }
    }
  }

  public static function getPostByCategoryFornt($CatId)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM postcatmapping
    JOIN category ON 
    postcatmapping.CatId=category.CategoryId
    JOIN  post ON postcatmapping.postId=post.postId
    JOIN postimages AS pI ON post.postId=pI.postId
    WHERE category.CategoryId=" . $CatId;
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $postList = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setImage($row["postImage"]);
        $post->setPostUrl($row["postUrl"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setPostCreatedBy(date_format(date_create($row["postCreatedOn"]), "d-m-Y"));
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
        array_push($postList, $post);
      }
    }
    return $postList;
  }
  public static function getPostBySubCategoryFornt($subId)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM postsubcatmapping
    JOIN subcategory ON 
    postsubcatmapping.subCatId=subcategory.subCategoryId
    JOIN  post ON postsubcatmapping.postId=post.postId
    JOIN postimages AS pI ON post.postId=pI.postId
    WHERE subcategory.subCategoryId=" . $subId;
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $postList = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setImage($row["postImage"]);
        $post->setPostUrl($row["postUrl"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setPostCreatedBy(date_format(date_create($row["postCreatedOn"]), "d-m-Y"));
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
        array_push($postList, $post);
      }
    }
    return $postList;
  }
  public static function getPostBySubCategoryId($postId)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM postsubcatmapping
    JOIN subcategory ON 
    postsubcatmapping.subCatId=subcategory.subCategoryId
    JOIN  post ON postsubcatmapping.postId=post.postId
    JOIN postimages AS pI ON post.postId=pI.postId
    WHERE post.postId <>" . $postId;
    error_log($sql);
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $postList = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setImage($row["postImage"]);
        $post->setPostUrl($row["postUrl"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setPostCreatedBy(date_format(date_create($row["postCreatedOn"]), "d-m-Y"));
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
        array_push($postList, $post);
      }
    }
    return $postList;
  }
  public static function getPostByUrl($postUrl)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT *  FROM post AS p 
    JOIN postimages AS pI ON p.postId= pI.postId 
    WHERE p.postUrl='" . $postUrl . "'";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
 
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
             $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setImage($row["postImage"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
      }
      return $post;
    }else{
        return 0;
    }
    
  }
  public static function getPostById($Id)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT *  FROM post AS p JOIN postimages AS pI ON p.postId=pI.postId WHERE p.postId=" . $Id;
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $post = new Post();
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setPostUrl($row["postUrl"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setImage($row["postImage"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
      }
    }
    return $post;
  }
  public static function getpopularPost()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM post AS p JOIN postimages AS pI ON p.postId=pI.postId ORDER BY RAND() LIMIT 3";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $postList = [];
   
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setPostUrl($row["postUrl"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setImage($row["postImage"]);
        $post->setAltTextImage($row["imageAlternateText"]);       
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
         array_push($postList, $post);
        error_log($sql);
      }
    }
    return $postList;
  }

  public static function getPostList()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT *  FROM post AS p JOIN postimages AS pI ON p.postId=pI.postId";
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $postList = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setImage($row["postImage"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
        array_push($postList, $post);
      }
    }
    return $postList;
  }
  public static function getMappedSubCategories($postId)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT subCategoryName, 
    subCategoryId FROM postsubcatmapping 
    JOIN subcategory ON 
    postsubcatmapping.subCatId=subcategory.subCategoryId 
    WHERE postsubcatmapping.postId=" . $postId;
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $count = mysqli_num_rows($result);
    $mappedCategoriesList = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $subCategory = new Subcategory();
        $subCategory->setSubCategoryId($row['subCategoryId']);
        $subCategory->setSubCategoryName($row['subCategoryName']);
        array_push($mappedCategoriesList, $subCategory);
      }
    }
    return $mappedCategoriesList;
  }
  public static function getPostOnHome()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "SELECT * FROM   post
    JOIN postimages AS pI ON post.postId=pI.postId
    WHERE post.appearOnHome=" . 1;
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $postList = [];
    if ($count > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $post = new Post();
        $post->setPostId($row["postId"]);
        $post->setPostTitle($row["postTitle"]);
        $post->setPostDescription($row["postDescription"]);
        $post->setPostCreatedBy($row["postCreatedOn"]);
        $post->setKeywords($row["keywords"]);
        $post->setTitleTag($row["titleTag"]);
        $post->setImage($row["postImage"]);
        $post->setPostUrl($row["postUrl"]);
        $post->setOnHome($row["appearOnHome"]);
        $post->setAltTextImage($row["imageAlternateText"]);
        $post->setPostCreatedBy(date_format(date_create($row["postCreatedOn"]), "d-m-Y"));
        $post->setMappedSubCategory(DBpost::getMappedSubCategories($row["postId"]));
        array_push($postList, $post);
      }
    }
    return $postList;
  }
  public static function update($post)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE `post` SET 
    `postTitle`='" . $post->getPostTitle() .
      "' , `postUrl`='" . $post->getPostUrl() .
      "' , `appearOnHome`='" . $post->getOnHome() .
      "' , `postDescription`='" . $post->getPostDescription() .
      "', `LinkUnder`='".$post->getLinkUnder ().
      "' , `titleTag`='" . $post->getPostTitle() .
      "' , `keywords`='" . $post->getKeywords() .
      "' , `modifiedBy`='" . $post->getModifiedBy() .
      "'  WHERE `postId`=" . $post->getPostId();
    if ($connectionObj->query($sql) === true) {
      if (!empty($post->getImage())) {
        $sql = "UPDATE `postimages` SET `postImage`='" . $post->getImage() .
          "' , `modifiedBy`='" . $post->getModifiedBy() .
          "' , `imageAlternateText`='" . $post->getAltTextImage() .
          "' WHERE `postId`=" . $post->getPostId();
        if ($connectionObj->query($sql) === true) {
        }
      }

      if ($post->getLinkUnder() == "1") {
        $sql = "DELETE FROM postcatmapping WHERE postId=". $post->getPostId();
        if ($connectionObj->query($sql) === true) {
        }
        if (is_array($post->getMappedSubCategory())) {
          $count = count($post->getMappedSubCategory());
          $mapped = $post->getMappedSubCategory();
         
          for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO `postcatmapping`(`postId`, `CatId`) VALUES (" .
              $post->getPostId() .
              "," . $mapped[$i] . ")";
            if ($connectionObj->query($sql) === true) {
            }
          }
        }else {
          $sql = "INSERT INTO `postcatmapping`(`postId`, `CatId`) VALUES (" .
          $post->getPostId() .
            "," . $post->getMappedSubCategory() . ")";
          if ($connectionObj->query($sql) === true) {
          }
        }
      } else {
        $sql = "DELETE FROM postsubcatmapping WHERE postId=". $post->getPostId();
        if ($connectionObj->query($sql) === true) {
        }
        if (is_array($post->getMappedSubCategory())) {
          $count = count($post->getMappedSubCategory());
          $mapped = $post->getMappedSubCategory();
          for ($i = 0; $i < $count; $i++) {
            $sql = "INSERT INTO `postsubcatmapping`(`postId`, `subCatId`) VALUES (" .
            $post->getPostId() .
              "," . $mapped[$i] . ")";
            if ($connectionObj->query($sql) === true) {
            }
          }
        } else {
          $sql = "INSERT INTO `postsubcatmapping`(`postId`, `subCatId`) VALUES (" .
          $post->getPostId() .
            "," . $post->getMappedSubCategory() . ")";
          if ($connectionObj->query($sql) === true) {
          }
        }
      }
    }
  }
  public static function delete($postId)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "DELETE FROM postsubcatmapping WHERE postId=" . $postId;
    if ($connectionObj->query($sql) === true) {
      $sql = "DELETE FROM postimages WHERE postId=" . $postId;
      if ($connectionObj->query($sql) === true) {
      }
      $sql = "DELETE FROM post WHERE postId=" . $postId;
      if ($connectionObj->query($sql) === true) {
      }
    }
  }
}
