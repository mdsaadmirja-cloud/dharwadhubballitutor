<?php
require "../../Admin/session.php";
require_once "../DB Operations/termsandconditionsOps.php";
include "../../Admin/DB Operations/FeesOps.php";
// include "../../Admin/model/Feesmodel.php";
include "../../Admin/Utilities/Helper.php";
include "header.php";
$id = $_GET['id'];
$collectfees = DBfees::collectionoffees($id);
?>
<style>
#enquery_length {
    float: left;
    width: 50%;
    display: inline;
    margin-left: 100px;
}

#feepaymentlist_length {
    float: left;
    width: 50%;
    display: inline;
    margin-left: 100px;
}
.table{
    width:100%;
    
}


</style>


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h6 class="">Fees Details </h6>
            </div>
            <div class="col-md-6">
                <div class="form-check float-right">
                    <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="option2">Edit <i class="fas fa-edit"></i></label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="feepaymentlist">
            <thead>
                <tr>
                    <th class="p-2">Paid Date</th>
                    <th>Base Amount</th>
                    <th>GST Amount</th>
                    <th>Paid Fees</th>
                    <th>Pending Fees</th>
                    <th>Payment Mode</th>
                    <th>Due date</th>
                    <th>Fee Receipt</th>
                    
                </tr>
            </thead>
            <?php

            echo  "<tbody>";
            $feesdetails = DBfees::viewfeesdetails($id);
            $latestGstRate = 0;
            $totalBaseAmount = 0;
            $totalGstAmount = 0;
            $totalPaidFees = 0;
            $latestPendingFees = 0;
            $totalFeesWithGST = 0;
            
            // Get latest GST rate and calculate totals from all previous payments
            if (!empty($feesdetails)) {
                $latestRecord = end($feesdetails);
                $latestGstRate = $latestRecord->get_gstrate() ?? 0;
                
                // Calculate cumulative totals from all previous payments
                foreach ($feesdetails as $fees) {
                    $totalBaseAmount += ($fees->get_baseamount() ?? 0);
                    $totalGstAmount += ($fees->get_gstamount() ?? 0);
                    $totalPaidFees += ($fees->get_pfees() ?? 0);
                }
                
                // Calculate total fees with GST: Base Amount + GST Amount
                // Use the base amount from TotalFees field (which is the base amount)
                $baseAmountFromTotalFees = $collectfees->get_tfees() ?? 0;
                if ($baseAmountFromTotalFees > 0 && $latestGstRate > 0) {
                    $gstAmountOnBase = ($baseAmountFromTotalFees * $latestGstRate) / 100;
                    $totalFeesWithGST = $baseAmountFromTotalFees + $gstAmountOnBase;
                } else {
                    // If no GST, total is just base amount
                    $totalFeesWithGST = $baseAmountFromTotalFees;
                }
                
                // Calculate correct pending fees: Total with GST - All Paid Fees
                $latestPendingFees = $totalFeesWithGST - $totalPaidFees;
                if ($latestPendingFees < 0) $latestPendingFees = 0;
            }
            
            $rowIndex = 0;
            foreach ($feesdetails as $fees) {
                $baseAmount = $fees->get_baseamount() ?? 0;
                $gstAmount = $fees->get_gstamount() ?? 0;
                $rowIndex++;
                echo "<tr><td> "  .  $fees->get_modifieddate() . 
                "</td><td>"  . number_format($baseAmount, 2) .
                "</td><td>" . number_format($gstAmount, 2) .
                "</td><td>"  . $fees->get_pfees() .
                "</td><td>" . $fees->get_pendingfees() . 
                "</td><td>" . $fees->get_pmode() .  
                "</td><td>" . $fees->get_duedate() . "</td><td>"
                    . '<button class="btn btn-success view-receipt-btn" data-bs-toggle="modal" data-bs-target="#TransactionModal" role="button" data-admit-id="' . $collectfees->get_admitid() . '"> View Fee Receipt </button>' . '</td></tr>';
            }
            echo  "</tbody>";
            ?>
        </table>
        <div class="row">
            <div class="col-md-12">
                
                <form class="form" action="../Controller/feesaddition.php" method="POST" id="myForm"
                    enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="col-md-6 control-label">Full Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="name" placeholder="Full Name" name="name" class="form-control"
                                    value="<?php echo $collectfees->get_name(); ?>">

                                <input type="hidden" id="admitid" name="admitid"
                                    value="<?php echo $collectfees->get_admitid(); ?>">
                                <input type="hidden" id="courseid" name="courseid"
                                    value="<?php echo $collectfees->get_courseid(); ?>">
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="coursesopted" class="col-md-6 control-label">Courses
                                Opted</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="coursesopted" name="coursesopted" required
                                    value="<?php echo $collectfees->get_coursesopted(); ?>">
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="phone" class="col-md-6 control-label">Phone</label>
                            <div class="col-sm-12">
                                <input type="tel" id="phone" placeholder="Phone" name="phone" class="form-control"
                                    value="<?php echo $collectfees->get_phone(); ?>">
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="tfees" class="col-md-6 control-label">Total Fees (Base Amount)</label>
                            <div class="col-sm-12">
                                <input type="text" id="tfees" name="tfees" class="form-control" required
                                    value="<?php echo $collectfees->get_tfees() ?>">
                                <small class="text-muted">Enter amount before GST</small>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="pfees" class="col-md-6 control-label">Fees Paid (This Payment)</label>
                            <div class="col-sm-12">
                                <input type="text" id="pfees" name="pfees" class="form-control" required
                                    value="">
                                <small class="text-muted">Enter amount for this payment only</small>
                                <input type="hidden" id="previousPaidFees" value="<?php echo $totalPaidFees ?? $collectfees->get_pfees(); ?>">
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="pendingfees" class="col-md-6 control-label">Pending Fees</label>
                            <div class="col-sm-12">
                                <input type="text" id="pendingfees" name="pendingfees" class="form-control" required
                                    value="<?php echo isset($latestPendingFees) ? number_format($latestPendingFees, 2) : $collectfees->get_pendingfees(); ?>">
                                <small class="text-muted">After GST calculation</small>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="feesplan" class="col-md-6 control-label">Fees Plan</label>
                            <div class="col-sm-12">
                                <select class="form-select" id="feesplan" name="feesplan" required>
                                    <option value="">Fees Plan</option>
                                    <option value="Part Payment">Part Payment</option>
                                    <option value="Full Payment">Full Payment</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="col-md-6">
                         <label for="duedate" class="col-md-6 control-label">Due Date</label>
                          <div class="col-sm-12">
                         <input type="date" id="duedate" name="duedate" class="form-control" 
                         value="<?php echo ($collectfees->get_duedate() !== NULL) ? htmlspecialchars($collectfees->get_duedate()) : ''; ?>" required>
    </div>
</div>
                        <br/>
                      <div class="col-md-6">
                            <label for="pmode" class="col-md-6 control-label">Payment Mode</label>
                            <div class="col-sm-12">
                                <select class="form-select" id="pmode" name="pmode" required>
                                    <option value=""></option>
                                    <option value="Cash">Cash</option>
                                    <option value="Net Banking">Net Banking</option>
                                    <option value="Debit/Credit Card">Debit/Credit Card </option>
                                    <option value="UPI transaction">UPI transaction</option>
                                </select>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="pdescription" class="col-md-6 control-label">Payment
                                Description</label>
                            <div class="col-sm-12">
                                <input type="text" id="pdescription" name="pdescription"
                                    placeholder="Payment Description" class="form-control" required>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="gstrate" class="col-md-6 control-label">GST Rate (%)</label>
                            <div class="col-sm-12">
                                <input type="number" id="gstrate" name="gstrate" class="form-control" 
                                    placeholder="Enter GST Rate" step="0.01" min="0" max="100" 
                                    value="<?php echo isset($latestGstRate) ? $latestGstRate : 0; ?>">
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="baseamount" class="col-md-6 control-label">Base Amount</label>
                            <div class="col-sm-12">
                                <input type="text" id="baseamount" name="baseamount" class="form-control" 
                                    placeholder="Base Amount" readonly>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="gstamount" class="col-md-6 control-label">GST Amount</label>
                            <div class="col-sm-12">
                                <input type="text" id="gstamount" name="gstamount" class="form-control" 
                                    placeholder="GST Amount" readonly>
                            </div>
                        </div>
                        <br />

                        <div class="col-md-6">
                            <label for="totalfeeswithgst" class="col-md-6 control-label">Total Fees with GST</label>
                            <div class="col-sm-12">
                                <input type="text" id="totalfeeswithgst" name="totalfeeswithgst" class="form-control" 
                                    placeholder="Total Fees with GST" readonly style="font-weight: bold; color: #28a745;">
                            </div>
                        </div>
                        <br />

                        <div>
                            <button class="btn btn-success" id="btn" type="submit" name="submit">Update <i
                                    class="fas fa-save"></i></button>
                           
                        </div>
                </form>
             
                </br>
            </div>
        </div>
       
    </div>

</div>
<div class="modal fade" id="TransactionModal" tabindex="-1" role="dialog" aria-labelledby="TransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="row gutters-sm">
            <div class="col-md-2 mb-2">
            </div>
            <div class="col-md-12">
                <form class="form" method="POST" id="TransactionForm" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                                    <div class="col-md-12" id="printTransaction">
                                      
      
                                        <table class="table table-bordered  container" id="StudentTransaction">
                                            <thead>
                                                <tr>
                                                    <td style="text-align:left" colspan="7">
                                                    <h1 style="text-align:left">DharwadHubballiTutor</h1> 
                                                    <p style="text-align:left">  JP Nippani Complex,Gandhinagar</p>
                                                    <p style="text-align:left"><i class="fas fa-phone"></i>  +919741237334 ,+918007961759</p>
                                                    <p style="text-align:left"><i class="fas fa-globe"></i> www.dharwadhubballitutor.com </p>
                                                    </td>
                                                    <td colspan="4" style="text-align: center;">
                                                    <img alt="logo" src="../media/logo.png" width="100px" height="100px"> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                <td colspan="10" style="text-align:center">
                                                <h4>Invoice</h4>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <!-- <th >Customer Name </th> -->
                                                    <td colspan="10" style="text-align:left">
                                                        <h5>Student Details</h5>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td colspan="4" >
                                                        Student Name : <span id="studName"><?php echo $collectfees->get_name(); ?></span>
                                                    </td>
                                                    <td colspan="6">
                                                        Admission Id : <span><?php echo $collectfees->get_admitid(); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        Address : <span><?php echo $collectfees->getAddress(); ?></span>
                                                    </td>
                                                    <td colspan="6">
                                                        Course Opted: <span><?php echo $collectfees->get_coursesopted(); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        Date : <span id="Date"><?php echo $date = date('d-m-Y '); ?></span>
                                                    </td>
                                                    <td colspan="6">
                                                        Total Amount (Base) : <span id="invoiceBaseAmount"><?php echo $collectfees->get_tfees() ?></span><br>
                                                        GST Amount : <span id="invoiceGstAmount">0.00</span><br>
                                                        Total Amount with GST : <span id="invoiceTotalWithGst" style="font-weight: bold;"><?php echo $collectfees->get_tfees() ?></span>
                                                    </td>
                                                    <div id="POcode" style="display:none"></div>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:center">Sl</th>
                                                    <th style="text-align:center">Payment Date</th>
                                                    <th style="text-align:center">Mode of payment</th>
                                                    <th style="text-align:center" colspan=1>Base Amount</th>
                                                    <th style="text-align:center" colspan=1>GST Amount</th>
                                                    <th style="text-align:center" colspan=1>Pending Amount</th>
                                                    <th style="text-align:center" colspan=1>Due Date</th>
                                                    <th style="text-align:center" colspan=4>Paid Amount</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
        
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    
        
                                                    <td style="text-align:right" rowspan="" colspan="3">Total</td>
                                                    <td id="totalBaseAmount" style="text-align:center" ></td>
                                                    <td id="totalGstAmount" style="text-align:center" ></td>
                                                    <td id="pending" style="text-align:center" ></td>
                                                    <td id="totalpaidAmount" style="text-align:center"  colspan="4"></td>
                                                </tr>
                                            </tfoot>
                                            <tr>
        
                                            </tr>
                                        </table>
                                        <br><br><br><br><br><br>
<div style="text-align:right">
    Authorized Signatory
</div>


                                         <div class="form-group">
                                            <div class="row">
                                                 <input type="hidden" name="createdby" id="createdby" class="form-control"
                                                    required value="" />
                                                <input type="hidden" name="modifiedby" id="modifiedby" class="form-control"
                                                    required value="" />
                                                <input type="hidden" id="admitID"
                                                    value="<?php echo $collectfees->get_admitid(); ?>" />
                                            </div>
                                         </div>
                                         <br><br><br><br><br><br><br><hr>
                                         <div class="form-group">
                                              <div class="row">
                                                  <h4>Terms and Conditions:</h4>
                                                   <div style="font-size:13px">
                                                   <?php $term = DBterms::gettermsandconditions(); 
                                                   echo $term->getdescription();?>
                                                   </div>
                                              </div>
                                         </div>
                                     
                                    </div>
                        </div>
                        <div class="modal-footer" id="footer">
                                    <input type="submit" name="submit" id="PDF" class="btn btn-success"
                                        value="Save AS PDF" />
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>     
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<?php include ("footer.php");?>

<script>
$(document).ready(function() {
    $("#feepaymentlist").DataTable({})
    // Disable all form inputs initially
    $("#myForm :input").prop("disabled", true);
    // Keep GST fields, base amount, and total fees with GST readonly even when enabled
    $("#baseamount, #gstamount, #totalfeeswithgst").prop("readonly", true);
    
    // Edit button functionality - simple click handler on label
    $('label[for="option2"]').on('click', function() {
        // Small delay to let Bootstrap handle the radio check first
        setTimeout(function() {
            if ($('#option2').is(':checked')) {
                enableEditMode();
            }
        }, 50);
    });
    
    // Also handle change event on radio button directly
    $('#option2').on('change', function() {
        if ($(this).is(':checked')) {
            enableEditMode();
        } else {
            disableEditMode();
        }
    });
    
    function enableEditMode() {
        // Force enable all form inputs - this overrides any other disabling
        $('#myForm input, #myForm select, #myForm textarea, #myForm button').each(function() {
            if ($(this).attr('id') !== 'baseamount' && $(this).attr('id') !== 'gstamount' && $(this).attr('id') !== 'totalfeeswithgst') {
                $(this).prop('disabled', false);
            }
        });
        
        // Keep GST amount, base amount, and total fees with GST readonly (calculated fields)
        $("#baseamount, #gstamount, #totalfeeswithgst").prop("readonly", true).prop("disabled", false);
        
        // Handle total fees field
        var tfeesVal = $('#tfees').val();
        if (!tfeesVal || tfeesVal == '' || parseFloat(tfeesVal) <= 0) {
            $('#tfees').prop('readonly', false).prop('disabled', false);
            $('#tfees').focus();
            $('#pfees').prop('disabled', true);
        } else {
            // If total fees exists, make it readonly (can't change base amount after first entry)
            $('#tfees').prop('readonly', true).prop('disabled', false);
            $('#pfees').prop('disabled', false);
        }
        
        // Enable submit button
        $('#btn').prop('disabled', false);
        
        // Recalculate GST when entering edit mode
        calculateGST();
        
        console.log('Edit mode enabled - form fields should be editable now');
    }
    
    function disableEditMode() {
        $("#myForm :input").prop("disabled", true);
        $("#baseamount, #gstamount, #totalfeeswithgst").prop("readonly", true);
    }

    $("#feesplan").change(function() {

        if ($(this).val() == "Part Payment") {
            $("#duedatediv").show();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            $("#duedate").attr("min", today);
            $("#duedate").attr('disabled', false);
            $("#btn").attr("disabled", false);
        } else {
            debugger;
            var totalFeesWithGST = parseFloat($("#totalfeeswithgst").val()) || parseFloat($("#tfees").val()) || 0;
            var paidFees = parseFloat($("#pfees").val()) || 0;
            var pendingFees = parseFloat($("#pendingfees").val()) || 0;
            
            if (paidFees > 0 && pendingFees > 0.01) {
                $("#btn").attr("disabled", true);
                alert("Fees is still due");
            }

            $("#duedate").attr('disabled', true);
        }
    });

    $("#tfees").on('change keyup', function() {
        var tfeesVal = parseFloat($(this).val()) || 0;
        if (tfeesVal > 0) {
            $('#pfees').attr('disabled', false);
        }
        calculateGST();
        updatePendingFees();
    });

    $("#pfees").on('change keyup', function() {
        // Ensure GST is calculated first
        calculateGST();
        
        // Get previous paid fees and current payment
        var previousPaidFees = parseFloat($("#previousPaidFees").val()) || 0;
        var currentPaidFees = parseFloat($(this).val()) || 0;
        var totalPaidFees = previousPaidFees + currentPaidFees;
        
        var totalFeesWithGST = parseFloat($("#totalfeeswithgst").val()) || 0;
        
        if (totalFeesWithGST == 0) {
            // If total with GST not calculated, calculate it
            var totalFees = parseFloat($("#tfees").val()) || 0;
            var gstRate = parseFloat($("#gstrate").val()) || 0;
            var gstAmount = (totalFees * gstRate) / 100;
            totalFeesWithGST = totalFees + gstAmount;
            $("#totalfeeswithgst").val(totalFeesWithGST.toFixed(2));
        }
        
        // Check if total paid (previous + current) exceeds total with GST
        if (totalPaidFees <= totalFeesWithGST) {
            updatePendingFees();
        } else {
            var maxAllowedPayment = totalFeesWithGST - previousPaidFees;
            if (maxAllowedPayment < 0) maxAllowedPayment = 0;
            alert("Total paid fees (including previous payments) cannot exceed total fees with GST!\nMaximum payment allowed: " + maxAllowedPayment.toFixed(2));
            $(this).val(maxAllowedPayment.toFixed(2));
            updatePendingFees();
        }
    });

    // GST Calculation Functions - Calculate GST on Total Fees
    function calculateGST() {
        var totalFees = parseFloat($("#tfees").val()) || 0;
        var gstRate = parseFloat($("#gstrate").val()) || 0;
        
        if (totalFees > 0) {
            // Base amount is the total fees entered
            var baseAmount = totalFees;
            
            // Calculate GST amount on base amount
            var gstAmount = 0;
            if (gstRate > 0) {
                gstAmount = (baseAmount * gstRate) / 100;
            }
            
            // Total fees with GST
            var totalFeesWithGST = baseAmount + gstAmount;
            
            $("#baseamount").val(baseAmount.toFixed(2));
            $("#gstamount").val(gstAmount.toFixed(2));
            $("#totalfeeswithgst").val(totalFeesWithGST.toFixed(2));
        } else {
            $("#baseamount").val("0.00");
            $("#gstamount").val("0.00");
            $("#totalfeeswithgst").val("0.00");
        }
    }

    // Calculate GST when GST Rate changes
    $("#gstrate").on('change keyup', function() {
        calculateGST();
        updatePendingFees();
    });

    // Function to update pending fees based on total fees with GST
    function updatePendingFees() {
        // Get total fees with GST (calculated)
        var totalFeesWithGST = parseFloat($("#totalfeeswithgst").val()) || 0;
        
        // If total fees with GST is not calculated yet, calculate it
        if (totalFeesWithGST == 0) {
            var totalFees = parseFloat($("#tfees").val()) || 0;
            var gstRate = parseFloat($("#gstrate").val()) || 0;
            var gstAmount = (totalFees * gstRate) / 100;
            totalFeesWithGST = totalFees + gstAmount;
            $("#totalfeeswithgst").val(totalFeesWithGST.toFixed(2));
        }
        
        // Get previous paid fees (from all previous transactions)
        var previousPaidFees = parseFloat($("#previousPaidFees").val()) || 0;
        
        // Get current paid fees (this is the NEW amount being paid in this transaction)
        var currentPaidFees = parseFloat($("#pfees").val()) || 0;
        
        // Calculate total paid fees: Previous + Current
        var totalPaidFees = previousPaidFees + currentPaidFees;
        
        // Calculate pending fees: Total with GST - Total Paid Fees (Previous + Current)
        var pendingFees = totalFeesWithGST - totalPaidFees;
        
        // Ensure pending fees is not negative
        if (pendingFees < 0) {
            pendingFees = 0;
        }
        
        $("#pendingfees").val(pendingFees.toFixed(2));
        
        // Update total fees with GST field if not already set
        if (parseFloat($("#totalfeeswithgst").val()) == 0 && totalFeesWithGST > 0) {
            $("#totalfeeswithgst").val(totalFeesWithGST.toFixed(2));
        }
    }


    // Check if fees are fully paid (compare with total fees with GST)
    function checkFeesCompletion() {
        var totalFeesWithGST = parseFloat($("#totalfeeswithgst").val()) || parseFloat($("#tfees").val()) || 0;
        var paidFees = parseFloat($("#pfees").val()) || 0;
        
        // Only disable form if fees are fully paid AND edit mode is NOT active
        if (Math.abs(paidFees - totalFeesWithGST) < 0.01 && !$('#option2').is(':checked')) {
            // Only disable if edit button is not checked
            $("#myForm :input").prop("disabled", true);
        }
        // Always allow edit button to be clicked
        $("#option2").prop("disabled", false);
        $('label[for="option2"]').removeClass('disabled').css('pointer-events', 'auto');
    }
    
    // Initialize on page load - calculate GST and update pending fees
    calculateGST();
    updatePendingFees();
    
    // Only check fees completion if edit mode is not active
    if (!$('#option2').is(':checked')) {
        checkFeesCompletion();
    }
    

    // Handle View Fee Receipt button clicks
    $(document).on('click', '.view-receipt-btn', function() {
        var admitId = $(this).data('admit-id') || $('#admitID').val();
        $('#admitID').val(admitId);
    });
    
    $('#TransactionModal').on('show.bs.modal', function(e) {
        var rowid = $('#admitID').val();
        
        if (!rowid) {
            console.error('Admission ID not found');
            return;
        }
        
        var transactionUrl = config.developmentPath +
            "/Admin/Controller/feesaddition.php?admitID=" + rowid;
        console.log('Loading transaction data from:', transactionUrl);

        $.getJSON(transactionUrl, function(data) {
            console.log('Transaction data received:', data);
            var count = 1;
            var TotalPendingAmount = 0;
            var TotalPaidAmount = 0;
            var TotalBaseAmount = 0;
            var TotalGstAmount = 0;
            
            // Clear existing rows
            $("#StudentTransaction tbody").empty();
            
            if (!data || data.length === 0) {
                console.log('No transaction data found');
                return;
            }
            $.each(data, function(index, value) {

                $('#StudentTransaction tbody').
                append($(document.createElement('tr')).prop({

                }));

                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: count++,
                    style:"text-align:center"

                }));

                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: value.modifieddate,
                    style:"text-align:center"
                }));

                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: value.pmode,
                    style:"text-align:center"
                }));
                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: parseFloat(value.baseamount || 0).toFixed(2),
                    style:"text-align:center"
                }));
                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: parseFloat(value.gstamount || 0).toFixed(2),
                    style:"text-align:center"
                }));
                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: value.pendingfees,
                    style:"text-align:center"
                }));
                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: value.duedate,
                    style:"text-align:center"
                }));
               
                $('#StudentTransaction tr:last').
                append($(document.createElement('td')).prop({
                    innerHTML: value.pfees,
                    colspan:4,
                    style:"text-align:center"
                }));
                debugger;
                TotalPendingAmount = parseInt(value.pendingfees)
                TotalPaidAmount = parseFloat(TotalPaidAmount) + parseFloat(value.pfees || 0)
                TotalBaseAmount = parseFloat(value.baseamount) ;
                TotalGstAmount = parseFloat(TotalGstAmount) + parseFloat(value.gstamount || 0)
            });
            $('#pending').text(TotalPendingAmount);
            $('#totalpaidAmount').text(TotalPaidAmount.toFixed(2));
            $('#totalBaseAmount').text(TotalBaseAmount.toFixed(2));
            $('#totalGstAmount').text(TotalGstAmount.toFixed(2));
            
            // Update invoice header with GST totals
            var invoiceTotalBase = TotalBaseAmount.toFixed(2);
            var invoiceTotalGST = TotalGstAmount.toFixed(2);
            var invoiceTotalWithGST = (TotalBaseAmount + TotalGstAmount).toFixed(2);
            
            $('#invoiceBaseAmount').text();
            $('#invoiceGstAmount').text(invoiceTotalGST);
            $('#invoiceTotalWithGst').text(invoiceTotalWithGST);
        }).fail(function(jqxhr, textStatus, error) {
            console.error('Error loading transaction data:', textStatus, error);
            alert('Error loading fee receipt data. Please try again.');
        });
    });


    $('#TransactionForm').submit(function(e) {
        debugger;
        $('#footer').hide();
        var content = $('#printTransaction').html();
        var fileName = $('#studName').text() + $('#Date').text()+ '_Transaction';

        var uniturl = config.developmentPath +
            "/Admin/Controller/pdfGeneratorContorller.php";

        $.ajax({
            type: "POST",
            url: uniturl,
            data: {
                "modifiedby": $('#modifiedby').val(),
                "admitID": $('#admitID').val(),
                "fileType": "Fee Receipts",
                // "waterMarked": waterMarked,
                "fileName": fileName,
                "html": content
            },
            dataType: "json",
            encode: true,
        }).done(function(data) {
            console.log(data);
            setTimeout(function() {
                $('#printTransaction').html('');
            }, 20000);
        });

        window.open(config.developmentPath + '/Admin/uploads/Fee Receipts/' + fileName
            .trim() + '.pdf');
    });

});
</script>