<?php
require "../../Admin/session.php";

include "../../Admin/DB Operations/ServicesOps.php";
require_once "../../Admin/model/Coursesmodel.php";
require_once "../../Admin/model/Servicemodel.php";
require_once "../../Admin/DB Operations/CoursesOps.php";
include "../../Admin/Utilities/Helper.php";
?>
<html>

<head>
    <title>Services</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
    <link rel=stylesheet href=https://use.fontawesome.com/releases/v5.0.7/css/all.css />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel=stylesheet href="../../Admin/css/dharwadhubballitutoradmin.css" />

<style>
    <style>
    #enquery_length {
        float: left;
        width: 50%;
        display: inline;
        margin-left: 100px;
    }

    #paymentlist_length {
        float: left;
        width: 50%;
        display: inline;
        margin-left: 100px;
    }
    </style>
</style>


</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <?php
                    include "../../Admin/navbar.php";
                    ?>
            </div>
            <div class="col-md-9">
                <div class="container">
                    <h2 class="h2">Service Details </h2>
                    <?php 
                        $id=$_GET['id'];
                        $payment= DBservice::paymentdetails($id);
                              
                    ?>
                    <br />
                    <div class="row gutters-sm">
                        <div class="col-md-3 mb-3">

                            <br />
                            <div class="form-check text-center">
                                <input type="radio" class="btn-check" name="options" id="option2">
                                <label class="btn btn-danger" for="option2">Edit</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <form class="form" action="../Controller/newservice.php" method="POST" id="myForm"
                                enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="col-md-6 control-label">Full Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="name" placeholder="Full Name" name="name"
                                                class="form-control" value="<?php
                                                 echo $payment->get_name();
                                                    ?>">
                                                <input type="hidden" id="candidateid" name="candidateid"
                                                value="<?php echo $payment->get_candidateid(); ?>">

                                            <input type="hidden" id="id" name="id"
                                                value="<?php echo $payment->get_Id(); ?>">

                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="email" class="col-md-6 control-label">Email
                                            </label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="email" name="email"
                                                required value="<?php
                                                    echo $payment->get_email();
                                                    ?>">
                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="services" class="col-md-6 control-label">Service
                                            Opted</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="services" name="services"
                                                required value="<?php
                                                    echo $payment->get_services();
                                                    ?>">
                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="phone" class="col-md-6 control-label">Phone</label>
                                        <div class="col-sm-12">
                                            <input type="tel" id="phone" placeholder="Phone" name="phone"
                                                class="form-control" value="<?php
                                                    echo $payment->get_phone();
                                                    ?>">
                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="totalamt" class="col-md-6 control-label">Total Amount</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="totalamt" name="totalamt" class="form-control"
                                                required value="<?php echo $payment->get_totalamt() ?>">
                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="paidamt" class="col-md-6 control-label">Paid Amount</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="paidamt" name="paidamt" class="form-control" required
                                                value="<?php echo $payment->get_paidamt() ?>">
                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="pendingamt" class="col-md-6 control-label">Pending Amount</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="pendingamt" name="pendingamt" class="form-control"
                                                required value="<?php echo $payment->get_pendingamt() ?>">
                                        </div>
                                    </div>
                                    <br />

                                    <div class="col-md-6">
                                        <label for="paymentmode" class="col-md-6 control-label">Payment Mode</label>
                                        <div class="col-sm-12">
                                            <select class="form-select" id="paymentmode" name="paymentmode" required>
                                                <option value=""></option>
                                                <option value="Cash">Cash</option>
                                                <option value="Net Banking">Net Banking</option>
                                                <option value="Debit/Credit Card">Debit/Credit Card </option>
                                                <option value="UPI transaction">UPI transaction</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br />
                                    <div>
                                        <button class="btn btn-success" id="btn" type="submit"
                                            name="submit">Update</button>
                                        <br />
                                    </div>
                                </div>
                            </form>
                            </br>
                        </div>
                    </div>
                </div>
       

            <div>
                    <br /></br>
                    <table class="enquiries center" id="paymentlist">
                        <thead>
                            <tr>

                                <th>Paid Date</th>
                                <th>Paid Amount</th>
                                <th>Pending Amount</th>
                                <th>Payment Mode</th>
                                <th> Payment Receipt </th>
                            </tr>
                        </thead>
                        <?php
                    
                    echo  "<tbody>";
                   $paymentdetails= DBservice::viewpaymentdetails($id);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
                   foreach($paymentdetails as $payment) 
                   {
                       echo "<tr><td> "  .  $payment->get_modifieddate(). "</td><td>"  . $payment->get_paidamt(). "</td><td>". $payment->get_pendingamt(). "</td><td>" .$payment->get_paymentmode(). "</td><td>"
                       .'<a class="btn btn-danger" id="paymentreceipt" name="paymentreceipt" href="../../Admin/uploads/Payment Receipts/'.$payment->get_paymentreceipt().'" role="button" download>Payment Receipt </a>'.'</td></tr>' ;
                   }
                   echo  "</tbody>";
                   ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
    $(document).ready(function() {
        $("#myForm :input").prop("disabled", true);
        $('input[type=radio][name=options]').click(function() {
            $('#myForm :input').prop('disabled', false);
            if (!parseInt($('#totalamt').val())) {
                $('#totalamt').focus();
                $('#paidamt').attr('disabled', true);
            } else {
                $('#totalamt').attr('readonly', true);
            }
        });

        // $("#feesplan").change(function() {

        //     if ($(this).val() == "Part Payment") {
        //         $("#duedatediv").show();
        //         var today = new Date();
        //         var dd = String(today.getDate()).padStart(2, '0');
        //         var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        //         var yyyy = today.getFullYear();

        //         today = yyyy + '-' + mm + '-' + dd;

        //         $("#duedate").attr("min", today);
        //         $("#duedate").attr('disabled', false);
        //         $("#btn").attr("disabled", false);
        //     } else {
        //         debugger;
        //         if (parseInt($("#paidamt").val()) > 0 && parseInt($("#pendingamt").val()) != 0) {
        //             $("#btn").attr("disabled", true);
        //             alert("Fees is still due");
        //         }

        //         $("#duedate").attr('disabled', true);
        //     }
        // });

        $("#totalamt").change(function() {
            if (parseInt($(this).val()) > 0) {
                $('#paidamt').attr('disabled', false);
            }
        });
    
        $("#paidamt").change(function() {
            debugger;
            if (parseInt($(this).val()) < parseInt($("#totalamt").val())) {
                if ($("#pendingamt").val() == 0) {
                    var pendingamt = $("#totalamt").val() - $("#paidamt").val();
                } else {
                    var pendingamt = $("#pendingamt").val() - $("#paidamt").val();

                }
                $("#pendingamt").val(pendingamt);
            }


        });

        if (parseInt($("#paidamt").val()) == parseInt($("#totalamt").val())) {
            $("#myForm :input").prop("disabled", true);
            $("#option2").prop("disabled", true);
        }


    });
    </script>



</body>


</html>