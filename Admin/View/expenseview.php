<?php
require "../../Admin/session.php";
include "../../Admin/DB Operations/expenseOps.php";
include "../../Admin/Utilities/Helper.php";
include_once "header.php";

$Id = $_GET['id'];
$expense = DBExpense::getexpensebyId($Id);
?>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Expense Details </h6>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form class="form" action="../Controller/expenseController.php" method="POST" id="myForm" enctype="multipart/form-data">
            <div class="row g-3  ">
                <div class="col-md-6">
                    <h6>Date :</h6>
                    <div class="col-sm-4">
                        <?php echo $expense->getDate(); ?>
                    </div>
                    <hr>
                </div>
                <div class="col-md-6 ">
                    <h6>Title :</h6>
                    <div class="col-sm-12">
                        <?php echo $expense->getName(); ?>
                        <input type="hidden" id="id" name="id" value="<?php echo $expense->getId(); ?>">
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <h6>Description : </h6>
                    <div class="col-sm-12">
                        <?php echo $expense->getDescription(); ?>
                    </div>
                    <hr>
                </div>
                <br />
                <div class="col-md-6">
                    <h6>Amount : </h6>
                    <div class="col-sm-12">
                        <?php echo $expense->getAmount(); ?>
                    </div>
                    <hr>
                </div>
                <br />
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <h6> Category : </h6>
                    <div class="col-sm-12">
                        <?php echo $expense->getCategory(); ?>
                    </div>
                    <hr>
                </div>
            </div>
            <!-- <div class="col-sm-12">
                        <select class="custom-select" id="Category" name="Category">
                            <option value="">Select</option>
                            <option value="Rent">Rent</option>
                            <option value="Bills">Bills</option>
                        </select>
            </div> -->

            <br />
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <h6>Receipts : </h6>
                    <div class="col-sm-12">
                        <input type="file" name="Receipts" id="Receipts" class="form-control" form="myForm" hidden>
                        <img src="<?php echo '../uploads/', $expense->getReceipts(); ?>" alt="Admin" class="" width="200" height="200" />
                    </div>
                </div>
            </div>
            <br />
        </form>
    </div>
</div>
<?php include "footer.php"; ?>