<?php
require_once "../session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../DB Operations/VendorOps.php";
require_once "../model/VendorModel.php";
$vendors  = DBVendor::getAllVendors();
$branches = DBVendor::getBranches();

// Map BranchName => id, used because getAllVendors() overwrites branch with BranchName
$branchNameToId = array();
if (is_array($branches)) {
    foreach ($branches as $b) {
        $bid   = is_array($b) ? ($b['id'] ?? '') : ($b->id ?? '');
        $bname = is_array($b) ? ($b['BranchName'] ?? '') : ($b->BranchName ?? '');
        $branchNameToId[$bname] = $bid;
    }
}

$successMsg = $_SESSION['success'] ?? '';
$errorMsg   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Management</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        body {
            background-color: #F4F6F9;
        }

        .page-header {
            background: #fff;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header h4 {
            margin-bottom: 2px;
            font-weight: 600;
        }

        .page-header p {
            margin-bottom: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-add-vendor {
            background-color: #FFC107;
            border-color: #FFC107;
            color: #212529;
            font-weight: 600;
        }

        .btn-add-vendor:hover {
            background-color: #e0a800;
            border-color: #e0a800;
            color: #212529;
        }

        .content-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .toolbar-filters .form-select,
        .toolbar-filters .form-control {
            min-width: 180px;
        }

        .badge-active {
            background-color: #198754;
        }

        .badge-inactive {
            background-color: #dc3545;
        }

        table.dataTable {
            width: 100% !important;
        }

        /* ===============================
         DataTable Export Buttons
        ================================= */

        .dt-buttons .btn,
        .dt-buttons button {
            background: #F6BE01 !important;
            border: 1px solid #F6BE01 !important;
            color: #000 !important;
            font-weight: 600;
            border-radius: 8px;
            transition: all .3s ease;
        }

        .dt-buttons .btn i,
        .dt-buttons button i {
            color: #000 !important;
        }

        .dt-buttons .btn:hover,
        .dt-buttons button:hover {
            background: #FFD54F !important;
            border-color: #FFD54F !important;
            color: #000 !important;
        }

        .dt-buttons .btn:focus,
        .dt-buttons .btn:active,
        .dt-buttons button:focus,
        .dt-buttons button:active {
            background: #F6BE01 !important;
            border-color: #F6BE01 !important;
            color: #000 !important;
            box-shadow: 0 0 0 0.2rem rgba(246, 190, 1, .25);
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center">
    <div>
        <h2>Vendor Management</h2>
        <p class="text-muted mb-0">Manage all vendors branch-wise.</p>
    </div>

    <div>
    <a href="dashboard.php" class="btn btn-outline-secondary mr-2">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <button type="button"
            class="btn btn-warning"
            data-bs-toggle="modal"
            data-bs-target="#addVendorModal">
        <i class="fas fa-plus-circle"></i> Add Vendor
    </button>
</div>
</div>

        <!-- FLASH MESSAGES -->
        <?php if (!empty($successMsg)) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($successMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMsg)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($errorMsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- TOOLBAR -->
        <div class="content-card mb-3">
            <div class="d-flex flex-wrap gap-2 toolbar-filters justify-content-between">
                <div class="d-flex flex-wrap gap-2">
                    <select id="filterBranch" class="form-select">
                        <option value="">All Branches</option>
                        <?php if (is_array($branches)) : foreach ($branches as $b) :
                                $bname = is_array($b) ? ($b['BranchName'] ?? '') : ($b->BranchName ?? '');
                        ?>
                                <option value="<?php echo htmlspecialchars($bname); ?>"><?php echo htmlspecialchars($bname); ?></option>
                        <?php endforeach;
                        endif; ?>
                    </select>

                    <select id="filterStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="content-card">
            <div class="table-responsive">
                <table id="vendorTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vendors as $vendor) :
                            $branchName = $vendor->getBranch();
                            $branchId   = $branchNameToId[$branchName] ?? '';
                            $status     = $vendor->getStatus();
                            $badgeClass = ($status === 'Active') ? 'badge-active' : 'badge-inactive';
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vendor->getId()); ?></td>
                                <td><?php echo htmlspecialchars($vendor->getName()); ?></td>
                                <td><?php echo htmlspecialchars($vendor->getContactPerson()); ?></td>
                                <td><?php echo htmlspecialchars($vendor->getPhone()); ?></td>
                                <td><?php echo htmlspecialchars($vendor->getEmail()); ?></td>
                                <td><?php echo htmlspecialchars($branchName); ?></td>
                                <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                <td><?php echo htmlspecialchars($vendor->getCreatedAt()); ?></td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary btn-edit-vendor"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editVendorModal"
                                        data-id="<?php echo htmlspecialchars($vendor->getId()); ?>"
                                        data-name="<?php echo htmlspecialchars($vendor->getName()); ?>"
                                        data-contact_person="<?php echo htmlspecialchars($vendor->getContactPerson()); ?>"
                                        data-phone="<?php echo htmlspecialchars($vendor->getPhone()); ?>"
                                        data-email="<?php echo htmlspecialchars($vendor->getEmail()); ?>"
                                        data-gst_number="<?php echo htmlspecialchars($vendor->getGstNumber()); ?>"
                                        data-address="<?php echo htmlspecialchars($vendor->getAddress()); ?>"
                                        data-city="<?php echo htmlspecialchars($vendor->getCity()); ?>"
                                        data-state="<?php echo htmlspecialchars($vendor->getState()); ?>"
                                        data-pincode="<?php echo htmlspecialchars($vendor->getPincode()); ?>"
                                        data-branch="<?php echo htmlspecialchars($branchId); ?>"
                                        data-notes="<?php echo htmlspecialchars($vendor->getNotes()); ?>"
                                        data-status="<?php echo htmlspecialchars($status); ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-delete-vendor"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteVendorModal"
                                        data-id="<?php echo htmlspecialchars($vendor->getId()); ?>"
                                        data-name="<?php echo htmlspecialchars($vendor->getName()); ?>"
                                        data-branch="<?php echo htmlspecialchars($branchName); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================
     ADD VENDOR MODAL
============================ -->
    <div class="modal fade" id="addVendorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="../Controller/vendor-actions.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vendor Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Person *</label>
                                <input type="text" name="contact_person" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">GST Number</label>
                                <input type="text" name="gst_number" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Branch *</label>
                                <select name="branch" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    <?php if (is_array($branches)) : foreach ($branches as $b) :
                                            $bid   = is_array($b) ? ($b['id'] ?? '') : ($b->id ?? '');
                                            $bname = is_array($b) ? ($b['BranchName'] ?? '') : ($b->BranchName ?? '');
                                    ?>
                                            <option value="<?php echo htmlspecialchars($bid); ?>"><?php echo htmlspecialchars($bname); ?></option>
                                    <?php endforeach;
                                    endif; ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-add-vendor">Save Vendor</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

   <!-- ============================
     EDIT VENDOR MODAL
============================= -->
<div class="modal fade" id="editVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <form action="../Controller/vendor-actions.php" method="POST">

            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Vendor</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- Vendor Name -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Vendor Name *
                            </label>

                            <input type="text"
                                   name="name"
                                   id="edit_name"
                                   class="form-control"
                                   minlength="3"
                                   maxlength="100"
                                   pattern="[A-Za-z0-9\s.&()-]+"
                                   required>
                        </div>

                        <!-- Contact Person -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Contact Person *
                            </label>

                            <input type="text"
                                   name="contact_person"
                                   id="edit_contact_person"
                                   class="form-control"
                                   minlength="3"
                                   maxlength="50"
                                   pattern="[A-Za-z\s]+"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Phone *
                            </label>

                            <input type="text"
                                   name="phone"
                                   id="edit_phone"
                                   class="form-control"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   id="edit_email"
                                   class="form-control">
                        </div>

                        <!-- GST Number -->
                        <div class="col-md-6">
                            <label class="form-label">
                                GST Number
                            </label>

                            <input type="text"
                                   name="gst_number"
                                   id="edit_gst_number"
                                   class="form-control"
                                   maxlength="15"
                                   pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$">
                        </div>

                        <!-- Branch -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Branch *
                            </label>

                            <select name="branch"
                                    id="edit_branch"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Branch
                                </option>

                                <?php
                                if (is_array($branches)) :
                                    foreach ($branches as $b) :

                                        $bid = is_array($b)
                                            ? ($b['id'] ?? '')
                                            : ($b->id ?? '');

                                        $bname = is_array($b)
                                            ? ($b['BranchName'] ?? '')
                                            : ($b->BranchName ?? '');
                                ?>

                                    <option value="<?php echo htmlspecialchars($bid); ?>">
                                        <?php echo htmlspecialchars($bname); ?>
                                    </option>

                                <?php
                                    endforeach;
                                endif;
                                ?>

                            </select>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label class="form-label">
                                Address
                            </label>

                            <textarea name="address"
                                      id="edit_address"
                                      class="form-control"
                                      rows="2"></textarea>
                        </div>

                        <!-- City -->
                        <div class="col-md-4">
                            <label class="form-label">
                                City
                            </label>

                            <input type="text"
                                   name="city"
                                   id="edit_city"
                                   class="form-control"
                                   pattern="[A-Za-z\s]+">
                        </div>

                        <!-- State -->
                        <div class="col-md-4">
                            <label class="form-label">
                                State
                            </label>

                            <input type="text"
                                   name="state"
                                   id="edit_state"
                                   class="form-control"
                                   pattern="[A-Za-z\s]+">
                        </div>

                        <!-- Pincode -->
                        <div class="col-md-4">
                            <label class="form-label">
                                Pincode
                            </label>

                            <input type="text"
                                   name="pincode"
                                   id="edit_pincode"
                                   class="form-control"
                                   maxlength="6"
                                   pattern="[0-9]{6}">
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    id="edit_status"
                                    class="form-select">

                                <option value="Active">
                                    Active
                                </option>

                                <option value="Inactive">
                                    Inactive
                                </option>

                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <label class="form-label">
                                Notes
                            </label>

                            <textarea name="notes"
                                      id="edit_notes"
                                      class="form-control"
                                      rows="2"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-add-vendor">
                        Save Vendor
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>

    <!-- ============================
     DELETE VENDOR MODAL
============================ -->
    <div class="modal fade" id="deleteVendorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="../Controller/vendor-actions.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this vendor?</p>
                        <p class="mb-1"><strong>Vendor Name:</strong> <span id="delete_name"></span></p>
                        <p class="mb-0"><strong>Branch:</strong> <span id="delete_branch"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(function() {

            var vendorTable = $('#vendorTable').DataTable({
                responsive: true,
                columnDefs: [{
                    targets: 0,
                    visible: false
                }],
                dom: '<"d-flex justify-content-between align-items-center mb-2"Bf>rt<"d-flex justify-content-between align-items-center mt-2"lip>',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        className: 'btn btn-sm btn-outline-success'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Print',
                        className: 'btn btn-sm btn-outline-secondary'
                    }
                ]
            });

            // Branch filter
            $('#filterBranch').on('change', function() {
                vendorTable.column(5).search(this.value).draw();
            });

            // Status filter
            $('#filterStatus').on('change', function() {
                vendorTable.column(6).search(this.value).draw();
            });

            // Populate Edit Modal
            $(document).on('click', '.btn-edit-vendor', function() {
                $('#edit_id').val($(this).data('id'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_contact_person').val($(this).data('contact_person'));
                $('#edit_phone').val($(this).data('phone'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_gst_number').val($(this).data('gst_number'));
                $('#edit_address').val($(this).data('address'));
                $('#edit_city').val($(this).data('city'));
                $('#edit_state').val($(this).data('state'));
                $('#edit_pincode').val($(this).data('pincode'));
                $('#edit_branch').val($(this).data('branch'));
                $('#edit_notes').val($(this).data('notes'));
                $('#edit_status').val($(this).data('status'));
            });

            // Populate Delete Modal
            $(document).on('click', '.btn-delete-vendor', function() {
                $('#delete_id').val($(this).data('id'));
                $('#delete_name').text($(this).data('name'));
                $('#delete_branch').text($(this).data('branch'));
            });

        });
    </script>

</body>

</html>