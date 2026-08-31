<?php
require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";

require_once "../DB Operations/ProjectPaymentOps.php";
require_once "../DB Operations/ClientProjectOps.php";

require_once "../model/ProjectPaymentModel.php";
require_once "../model/ClientProjectModel.php";

include_once "header.php";

$dashboard = DBprojectpayment::getPaymentDashboardCounts();
$payments = DBprojectpayment::getAllPayments();
$projects = DBclientproject::getAllProjects()['data'];
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-money-check-alt text-success"></i>
        Project Payments
    </h1>

    <button
        class="btn btn-success shadow-sm"
        data-bs-toggle="modal"
        data-bs-target="#addPaymentModal">

        <i class="fas fa-plus"></i>

        Add Payment

    </button>

</div>

<!-- Dashboard Cards -->

<div class="row">

    <!-- Total Received -->

    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-success shadow h-100 py-2">

            <div class="card-body">

                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">

                            Total Received

                        </div>

                        <div class="h5 mb-0 font-weight-bold text-gray-800">

                            ₹<?= number_format($dashboard['total_received'], 2); ?>

                        </div>

                    </div>

                    <div class="col-auto">

                        <i class="fas fa-wallet fa-2x text-gray-300"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Pending -->

    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-danger shadow h-100 py-2">

            <div class="card-body">

                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">

                            Pending Revenue

                        </div>

                        <div class="h5 mb-0 font-weight-bold text-gray-800">

                            ₹<?= number_format($dashboard['pending_amount'], 2); ?>

                        </div>

                    </div>

                    <div class="col-auto">

                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Today -->

    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-info shadow h-100 py-2">

            <div class="card-body">

                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">

                            Today's Collection

                        </div>

                        <div class="h5 mb-0 font-weight-bold text-gray-800">

                            ₹<?= number_format($dashboard['today_collection'], 2); ?>

                        </div>

                    </div>

                    <div class="col-auto">

                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Month -->

    <div class="col-xl-3 col-md-6 mb-4">

        <div class="card border-left-warning shadow h-100 py-2">

            <div class="card-body">

                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">

                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">

                            This Month

                        </div>

                        <div class="h5 mb-0 font-weight-bold text-gray-800">

                            ₹<?= number_format($dashboard['month_collection'], 2); ?>

                        </div>

                    </div>

                    <div class="col-auto">

                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- Payment List -->

<div class="card shadow mb-4">

    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-primary">

            Payment List

        </h6>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered"
                id="paymentTable"
                width="100%"
                cellspacing="0">

                <thead>

                    <tr>

                        <th>Date</th>

                        <th>Branch</th>

                        <th>Company</th>

                        <th>Client</th>

                        <th>Project</th>

                        <th>Amount</th>

                        <th>Mode</th>

                        <th>Type</th>

                        <th>Transaction</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($payments as $payment) { ?>

                        <tr>

                            <td><?= date("d-m-Y", strtotime($payment->get_payment_date())); ?></td>

                            <td><?= htmlspecialchars($payment->get_branch_name()); ?></td>

                            <td><?= htmlspecialchars($payment->get_company_name()); ?></td>

                            <td><?= htmlspecialchars($payment->get_client_name()); ?></td>

                            <td><?= htmlspecialchars($payment->get_project_name()); ?></td>

                            <td>
                                ₹<?= number_format($payment->get_amount(), 2); ?>
                            </td>

                            <td><?= htmlspecialchars($payment->get_payment_mode()); ?></td>

                            <td><?= htmlspecialchars($payment->get_payment_type()); ?></td>

                            <td><?= htmlspecialchars($payment->get_transaction_no()); ?></td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-info btn-sm viewPayment"

                                    data-date="<?= $payment->get_payment_date(); ?>"
                                    data-branch="<?= htmlspecialchars($payment->get_branch_name()); ?>"
                                    data-company="<?= htmlspecialchars($payment->get_company_name()); ?>"
                                    data-client="<?= htmlspecialchars($payment->get_client_name()); ?>"
                                    data-project="<?= htmlspecialchars($payment->get_project_name()); ?>"
                                    data-amount="<?= $payment->get_amount(); ?>"
                                    data-mode="<?= htmlspecialchars($payment->get_payment_mode()); ?>"
                                    data-type="<?= htmlspecialchars($payment->get_payment_type()); ?>"
                                    data-transaction="<?= htmlspecialchars($payment->get_transaction_no()); ?>"
                                    data-remarks="<?= htmlspecialchars($payment->get_remarks()); ?>">

                                    <i class="fas fa-eye"></i>

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm editPayment"

                                    data-id="<?= $payment->get_id(); ?>"
                                    data-date="<?= $payment->get_payment_date(); ?>"
                                    data-amount="<?= $payment->get_amount(); ?>"
                                    data-mode="<?= htmlspecialchars($payment->get_payment_mode()); ?>"
                                    data-type="<?= htmlspecialchars($payment->get_payment_type()); ?>"
                                    data-transaction="<?= htmlspecialchars($payment->get_transaction_no()); ?>"
                                    data-remarks="<?= htmlspecialchars($payment->get_remarks()); ?>">

                                    <i class="fas fa-edit"></i>

                                </button>

                                <form
                                    action="../Controller/projectpayment-actions.php"
                                    method="POST"
                                    style="display:inline;">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $payment->get_id(); ?>">

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"

                                        onclick="return confirm('Delete this payment?')">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- ==========================================
        ADD PAYMENT MODAL
    =========================================== -->

<div class="modal fade" id="addPaymentModal" tabindex="-1">

    <div class="modal-dialog modal-xl">

        <form action="../Controller/projectpayment-actions.php" method="POST">

            <input type="hidden" name="action" value="add">

            <div class="modal-content">

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">
                        <i class="fas fa-money-check-alt"></i>
                        Add Project Payment
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <!-- Project -->

                        <div class="col-md-6 mb-3">

                            <label>Project *</label>

                            <select
                                name="project_id"
                                id="project_id"
                                class="form-control"
                                required>

                                <option value="">
                                    Select Project
                                </option>

                                <?php foreach ($projects as $project) { ?>

                                    <option value="<?= $project->get_id(); ?>">

                                        <?= htmlspecialchars($project->get_project_name()); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Payment Date -->

                        <div class="col-md-6 mb-3">

                            <label>Payment Date</label>

                            <input
                                type="date"
                                name="payment_date"
                                class="form-control"
                                value="<?= date('Y-m-d'); ?>"
                                required>

                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>Branch</label>

                            <input
                                type="text"
                                id="branch"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Company</label>

                            <input
                                type="text"
                                id="company"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Client</label>

                            <input
                                type="text"
                                id="client"
                                class="form-control"
                                readonly>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>Budget</label>

                            <input
                                type="text"
                                id="budget"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Received</label>

                            <input
                                type="text"
                                id="received"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Pending</label>

                            <input
                                type="text"
                                id="pending"
                                class="form-control"
                                readonly>

                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>Amount *</label>

                            <input
                                type="number"
                                step="0.01"
                                name="amount"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Payment Mode</label>

                            <select
                                name="payment_mode"
                                class="form-control">

                                <option>Cash</option>
                                <option>UPI</option>
                                <option>Bank Transfer</option>
                                <option>Cheque</option>
                                <option>Card</option>

                            </select>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>Payment Type</label>

                            <select
                                name="payment_type"
                                class="form-control">

                                <option>Advance</option>
                                <option selected>Partial</option>
                                <option>Final</option>
                                <option>Refund</option>

                            </select>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label>Transaction No.</label>

                        <input
                            type="text"
                            name="transaction_no"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label>Remarks</label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="fas fa-save"></i>

                        Save Payment

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<!-- ==========================================
        VIEW PAYMENT MODAL
=========================================== -->

<div class="modal fade" id="viewPaymentModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-info text-white">

                <h5 class="modal-title">

                    <i class="fas fa-eye"></i>

                    Payment Details

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label><b>Project</b></label>
                        <input id="view_project" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label><b>Client</b></label>
                        <input id="view_client" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label><b>Company</b></label>
                        <input id="view_company" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label><b>Branch</b></label>
                        <input id="view_branch" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label><b>Payment Date</b></label>
                        <input id="view_date" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label><b>Amount</b></label>
                        <input id="view_amount" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label><b>Payment Type</b></label>
                        <input id="view_type" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label><b>Payment Mode</b></label>
                        <input id="view_mode" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label><b>Transaction No.</b></label>
                        <input id="view_transaction" class="form-control" readonly>
                    </div>

                    <div class="col-md-12">
                        <label><b>Remarks</b></label>
                        <textarea
                            id="view_remarks"
                            class="form-control"
                            rows="3"
                            readonly></textarea>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- ==========================================
        EDIT PAYMENT MODAL
=========================================== -->

<div class="modal fade" id="editPaymentModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form action="../Controller/projectpayment-actions.php" method="POST">

                <input type="hidden" name="action" value="update">

                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header bg-warning">

                    <h5 class="modal-title">

                        <i class="fas fa-edit"></i>

                        Edit Payment

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Payment Date</label>

                            <input
                                type="date"
                                name="payment_date"
                                id="edit_date"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Amount</label>

                            <input
                                type="number"
                                step="0.01"
                                name="amount"
                                id="edit_amount"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Payment Mode</label>

                            <select
                                name="payment_mode"
                                id="edit_mode"
                                class="form-control">

                                <option>Cash</option>
                                <option>UPI</option>
                                <option>Bank Transfer</option>
                                <option>Cheque</option>
                                <option>Card</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Payment Type</label>

                            <select
                                name="payment_type"
                                id="edit_type"
                                class="form-control">

                                <option>Advance</option>
                                <option>Partial</option>
                                <option>Final</option>
                                <option>Refund</option>

                            </select>

                        </div>

                        <div class="col-md-12 mb-3">

                            <label>Transaction No.</label>

                            <input
                                type="text"
                                name="transaction_no"
                                id="edit_transaction"
                                class="form-control">

                        </div>

                        <div class="col-md-12">

                            <label>Remarks</label>

                            <textarea
                                name="remarks"
                                id="edit_remarks"
                                rows="3"
                                class="form-control"></textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i class="fas fa-save"></i>

                        Update Payment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
    document.getElementById("project_id").addEventListener("change", function() {

        let projectId = this.value;

        if (projectId == "") {

            document.getElementById("branch").value = "";
            document.getElementById("company").value = "";
            document.getElementById("client").value = "";
            document.getElementById("budget").value = "";
            document.getElementById("received").value = "";
            document.getElementById("pending").value = "";

            return;
        }

        fetch("../Controller/projectpayment-ajax.php?project_id=" + projectId)

            .then(response => response.json())

            .then(data => {

                document.getElementById("branch").value = data.BranchName;
                document.getElementById("company").value = data.company_name;
                document.getElementById("client").value = data.client_name;

                document.getElementById("budget").value =
                    "₹ " + parseFloat(data.budget).toFixed(2);

                document.getElementById("received").value =
                    "₹ " + parseFloat(data.received).toFixed(2);

                document.getElementById("pending").value =
                    "₹ " + parseFloat(data.pending).toFixed(2);

            })

            .catch(error => {

                console.log(error);

            });

    });
</script>
<script>
    document.querySelectorAll(".viewPayment").forEach(function(btn) {

        btn.addEventListener("click", function() {

            document.getElementById("view_project").value = this.dataset.project;
            document.getElementById("view_client").value = this.dataset.client;
            document.getElementById("view_company").value = this.dataset.company;
            document.getElementById("view_branch").value = this.dataset.branch;

            document.getElementById("view_date").value = this.dataset.date;
            document.getElementById("view_amount").value = "₹ " + this.dataset.amount;

            document.getElementById("view_type").value = this.dataset.type;
            document.getElementById("view_mode").value = this.dataset.mode;

            document.getElementById("view_transaction").value = this.dataset.transaction;

            document.getElementById("view_remarks").value = this.dataset.remarks;

            new bootstrap.Modal(
                document.getElementById("viewPaymentModal")
            ).show();

        });

    });
</script>
<script>
    document.querySelectorAll(".editPayment").forEach(function(btn) {

        btn.addEventListener("click", function() {

            document.getElementById("edit_id").value = this.dataset.id;

            document.getElementById("edit_date").value = this.dataset.date;

            document.getElementById("edit_amount").value = this.dataset.amount;

            document.getElementById("edit_mode").value = this.dataset.mode;

            document.getElementById("edit_type").value = this.dataset.type;

            document.getElementById("edit_transaction").value = this.dataset.transaction;

            document.getElementById("edit_remarks").value = this.dataset.remarks;

            new bootstrap.Modal(
                document.getElementById("editPaymentModal")
            ).show();

        });

    });
</script>
<?php include_once "footer.php"; ?>