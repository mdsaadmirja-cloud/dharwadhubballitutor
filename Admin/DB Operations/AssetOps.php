<?php

require_once "../../DB Operations/dbconnection.php";
require_once "../model/AssetModel.php";

class DBasset
{
    private static function conn()
    {
        return ConnectDb::getInstance()->getConnection();
    }

    /* =====================================================
       GENERATE NEXT ASSET CODE
    ===================================================== */

    public static function generateNextAssetCode()
    {
        $conn = self::conn();

        $sql = "SELECT MAX(id) AS last_id FROM assets";
        $result = $conn->query($sql);

        $next = 1;

        if ($result && $row = $result->fetch_assoc()) {
            $next = ((int)$row['last_id']) + 1;
        }

        return "AST-" . str_pad($next, 5, "0", STR_PAD_LEFT);
    }

    /* =====================================================
       CATEGORY
    ===================================================== */

    public static function getAllCategories()
    {
        $conn = self::conn();

        $data = [];

        $sql = "SELECT id,name
                FROM asset_categories
                ORDER BY name ASC";

        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* =====================================================
       BRAND
    ===================================================== */

    public static function getAllBrands()
    {
        $conn = self::conn();

        $data = [];

        $sql = "SELECT id,name
                FROM asset_brands
                ORDER BY name ASC";

        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* =====================================================
       MODEL
    ===================================================== */

    public static function getAllModels()
    {
        $conn = self::conn();

        $data = [];

        $sql = "SELECT id,
                       brand_id,
                       name
                FROM asset_models
                ORDER BY name ASC";

        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* =====================================================
       VENDOR
    ===================================================== */

    public static function getAllVendors()
    {
        $conn = self::conn();

        $data = [];

        $sql = "SELECT id,
                       name
                FROM asset_vendors
                WHERE status='Active'
                ORDER BY name ASC";

        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /* =====================================================
       BRANCH
    ===================================================== */

    public static function getAllBranches()
    {
        $conn = self::conn();

        $data = [];

        $sql = "SELECT id,
                       BranchName
                FROM branch
                WHERE Status='Active'
                ORDER BY BranchName ASC";

        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
    /* =====================================================
       INSERT ASSET
    ===================================================== */

    public function insertAsset(AssetModel $asset)
    {
        $conn = self::conn();

        try {

            /* ===========================
               IDs
            =========================== */

            $category_id = (int)$asset->get_category_id();
            $brand_id    = (int)$asset->get_brand_id();

            $model_id = !empty($asset->get_model_id())
                ? (int)$asset->get_model_id()
                : null;

            $vendor_id = !empty($asset->get_vendor_id())
                ? (int)$asset->get_vendor_id()
                : null;

            /* ===========================
               Get Category Name
            =========================== */

            $category = "";

            $stmt = $conn->prepare("SELECT name FROM asset_categories WHERE id=?");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $stmt->bind_result($category);
            $stmt->fetch();
            $stmt->close();

            /* ===========================
               Get Brand Name
            =========================== */

            $brand = "";

            $stmt = $conn->prepare("SELECT name FROM asset_brands WHERE id=?");
            $stmt->bind_param("i", $brand_id);
            $stmt->execute();
            $stmt->bind_result($brand);
            $stmt->fetch();
            $stmt->close();

            /* ===========================
               Get Model Name
            =========================== */

            $model = "";

            if ($model_id) {

                $stmt = $conn->prepare("SELECT name FROM asset_models WHERE id=?");
                $stmt->bind_param("i", $model_id);
                $stmt->execute();
                $stmt->bind_result($model);
                $stmt->fetch();
                $stmt->close();
            }

            /* ===========================
               Get Vendor Name
            =========================== */

            $vendor = "";

            if ($vendor_id) {

                $stmt = $conn->prepare("SELECT name FROM asset_vendors WHERE id=?");
                $stmt->bind_param("i", $vendor_id);
                $stmt->execute();
                $stmt->bind_result($vendor);
                $stmt->fetch();
                $stmt->close();
            }

            /* ===========================
               Asset Details
            =========================== */

            $asset_code = $asset->get_asset_code();
            $asset_name = $asset->get_asset_name();

            $branch = $asset->get_branch();
            $department = $asset->get_department();

            $purchase_date = $asset->get_purchase_date();
            $purchase_cost = $asset->get_purchase_cost();

            $warranty_expiry = $asset->get_warranty_expiry();

            $description = $asset->get_description();

            $image_path = $asset->get_image_path();

            $created_by = (int)$asset->get_created_by();

            /* ===========================
               INSERT
            =========================== */

            $stmt = $conn->prepare("
                INSERT INTO assets
                (
                    asset_code,
                    asset_name,

                    category,
                    category_id,

                    brand,
                    brand_id,

                    model,
                    model_id,

                    branch,
                    department,

                    vendor,
                    vendor_id,

                    purchase_date,
                    purchase_cost,
                    warranty_expiry,

                    description,
                    image_path,

                    status,
                    created_by
                )
                VALUES
                (
                    ?,?,
                    ?,?,
                    ?,?,
                    ?,?,
                    ?,?,
                    ?,?,
                    ?,?,?,
                    ?,?,
                    'available',
                    ?
                )
            ");

            $stmt->bind_param(
                "sssisisisssisdsssi",

                $asset_code,
                $asset_name,

                $category,
                $category_id,

                $brand,
                $brand_id,

                $model,
                $model_id,

                $branch,
                $department,

                $vendor,
                $vendor_id,

                $purchase_date,
                $purchase_cost,
                $warranty_expiry,

                $description,
                $image_path,

                $created_by
            );

            if ($stmt->execute()) {

                return $conn->insert_id;
            }

            if ($conn->errno == 1062) {
                return "DUPLICATE";
            }

            return "ERROR";
        } catch (Exception $e) {

            return "ERROR";
        }
    }
    /* =====================================================
       GET ALL ASSETS
    ===================================================== */

    public static function getAllAssets()
    {
        $conn = self::conn();

        $assets = [];

        $sql = "SELECT *
                FROM assets
                ORDER BY id DESC";

        $result = $conn->query($sql);

        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $assets[] = $row;
            }
        }

        return $assets;
    }


    /* =====================================================
       GET ASSET BY ID
    ===================================================== */

    public static function getAssetById($id)
    {
        $conn = self::conn();

        $stmt = $conn->prepare("
            SELECT *
            FROM assets
            WHERE id=?
            LIMIT 1
        ");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            return $result->fetch_assoc();
        }

        return null;
    }
    /* =====================================================
       DELETE ASSET
    ===================================================== */

    public function deleteAsset($id)
    {
        $conn = self::conn();

        /* Get image path first */

        $stmt = $conn->prepare("SELECT image_path FROM assets WHERE id=?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($image);

        $stmt->fetch();

        $stmt->close();

        /* Delete image if exists */

        if (!empty($image)) {

            $file = "../../" . $image;

            if (file_exists($file)) {

                unlink($file);
            }
        }

        /* Delete asset */

        $stmt = $conn->prepare("DELETE FROM assets WHERE id=?");

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
    public function updateAsset(AssetModel $asset)
    {
        $conn = self::conn();

        $asset_name    = $asset->get_asset_name();
        $purchase_cost = $asset->get_purchase_cost();
        $department    = $asset->get_department();
        $purchase_date = $asset->get_purchase_date();
        $description   = $asset->get_description();
        $id            = $asset->get_id();

        $stmt = $conn->prepare("
        UPDATE assets
        SET
            asset_name = ?,
            purchase_cost = ?,
            department = ?,
            purchase_date = ?,
            description = ?
        WHERE id = ?
    ");

        if (!$stmt) {
            die($conn->error);
        }

        $stmt->bind_param(
            "sdsssi",
            $asset_name,
            $purchase_cost,
            $department,
            $purchase_date,
            $description,
            $id
        );

        return $stmt->execute();
    }

    public function saveQrPath($id, $path)
    {
        $conn = self::conn();

        $stmt = $conn->prepare("
        UPDATE assets
        SET qr_code_path = ?
        WHERE id = ?
    ");

        $stmt->bind_param("si", $path, $id);

        return $stmt->execute();
    }
}
