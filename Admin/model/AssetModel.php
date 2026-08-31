<?php

class AssetModel
{
    private $id;
    private $asset_code;
    private $asset_name;

    private $category_id;
    private $brand_id;
    private $model_id;
    private $vendor_id;

    private $branch;
    private $department;

    private $purchase_date;
    private $purchase_cost;
    private $warranty_expiry;

    private $description;
    private $image_path;

    private $status;
    private $created_by;

    /* ---------- ID ---------- */

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    /* ---------- Asset Code ---------- */

    public function get_asset_code()
    {
        return $this->asset_code;
    }

    public function set_asset_code($asset_code)
    {
        $this->asset_code = $asset_code;
    }

    /* ---------- Asset Name ---------- */

    public function get_asset_name()
    {
        return $this->asset_name;
    }

    public function set_asset_name($asset_name)
    {
        $this->asset_name = $asset_name;
    }

    /* ---------- Category ---------- */

    public function get_category_id()
    {
        return $this->category_id;
    }

    public function set_category_id($category_id)
    {
        $this->category_id = $category_id;
    }

    /* ---------- Brand ---------- */

    public function get_brand_id()
    {
        return $this->brand_id;
    }

    public function set_brand_id($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /* ---------- Model ---------- */

    public function get_model_id()
    {
        return $this->model_id;
    }

    public function set_model_id($model_id)
    {
        $this->model_id = $model_id;
    }

    /* ---------- Vendor ---------- */

    public function get_vendor_id()
    {
        return $this->vendor_id;
    }

    public function set_vendor_id($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }

    /* ---------- Branch ---------- */

    public function get_branch()
    {
        return $this->branch;
    }

    public function set_branch($branch)
    {
        $this->branch = $branch;
    }

    /* ---------- Department ---------- */

    public function get_department()
    {
        return $this->department;
    }

    public function set_department($department)
    {
        $this->department = $department;
    }

    /* ---------- Purchase Date ---------- */

    public function get_purchase_date()
    {
        return $this->purchase_date;
    }

    public function set_purchase_date($purchase_date)
    {
        $this->purchase_date = $purchase_date;
    }

    /* ---------- Purchase Cost ---------- */

    public function get_purchase_cost()
    {
        return $this->purchase_cost;
    }

    public function set_purchase_cost($purchase_cost)
    {
        $this->purchase_cost = $purchase_cost;
    }

    /* ---------- Warranty ---------- */

    public function get_warranty_expiry()
    {
        return $this->warranty_expiry;
    }

    public function set_warranty_expiry($warranty_expiry)
    {
        $this->warranty_expiry = $warranty_expiry;
    }

    /* ---------- Description ---------- */

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($description)
    {
        $this->description = $description;
    }

    /* ---------- Image ---------- */

    public function get_image_path()
    {
        return $this->image_path;
    }

    public function set_image_path($image_path)
    {
        $this->image_path = $image_path;
    }

    /* ---------- Status ---------- */

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($status)
    {
        $this->status = $status;
    }

    /* ---------- Created By ---------- */

    public function get_created_by()
    {
        return $this->created_by;
    }

    public function set_created_by($created_by)
    {
        $this->created_by = $created_by;
    }
}