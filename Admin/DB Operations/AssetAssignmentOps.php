<?php

require_once "../../DB Operations/dbconnection.php";
require_once "../model/AssetAssignmentModel.php";

class AssetAssignmentOps
{
    private static function conn()
    {
        return ConnectDb::getInstance()->getConnection();
    }

    /* =====================================================
       ACTIVE TRAINERS
    ===================================================== */

    public static function getAllActiveTrainers()
    {
        $conn = self::conn();

        $data = [];

        $sql = "
            SELECT
                id,
                StaffCode,
                Name,
                BranchId,
                Department
            FROM trainers
            WHERE Status='Active'
            ORDER BY Name ASC
        ";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /* =====================================================
       ASSIGN ASSET
    ===================================================== */

    public function assignAsset(AssetAssignmentModel $assignment)
    {
        $conn = self::conn();

        $conn->begin_transaction();

        try {

            /* Get Trainer Details */

            $stmt = $conn->prepare("
                SELECT
                    Name,
                    BranchId,
                    Department
                FROM trainers
                WHERE id=?
            ");

            $employeeId = $assignment->get_employee_id();

            $stmt->bind_param("i", $employeeId);

            $stmt->execute();

            $trainer = $stmt->get_result()->fetch_assoc();

            if (!$trainer) {
                throw new Exception("Trainer not found");
            }

            /* Get Asset Code */

            $stmt = $conn->prepare("
                SELECT asset_code
                FROM assets
                WHERE id=?
            ");

            $assetId = $assignment->get_asset_id();

            $stmt->bind_param("i", $assetId);

            $stmt->execute();

            $asset = $stmt->get_result()->fetch_assoc();

            if (!$asset) {
                throw new Exception("Asset not found");
            }

            /* Insert Assignment */

            $stmt = $conn->prepare("
                INSERT INTO asset_assignments
                (
                    asset_id,
                    employee_id,
                    asset_code,
                    employee_name,
                    branch,
                    department,
                    assigned_date,
                    expected_return,
                    remarks,
                    assigned_by,
                    status
                )
                VALUES
                (?,?,?,?,?,?,?,?,?,?,'Assigned')
            ");

            $stmt->bind_param(
                "iisssssssi",
                $assetId,
                $employeeId,
                $asset['asset_code'],
                $trainer['Name'],
                $trainer['BranchId'],
                $trainer['Department'],
                $assignment->get_assigned_date(),
                $assignment->get_expected_return(),
                $assignment->get_remarks(),
                $assignment->get_assigned_by()
            );

            $stmt->execute();

            /* Update Asset Status */

            $stmt = $conn->prepare("
                UPDATE assets
                SET status='Assigned'
                WHERE id=?
            ");

            $stmt->bind_param("i", $assetId);

            $stmt->execute();

            $conn->commit();

            return true;
        } catch (Exception $e) {

            $conn->rollback();

            return false;
        }
    }
}