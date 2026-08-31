<?php
require_once "../../DB Operations/dbconnection.php";
require_once "../model/expenseModel.php";

class DBExpense
{
  public static function insert($expense)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "insert into expense (`Date`,`Name`,`Description`,`Amount`,`Category`,`Receipts`) 
      values (
      '" . $expense->getDate() . "',
      '" . $expense->getName() . "',
      '" . $expense->getDescription() . "',
      '" . $expense->getAmount() . "',
      '" . $expense->getCategory() . "',
      '" . $expense->getReceipts() .      
      "')";
    error_log($sql);
    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
  }

  public static function getexpensedetails()
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = 'SELECT * FROM expense';
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    $expenselist = [];
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $expense = new Expense();
        $expense->setId($row['id']);
        $expense->setDate($row['Date']);
        $expense->setName($row['Name']);
        $expense->setDescription($row['Description']);
        $expense->setAmount($row['Amount']);
        $expense->setCategory($row['Category']);
        $expense->setReceipts($row['Receipts']);

        array_push($expenselist, $expense);
      }
    } else {
      echo "";
    }
    return $expenselist;
  }

  public static function getexpensebyId($Id)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = 'SELECT * FROM expense';
    $result = $connectionObj->query($sql);
    $count = mysqli_num_rows($result);
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $expense = new Expense();
        $expense->setId($row['id']);
        $expense->setDate($row['Date']);
        $expense->setName($row['Name']);
        $expense->setDescription($row['Description']);
        $expense->setAmount($row['Amount']);
        $expense->setCategory($row['Category']);
        $expense->setReceipts($row['Receipts']);
      }
    } else {
      echo "";
    }
    return $expense;
  }

  public static function update($expense)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "UPDATE expense set Date ='" . $expense->getDate() . "', 
    Name ='" . $expense->getName() . "', 
    Description ='" . $expense->getDescription() . "',
    Amount ='" . $expense->getAmount() . "',
    Category ='" . $expense->getCategory() . "'";

    if (!empty($expense->getReceipts())) {
        $sql .= ",Receipts='" . $expense->getReceipts();
      }
     $sql .="  WHERE id=" . $expense->getId();    
        error_log($sql);

    if ($connectionObj->query($sql) === TRUE) {
    } else {
      echo "Error: " . $sql . "<br>" . $connectionObj->error;
    }
}

  public static function delete($expense)
  {
    $db = ConnectDb::getInstance();
    $connectionObj = $db->getConnection();
    $sql = "DELETE FROM expense WHERE id='" . $expense . "'";
    error_log($sql);
    if ($connectionObj->query($sql) === true) {
    }
  }
}
