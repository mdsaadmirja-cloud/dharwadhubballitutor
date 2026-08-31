<?php

require_once __DIR__ . "/../model/VendorModel.php";
require_once "../../DB Operations/dbconnection.php";

class DBVendor
{

    /* ===========================
       ADD VENDOR
    ============================ */
    public static function addVendor(VendorModel $vendor)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        try {

            $name             = $conn->real_escape_string($vendor->getName());
            $contact_person   = $conn->real_escape_string($vendor->getContactPerson());
            $phone            = $conn->real_escape_string($vendor->getPhone());
            $email            = $conn->real_escape_string($vendor->getEmail());
            $gst_number       = $conn->real_escape_string($vendor->getGstNumber());
            $address          = $conn->real_escape_string($vendor->getAddress());
            $city             = $conn->real_escape_string($vendor->getCity());
            $state            = $conn->real_escape_string($vendor->getState());
            $pincode          = $conn->real_escape_string($vendor->getPincode());
            $branch           = (int)$vendor->getBranch();
            $notes            = $conn->real_escape_string($vendor->getNotes());
            $status           = $conn->real_escape_string($vendor->getStatus());
            $created_by       = (int)$vendor->getCreatedBy();

            $sql = "INSERT INTO asset_vendors
                    (
                        name,
                        contact_person,
                        phone,
                        email,
                        gst_number,
                        address,
                        city,
                        state,
                        pincode,
                        branch,
                        notes,
                        status,
                        created_by
                    )
                    VALUES
                    (
                        '$name',
                        '$contact_person',
                        '$phone',
                        '$email',
                        '$gst_number',
                        '$address',
                        '$city',
                        '$state',
                        '$pincode',
                        $branch,
                        '$notes',
                        '$status',
                        $created_by
                    )";

            return $conn->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }


    /* ===========================
       GET ALL VENDORS
    ============================ */
    public static function getAllVendors()
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $vendors = array();

        $sql = "SELECT
            v.*,
            b.BranchName
        FROM asset_vendors v
        LEFT JOIN branch b
            ON b.id = v.branch
        ORDER BY v.id DESC";

        $result = $conn->query($sql);
        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $vendor = new VendorModel();

                $vendor->setId($row['id']);
                $vendor->setName($row['name']);
                $vendor->setContactPerson($row['contact_person']);
                $vendor->setPhone($row['phone']);
                $vendor->setEmail($row['email']);
                $vendor->setGstNumber($row['gst_number']);
                $vendor->setAddress($row['address']);
                $vendor->setCity($row['city']);
                $vendor->setState($row['state']);
                $vendor->setPincode($row['pincode']);
                $vendor->setBranch($row['BranchName']);
                $vendor->setNotes($row['notes']);
                $vendor->setStatus($row['status']);
                $vendor->setCreatedBy($row['created_by']);
                $vendor->setCreatedAt($row['created_at']);
                $vendor->setUpdatedAt($row['updated_at']);

                $vendors[] = $vendor;
            }
        }

        return $vendors;
    }


    /* ===========================
       GET SINGLE VENDOR
    ============================ */
    public static function getVendorById($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $id = (int)$id;

        $sql = "SELECT * FROM asset_vendors WHERE id=$id";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $vendor = new VendorModel();

            $vendor->setId($row['id']);
            $vendor->setName($row['name']);
            $vendor->setContactPerson($row['contact_person']);
            $vendor->setPhone($row['phone']);
            $vendor->setEmail($row['email']);
            $vendor->setGstNumber($row['gst_number']);
            $vendor->setAddress($row['address']);
            $vendor->setCity($row['city']);
            $vendor->setState($row['state']);
            $vendor->setPincode($row['pincode']);
            $vendor->setBranch($row['branch']);
            $vendor->setNotes($row['notes']);
            $vendor->setStatus($row['status']);
            $vendor->setCreatedBy($row['created_by']);
            $vendor->setCreatedAt($row['created_at']);
            $vendor->setUpdatedAt($row['updated_at']);

            return $vendor;
        }

        return null;
    }

    /* ===========================
   UPDATE VENDOR
    =========================== */
    public static function updateVendor(VendorModel $vendor)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        try {

            $id               = (int)$vendor->getId();
            $name             = $conn->real_escape_string($vendor->getName());
            $contact_person   = $conn->real_escape_string($vendor->getContactPerson());
            $phone            = $conn->real_escape_string($vendor->getPhone());
            $email            = $conn->real_escape_string($vendor->getEmail());
            $gst_number       = $conn->real_escape_string($vendor->getGstNumber());
            $address          = $conn->real_escape_string($vendor->getAddress());
            $city             = $conn->real_escape_string($vendor->getCity());
            $state            = $conn->real_escape_string($vendor->getState());
            $pincode          = $conn->real_escape_string($vendor->getPincode());
            $branch           = (int)$vendor->getBranch();
            $notes            = $conn->real_escape_string($vendor->getNotes());
            $status           = $conn->real_escape_string($vendor->getStatus());

            $sql = "UPDATE asset_vendors SET
                    name='$name',
                    contact_person='$contact_person',
                    phone='$phone',
                    email='$email',
                    gst_number='$gst_number',
                    address='$address',
                    city='$city',
                    state='$state',
                    pincode='$pincode',
                    branch=$branch,
                    notes='$notes',
                    status='$status',
                    updated_at=NOW()
                WHERE id=$id";

            return $conn->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    /* ===========================
       DELETE VENDOR
    ============================ */
    public static function deleteVendor($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $id = (int)$id;

        return $conn->query("DELETE FROM asset_vendors WHERE id=$id");
    }

    /* ===========================
   GET ALL BRANCHES
    =========================== */
    public static function getBranches()
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $branches = [];

        $sql = "SELECT id, BranchName
            FROM branch
            ORDER BY BranchName ASC";

        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $branches[] = $row;
            }
        }

        return $branches;
    }
}
