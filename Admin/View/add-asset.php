<?php
require "../session.php";

require_once "../DB Operations/AssetOps.php";

include "header.php";

$categories = DBasset::getAllCategories();
$brands     = DBasset::getAllBrands();
$models     = DBasset::getAllModels();
$vendors    = DBasset::getAllVendors();
$branches   = DBasset::getAllBranches();

$flash = $_SESSION['asset_flash'] ?? null;
unset($_SESSION['asset_flash']);
?>

<div class="row">
    <div class="col-12">

        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Add new asset</h6>
            </div>
            <div class="card-body">

                <form action="../Controller/asset-actions.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="action" value="add_asset">

                    <div class="row">
                        <div class="col-lg-8">

                            <h6 class="text-gray-600">Asset details</h6>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Asset name</label>
                                    <input type="text" name="asset_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Select category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Brand</label>
                                    <select name="brand_id" id="brand_id" class="form-control" required>
                                        <option value="">Select brand</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?php echo $brand['id']; ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Model</label>
                                    <select name="model_id" id="model_id" class="form-control">
                                        <option value="">Select model</option>
                                        <?php foreach ($models as $model): ?>
                                            <option value="<?php echo $model['id']; ?>" data-brand="<?php echo $model['brand_id']; ?>" style="display:none;">
                                                <?php echo htmlspecialchars($model['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Branch <span class="text-danger">*</span></label>

                                    <select name="branch" class="form-control" required>

                                        <option value="">Select Branch</option>

                                        <?php foreach ($branches as $branch) { ?>

                                            <option value="<?= htmlspecialchars($branch['BranchName']); ?>">

                                                <?= htmlspecialchars($branch['BranchName']); ?>

                                            </option>

                                        <?php } ?>

                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Department</label>
                                    <input type="text" name="department" class="form-control" placeholder="e.g. Web development">
                                </div>
                            </div>

                            <h6 class="text-gray-600 mt-3">Purchase and warranty</h6>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Vendor</label>
                                    <select name="vendor_id" class="form-control">
                                        <option value="">Select vendor</option>
                                        <?php foreach ($vendors as $vendor): ?>
                                            <option value="<?php echo $vendor['id']; ?>"><?php echo htmlspecialchars($vendor['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Purchase date</label>
                                    <input type="date" name="purchase_date" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Warranty expiry</label>
                                    <input type="date" name="warranty_expiry" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Purchase cost (₹)</label>
                                    <input type="number" step="0.01" name="purchase_cost" class="form-control">
                                </div>
                            </div>

                            <h6 class="text-gray-600 mt-3">Description</h6>
                            <div class="form-group">
                                <textarea name="description" class="form-control" rows="3" placeholder="Condition, accessories included, etc."></textarea>
                            </div>

                            <button type="submit" name="submit_action" value="save" class="btn btn-primary">Save</button>
                            <button type="submit" name="submit_action" value="save_and_assign" class="btn btn-outline-primary">Save &amp; assign</button>
                            <a href="../View/all-assets.php" class="btn btn-outline-secondary">Cancel</a>

                        </div>

                        <div class="col-lg-4">
                            <h6 class="text-gray-600">Images</h6>
                            <div class="form-group">
                                <input type="file" name="asset_image" class="form-control-file" accept="image/*">
                            </div>

                            <h6 class="text-gray-600 mt-3">Attachments</h6>
                            <div class="form-group">
                                <input type="file" name="attachments[]" class="form-control-file" multiple>
                                <small class="text-muted">Invoice, purchase order, etc.</small>
                            </div>

                            <div class="alert alert-info small mt-3">
                                QR code and barcode are generated automatically after saving.
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Cascading brand -> model filter, done client-side (no AJAX, per existing pattern)
    document.getElementById('brand_id').addEventListener('change', function() {
        var selectedBrand = this.value;
        var modelOptions = document.querySelectorAll('#model_id option[data-brand]');
        modelOptions.forEach(function(opt) {
            opt.style.display = (opt.getAttribute('data-brand') === selectedBrand) ? '' : 'none';
        });
        document.getElementById('model_id').value = '';
    });
</script>

<?php include "footer.php"; ?>