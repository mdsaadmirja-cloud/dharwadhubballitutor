<?php
require "../model/expenseModel.php";
require "../Utilities/Sanitization.php";
include "../../Admin/DB Operations/expenseOps.php";
require_once "../Utilities/Helper.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['editedid'])) {
        
        $expense = new Expense();
        $expense->setId(Sanitization::test_input($_POST["editedid"]));
        $expense->setDate(Sanitization::test_input($_POST["date"]));
        $expense->setName(Sanitization::test_input($_POST["name"]));
        $expense->setDescription(Sanitization::test_input($_POST["description"]));
        $expense->setCategory(Sanitization::test_input($_POST["category"]));
        $expense->setAmount(Sanitization::test_input($_POST["amount"]));
        $expense->setReceipts(Sanitization::test_input($_POST["receipts"]));        
        
        DBExpense::update($expense);
    } else if ($_POST["action"] == 'delete') {
        DBExpense::delete($_POST["id"]);
    } else {
        $expense = new Expense();
        $expense->setDate(Sanitization::test_input($_POST["Date"]));
        $expense->setName(Sanitization::test_input($_POST["Name"]));
        $expense->setDescription(Sanitization::test_input($_POST["Description"]));
        $expense->setCategory(Sanitization::test_input($_POST["Category"]));
        $expense->setAmount(Sanitization::test_input($_POST["Amount"]));
        $expense->setReceipts(Sanitization::test_input($_POST["Receipts"]));        

        DBExpense::insert($expense);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {

}


header("location: ../View/Expense.php");