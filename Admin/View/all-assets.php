<?php
require "../session.php";
require_once "../DB Operations/AssetOps.php";
require_once "../DB Operations/AssetAssignmentOps.php";

$trainers = AssetAssignmentOps::getAllActiveTrainers();
include "header.php";

$assets = DBasset::getAllAssets();

$totalAssets = count($assets);

$available = 0;
$assigned = 0;
$maintenance = 0;
$lost = 0;

foreach ($assets as $row) {

    switch (strtolower($row['status'])) {

        case "available":
            $available++;
            break;

        case "assigned":
            $assigned++;
            break;

        case "maintenance":
            $maintenance++;
            break;

        case "lost":
            $lost++;
            break;
    }
}
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800">
            <i class="fa fa-cubes"></i>
            Asset Management
        </h1>

        <a href="add-asset.php" class="btn btn-primary shadow-sm">
            <i class="fa fa-plus"></i>
            Add Asset
        </a>

    </div>

    <!-- Dashboard Cards -->

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-left-primary shadow h-100 py-2">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Assets
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $totalAssets; ?>
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fa fa-cubes fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-left-success shadow h-100 py-2">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Available
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $available; ?>
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fa fa-check-circle fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-left-info shadow h-100 py-2">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Assigned
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $assigned; ?>
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fa fa-user fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-left-warning shadow h-100 py-2">

                <div class="card-body">

                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Maintenance
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $maintenance; ?>
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="fa fa-wrench fa-2x text-gray-300"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="card shadow mb-4">

        <div class="card-header py-3 d-flex justify-content-between">

            <h6 class="m-0 font-weight-bold text-primary">
                All Assets
            </h6>

            <a href="add-asset.php" class="btn btn-success btn-sm">

                <i class="fa fa-plus"></i>

                New Asset

            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="assetTable"
                    class="table table-striped table-hover table-bordered"
                    style="width:100%">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Asset Code</th>

                            <th>Asset Name</th>

                            <th>Category</th>

                            <th>Brand</th>

                            <th>Branch</th>

                            <th>Status</th>

                            <th width="180">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        $i = 1;

                        foreach ($assets as $asset) {

                            $statusColor = "secondary";

                            switch (strtolower($asset['status'])) {

                                case "available":
                                    $statusColor = "success";
                                    break;

                                case "assigned":
                                    $statusColor = "info";
                                    break;

                                case "maintenance":
                                    $statusColor = "warning";
                                    break;

                                case "lost":
                                    $statusColor = "danger";
                                    break;
                            }
                        ?>

                            <tr>

                                <td><?= $i++; ?></td>

                                <td><?= htmlspecialchars($asset['asset_code']); ?></td>

                                <td><?= htmlspecialchars($asset['asset_name']); ?></td>

                                <td><?= htmlspecialchars($asset['category']); ?></td>

                                <td><?= htmlspecialchars($asset['brand']); ?></td>

                                <td><?= htmlspecialchars($asset['branch']); ?></td>

                                <td>

                                    <span class="badge badge-<?= $statusColor; ?>">

                                        <?= ucfirst($asset['status']); ?>

                                    </span>

                                </td>

                                <td>

                                    <button
                                        class="btn btn-info btn-sm viewAssetBtn"
                                        data-id="<?= $asset['id']; ?>"
                                        title="View Asset">

                                        <i class="fa fa-eye"></i>

                                    </button>

                                    <button
                                        class="btn btn-success btn-sm assignBtn"
                                        data-id="<?= $asset['id']; ?>"
                                        data-name="<?= htmlspecialchars($asset['asset_name']); ?>"
                                        data-code="<?= $asset['asset_code']; ?>">
                                        <i class="fas fa-user-check"></i>
                                    </button>

                                    <button
                                        class="btn btn-warning btn-sm editAssetBtn"
                                        data-id="<?= $asset['id']; ?>"
                                        title="Edit Asset">

                                        <i class="fa fa-edit"></i>

                                    </button>

                                    <button
                                        class="btn btn-danger btn-sm deleteAssetBtn"
                                        data-id="<?= $asset['id']; ?>"
                                        data-name="<?= htmlspecialchars($asset['asset_name']); ?>"
                                        title="Delete Asset">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>
    $(document).ready(function() {

        $('#assetTable').DataTable({

            responsive: true,

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],

            dom: 'Bfrtip',

            buttons: [

                {
                    extend: 'excel',
                    className: 'btn btn-success btn-sm'
                },

                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm'
                },

                {
                    extend: 'print',
                    className: 'btn btn-info btn-sm'
                }

            ],

            order: [
                [0, 'desc']
            ]

        });

    });
</script>

<!-- View Asset Modal -->

<div class="modal fade"
    id="viewAssetModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    <i class="fa fa-cube"></i>
                    Asset Details
                </h5>

                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-4 text-center">

                        <img id="viewImage"
                            src=""
                            class="img-fluid rounded border mb-3"
                            style="max-height:220px;">

                        <hr>

                        <h6>QR Code</h6>

                        <img id="viewQR"
                            src=""
                            class="img-fluid border p-2"
                            style="width:180px;height:180px;">

                    </div>

                    <div class="col-md-8">

                        <table class="table table-bordered">

                            <tr>
                                <th>Asset Code</th>
                                <td id="assetCode"></td>
                            </tr>

                            <tr>
                                <th>Asset Name</th>
                                <td id="assetName"></td>
                            </tr>

                            <tr>
                                <th>Category</th>
                                <td id="assetCategory"></td>
                            </tr>

                            <tr>
                                <th>Brand</th>
                                <td id="assetBrand"></td>
                            </tr>

                            <tr>
                                <th>Model</th>
                                <td id="assetModel"></td>
                            </tr>

                            <tr>
                                <th>Branch</th>
                                <td id="assetBranch"></td>
                            </tr>

                            <tr>
                                <th>Department</th>
                                <td id="assetDepartment"></td>
                            </tr>

                            <tr>
                                <th>Vendor</th>
                                <td id="assetVendor"></td>
                            </tr>

                            <tr>
                                <th>Purchase Date</th>
                                <td id="assetPurchase"></td>
                            </tr>

                            <tr>
                                <th>Purchase Cost</th>
                                <td id="assetCost"></td>
                            </tr>

                            <tr>
                                <th>Warranty Expiry</th>
                                <td id="assetWarranty"></td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td id="assetStatus"></td>
                            </tr>

                            <tr>
                                <th>Description</th>
                                <td id="assetDescription"></td>
                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Assign Asset Modal -->

<div class="modal fade" id="assignModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">
                    <i class="fas fa-user-check"></i>
                    Assign Asset
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>

            </div>

            <form id="assignForm">

                <div class="modal-body">

                    <input type="hidden" name="action" value="assign_asset">

                    <input type="hidden" name="asset_id" id="assignAssetId">

                    <div class="form-group">

                        <label>Asset Code</label>

                        <input type="text"
                            id="assignAssetCode"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>Asset Name</label>

                        <input type="text"
                            id="assignAssetName"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>Employee</label>

                        <select name="employee_id" class="form-control" required>

                            <option value="">Select Employee</option>

                            <?php foreach ($trainers as $trainer) { ?>

                                <option value="<?= $trainer['id']; ?>">

                                    <?= htmlspecialchars($trainer['StaffCode']); ?>
                                    -
                                    <?= htmlspecialchars($trainer['Name']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Assigned Date</label>

                        <input type="date"
                            name="assigned_date"
                            class="form-control"
                            value="<?= date('Y-m-d'); ?>"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Expected Return</label>

                        <input type="date"
                            name="expected_return"
                            class="form-control">

                    </div>

                    <div class="form-group">

                        <label>Remarks</label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Close
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">
                        <i class="fas fa-user-check"></i>
                        Assign Asset
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Edit Asset Modal -->

<div class="modal fade" id="editAssetModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-warning">

                <h5 class="modal-title">
                    <i class="fa fa-edit"></i>
                    Edit Asset
                </h5>

                <button type="button"
                    class="close"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <input type="hidden"
                    id="editAssetId">

                <div class="form-group">

                    <label>Asset Name</label>

                    <input type="text"
                        id="editAssetName"
                        class="form-control">

                </div>

                <div class="form-group">

                    <label>Purchase Cost</label>

                    <input type="number"
                        id="editPurchaseCost"
                        class="form-control">

                </div>

                <div class="form-row">

                    <div class="form-group col-md-6">

                        <label>Department</label>

                        <input type="text"
                            class="form-control"
                            id="editDepartment">

                    </div>

                    <div class="form-group col-md-6">

                        <label>Purchase Date</label>

                        <input type="date"
                            class="form-control"
                            id="editPurchaseDate">

                    </div>

                </div>

                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        class="form-control"
                        id="editDescription"
                        rows="3"></textarea>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-dismiss="modal">

                    Close

                </button>

                <button
                    type="button"
                    class="btn btn-warning"
                    id="updateAssetBtn">

                    Update Asset

                </button>

            </div>

        </div>

    </div>

</div>
<!-- Delete Asset Modal -->

<div class="modal fade" id="deleteAssetModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title">
                    <i class="fa fa-trash"></i>
                    Delete Asset
                </h5>

                <button type="button"
                    class="close text-white"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <input type="hidden" id="deleteAssetId">

                <p>

                    Are you sure you want to delete

                    <strong id="deleteAssetName"></strong> ?

                </p>

                <div class="alert alert-warning mb-0">

                    This action cannot be undone.

                </div>

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-dismiss="modal">

                    Cancel

                </button>

                <button
                    class="btn btn-danger"
                    id="confirmDeleteAsset">

                    Delete

                </button>

            </div>

        </div>

    </div>

</div>
<?php include "footer.php"; ?>
<script>
    $('.viewAssetBtn').click(function() {

        let id = $(this).data('id');

        $.get('../Controller/get-asset.php?id=' + id, function(data) {

            let asset = JSON.parse(data);

            $('#assetCode').text(asset.asset_code);
            $('#assetName').text(asset.asset_name);
            $('#assetCategory').text(asset.category);
            $('#assetBrand').text(asset.brand);
            $('#assetModel').text(asset.model);
            $('#assetBranch').text(asset.branch);
            $('#assetDepartment').text(asset.department);
            $('#assetVendor').text(asset.vendor);
            $('#assetPurchase').text(asset.purchase_date);
            $('#assetCost').text(asset.purchase_cost);
            $('#assetWarranty').text(asset.warranty_expiry);
            $('#assetStatus').text(asset.status);
            $('#assetDescription').text(asset.description);

            if (asset.image_path != '') {
                document.getElementById("viewImage").src = "/" + asset.image_path;

                $('#viewQR').attr('src', '../../' + asset.qr_code_path);
            }

            $('#viewAssetModal').modal({
                backdrop: true,
                keyboard: true,
                show: true
            });

        });

    });

    $(".assignBtn").click(function() {

        $("#assignAssetId").val($(this).data("id"));

        $("#assignAssetCode").val($(this).data("code"));

        $("#assignAssetName").val($(this).data("name"));

        $("#assignModal").modal("show");

    });

    $("#assignForm").submit(function(e) {

        e.preventDefault();

        $.ajax({

            url: "../Controller/asset-actions.php",

            type: "POST",

            data: $(this).serialize(),

            success: function(response) {

                response = $.trim(response);

                if (response == "SUCCESS") {

                    $("#assignModal").modal("hide");

                    alert("Asset Assigned Successfully.");

                    location.reload();

                } else {

                    alert("Unable to assign asset.");
                }
            },

            error: function() {

                alert("Server Error.");
            }

        });

    });
</script>
<script>
    $(document).on('click', '.editAssetBtn', function() {

        let id = $(this).data('id');

        $.ajax({

            url: '../Controller/get-asset.php',

            type: 'GET',

            data: {
                id: id
            },

            dataType: 'json',

            success: function(asset) {

                $('#editAssetId').val(asset.id);

                $('#editAssetName').val(asset.asset_name);

                $('#editPurchaseCost').val(asset.purchase_cost);

                $('#editDepartment').val(asset.department);

                $('#editPurchaseDate').val(asset.purchase_date);

                $('#editDescription').val(asset.description);

                $('#editAssetModal').modal('show');

            },

            error: function() {

                alert("Unable to load asset.");

            }

        });

    });
    $(document).on('click', '#updateAssetBtn', function() {

        $.ajax({

            url: '../Controller/asset-actions.php',

            type: 'POST',

            data: {

                action: 'update_asset',

                id: $('#editAssetId').val(),

                asset_name: $('#editAssetName').val(),

                purchase_cost: $('#editPurchaseCost').val(),

                department: $('#editDepartment').val(),

                purchase_date: $('#editPurchaseDate').val(),

                description: $('#editDescription').val()

            },

            success: function(response) {

                if ($.trim(response) === "SUCCESS") {

                    alert("Asset Updated Successfully");

                    $('#editAssetModal').modal('hide');

                    location.reload();

                } else {

                    alert(response);

                }

            },

            error: function() {

                alert("AJAX Failed");

            }

        });

    });
    $(document).on('click', '.deleteAssetBtn', function() {

        $('#deleteAssetId').val($(this).data('id'));

        $('#deleteAssetName').text($(this).data('name'));

        $('#deleteAssetModal').modal('show');

    });
    $(document).on('click', '#confirmDeleteAsset', function() {

        $.ajax({

            url: '../Controller/asset-actions.php',

            type: 'POST',

            data: {

                action: 'delete_asset',

                id: $('#deleteAssetId').val()

            },

            success: function(response) {

                console.log(response);

                if ($.trim(response) === "SUCCESS") {

                    $('#deleteAssetModal').modal('hide');

                    location.reload();

                } else {

                    alert(response);

                }

            },

            error: function() {

                alert("Delete Failed");

            }

        });

    });
</script>