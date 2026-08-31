<?php

require_once "../session.php";
require_once "../DB Operations/AssetOps.php";
require_once "../model/AssetModel.php";
require_once "../Libraries/phpqrcode/phpqrcode/qrlib.php";

if (isset($_POST['action']) && $_POST['action'] == 'delete_asset') {

    $db = new DBasset();

    if ($db->deleteAsset((int)$_POST['id'])) {
        echo "SUCCESS";
    } else {
        echo "FAILED";
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'update_asset') {

    $asset = new AssetModel();

    $asset->set_id($_POST['id']);
    $asset->set_asset_name($_POST['asset_name']);
    $asset->set_purchase_cost($_POST['purchase_cost']);
    $asset->set_department($_POST['department']);
    $asset->set_purchase_date($_POST['purchase_date']);
    $asset->set_description($_POST['description']);

    $db = new DBasset();

    if ($db->updateAsset($asset)) {

        echo "SUCCESS";
    } else {

        echo "FAILED";
    }

    exit;
}
require_once "../DB Operations/AssetAssignmentOps.php";
require_once "../model/AssetAssignmentModel.php";

if (isset($_POST['action']) && $_POST['action'] == 'assign_asset') {

    $assignment = new AssetAssignmentModel();

    $assignment->set_asset_id($_POST['asset_id']);
    $assignment->set_employee_id($_POST['employee_id']);
    $assignment->set_assigned_date($_POST['assigned_date']);
    $assignment->set_expected_return($_POST['expected_return']);
    $assignment->set_remarks($_POST['remarks']);
    $assignment->set_assigned_by($_SESSION['user_id']);

    $db = new AssetAssignmentOps();

    if ($db->assignAsset($assignment)) {
        echo "SUCCESS";
    } else {
        echo "FAILED";
    }

    exit;
}
if (!isset($_SESSION['login_user'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../View/add-asset.php");
    exit();
}

if (!isset($_POST['action']) || $_POST['action'] != "add_asset") {
    header("Location: ../View/add-asset.php");
    exit();
}

$asset = new AssetModel();

/* ==========================================
   Asset Details
========================================== */

$asset->set_asset_code(DBasset::generateNextAssetCode());

$asset->set_asset_name(trim($_POST['asset_name']));

$asset->set_category_id($_POST['category_id']);

$asset->set_brand_id($_POST['brand_id']);

$asset->set_model_id(
    !empty($_POST['model_id']) ? $_POST['model_id'] : null
);

$asset->set_branch($_POST['branch']);

$asset->set_department($_POST['department']);

$asset->set_vendor_id(
    !empty($_POST['vendor_id']) ? $_POST['vendor_id'] : null
);

$asset->set_purchase_date(
    !empty($_POST['purchase_date']) ? $_POST['purchase_date'] : null
);

$asset->set_purchase_cost(
    !empty($_POST['purchase_cost']) ? $_POST['purchase_cost'] : null
);

$asset->set_warranty_expiry(
    !empty($_POST['warranty_expiry']) ? $_POST['warranty_expiry'] : null
);

$asset->set_description(trim($_POST['description']));

$asset->set_created_by($_SESSION['user_id']);

/* ==========================================
   Upload Image
========================================== */

if (
    isset($_FILES['asset_image']) &&
    $_FILES['asset_image']['error'] == 0
) {

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    $extension = strtolower(
        pathinfo($_FILES['asset_image']['name'], PATHINFO_EXTENSION)
    );

    if (in_array($extension, $allowed)) {

        $folder = "../../uploads/assets/";

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = uniqid("asset_") . "." . $extension;

        if (
            move_uploaded_file(
                $_FILES['asset_image']['tmp_name'],
                $folder . $filename
            )
        ) {
            $asset->set_image_path("uploads/assets/" . $filename);
        }
    }
}

/* ==========================================
   Save Asset
========================================== */

$db = new DBasset();

$result = $db->insertAsset($asset);

if (is_numeric($result)) {

    // Asset ID
    $assetId = $result;

    // QR Content
    $qrContent = "Asset ID : " . $assetId .
        "\nAsset Code : " . $asset->get_asset_code() .
        "\nAsset Name : " . $asset->get_asset_name() .
        "\nBranch : " . $asset->get_branch();

    // QR File Name
    $qrFileName = "asset_" . $assetId . ".png";

    // Relative path stored in DB
    $qrPath = "uploads/qr/" . $qrFileName;

    // Physical path
    $qrFullPath = "../../uploads/qr/" . $qrFileName;

    // Generate QR
    QRcode::png($qrContent, $qrFullPath, QR_ECLEVEL_H, 6);

    // Save QR path in DB
    $db->saveQrPath($assetId, $qrPath);
}

if ($result == "DUPLICATE") {

    $_SESSION['asset_flash'] = [
        "type" => "danger",
        "message" => "Asset Code already exists."
    ];

    header("Location: ../View/add-asset.php");
    exit();
}

if ($result == "ERROR") {

    $_SESSION['asset_flash'] = [
        "type" => "danger",
        "message" => "Unable to save asset."
    ];

    header("Location: ../View/add-asset.php");
    exit();
}

/* ==========================================
   Success
========================================== */

$_SESSION['asset_flash'] = [
    "type" => "success",
    "message" => "Asset Added Successfully."
];

if (
    isset($_POST['submit_action']) &&
    $_POST['submit_action'] == "save_and_assign"
) {

    header(
        "Location: ../View/assign-asset.php?asset_id=" .
            $result
    );

    exit();
}

header("Location: ../View/all-assets.php");
exit();
