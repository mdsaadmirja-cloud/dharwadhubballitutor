<?php

/**
 * ClientOps.php
 * Static DB operations class for the `clients` table.
 * Mirrors the DBtask / DBtrainer / DBvendor convention:
 *  - static methods
 *  - raw mysqli + prepared statements
 *  - returns POJOs / arrays of POJOs
 *
 * ASSUMPTION: adjust the require_once path below to match your actual
 * connection include (the one used in TrainerOps/VendorOps). It must
 * expose a mysqli connection object as $conn.
 */

require_once __DIR__ . "/../model/ClientModel.php";
require_once __DIR__ . "/../../DB Operations/dbconnection.php"; // TODO: confirm actual path, must set $conn (mysqli)

class DBclient
{
    /**
     * Generate the next client_code, e.g. CLT-0001, CLT-0002 ...
     */
    public static function generateClientCode()
    {
        $conn = ConnectDb::getInstance()->getConnection();
        $code = 'CLT-0001';

        $sql = "SELECT client_code FROM clients ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastCode = $row['client_code'];
            if (preg_match('/CLT-(\d+)/', $lastCode, $m)) {
                $next = (int)$m[1] + 1;
                $code = 'CLT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        }
        return $code;
    }

    /**
     * Insert a new client. Returns new client id on success, false on failure.
     */
    public static function addClient(Client $client)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "INSERT INTO clients
(
    client_code,
    branch_id,
    company_name,
    client_name,
    mobile,
    alternate_mobile,
    email,
    website,
    gst_number,
    address,
    city,
    state,
    pincode,
    industry,
    status,
    notes,
    created_at,
    updated_at
)
VALUES
(
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $client_code      = $client->get_client_code();
        $branch_id = $client->get_branch_id();
        $company_name     = $client->get_company_name();
        $client_name      = $client->get_client_name();
        $mobile           = $client->get_mobile();
        $alternate_mobile = $client->get_alternate_mobile();
        $email            = $client->get_email();
        $website          = $client->get_website();
        $gst_number       = $client->get_gst_number();
        $address          = $client->get_address();
        $city             = $client->get_city();
        $state            = $client->get_state();
        $pincode          = $client->get_pincode();
        $industry         = $client->get_industry();
        $status           = $client->get_status();
        $notes            = $client->get_notes();

        $stmt->bind_param(
            "sissssssssssssss",
            $client_code,
            $branch_id,
            $company_name,
            $client_name,
            $mobile,
            $alternate_mobile,
            $email,
            $website,
            $gst_number,
            $address,
            $city,
            $state,
            $pincode,
            $industry,
            $status,
            $notes
        );

        if (!$stmt->execute()) {

            die("Insert Error: " . $stmt->error);
        }

        $newId = $conn->insert_id;

        $stmt->close();

        return $newId;
    }

    /**
     * Update an existing client. Returns true/false.
     */
    public static function updateClient(Client $client)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "UPDATE clients SET
                client_code = ?,
                branch_id = ?,
                company_name = ?,
                client_name = ?,
                mobile = ?,
                alternate_mobile = ?,
                email = ?,
                website = ?,
                gst_number = ?,
                address = ?,
                city = ?,
                state = ?,
                pincode = ?,
                industry = ?,
                status = ?,
                notes = ?,
                updated_at = NOW()
            WHERE id = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $client_code      = $client->get_client_code();
        $branch_id        = $client->get_branch_id();
        $company_name     = $client->get_company_name();
        $client_name      = $client->get_client_name();
        $mobile           = $client->get_mobile();
        $alternate_mobile = $client->get_alternate_mobile();
        $email            = $client->get_email();
        $website          = $client->get_website();
        $gst_number       = $client->get_gst_number();
        $address          = $client->get_address();
        $city             = $client->get_city();
        $state            = $client->get_state();
        $pincode          = $client->get_pincode();
        $industry         = $client->get_industry();
        $status           = $client->get_status();
        $notes            = $client->get_notes();
        $id               = $client->get_id();

        $stmt->bind_param(
            "sissssssssssssssi",
            $client_code,
            $branch_id,
            $company_name,
            $client_name,
            $mobile,
            $alternate_mobile,
            $email,
            $website,
            $gst_number,
            $address,
            $city,
            $state,
            $pincode,
            $industry,
            $status,
            $notes,
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    /**
     * Delete a client by id. child client_projects rows are removed
     * automatically via ON DELETE CASCADE.
     */
    public static function deleteClient($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Fetch a single client by id (includes total_projects count).
     */
    public static function getClientById($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "SELECT c.*,
                       (SELECT COUNT(*) FROM client_projects cp WHERE cp.client_id = c.id) AS total_projects
                FROM clients c
                WHERE c.id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $stmt->close();
            return self::mapRowToClient($row);
        }

        $stmt->close();
        return null;
    }

    /**
     * Fetch clients with optional search/filter + pagination.
     *
     * $filters keys: search, status, page, per_page
     * Returns ['data' => Client[], 'total' => int]
     */
    public static function getAllClients($filters = [])
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $search   = isset($filters['search']) ? trim($filters['search']) : '';
        $status   = isset($filters['status']) ? trim($filters['status']) : '';
        $page     = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $perPage  = isset($filters['per_page']) ? max(1, (int)$filters['per_page']) : 10;
        $offset   = ($page - 1) * $perPage;

        $where  = " WHERE 1=1 ";
        $types  = "";
        $params = [];

        if ($search !== '') {
            $where .= " AND (c.company_name LIKE ? OR c.client_name LIKE ?
                          OR c.mobile LIKE ? OR c.email LIKE ? OR c.client_code LIKE ?) ";
            $like = "%{$search}%";
            for ($i = 0; $i < 5; $i++) {
                $types .= "s";
                $params[] = $like;
            }
        }

        if ($status !== '') {
            $where .= " AND c.status = ? ";
            $types .= "s";
            $params[] = $status;
        }

        // Total Count
        $countSql = "SELECT COUNT(*) AS cnt
                 FROM clients c
                 $where";

        $countStmt = $conn->prepare($countSql);

        if ($types != "") {
            $countStmt->bind_param($types, ...$params);
        }

        $countStmt->execute();
        $total = (int)$countStmt->get_result()->fetch_assoc()['cnt'];
        $countStmt->close();

        // Client List
        $dataSql = "SELECT
                    c.*,
                    b.BranchName,
                    (
                        SELECT COUNT(*)
                        FROM client_projects cp
                        WHERE cp.client_id = c.id
                    ) AS total_projects
                FROM clients c
                LEFT JOIN branch b
                    ON b.id = c.branch_id
                $where
                ORDER BY c.created_at DESC
                LIMIT ? OFFSET ?";

        $dataTypes = $types . "ii";
        $dataParams = $params;
        $dataParams[] = $perPage;
        $dataParams[] = $offset;

        $stmt = $conn->prepare($dataSql);

        if ($types != "") {
            $stmt->bind_param($dataTypes, ...$dataParams);
        } else {
            $stmt->bind_param("ii", $perPage, $offset);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $clients = [];

        while ($row = $result->fetch_assoc()) {
            $clients[] = self::mapRowToClient($row);
        }

        $stmt->close();

        return [
            'data'  => $clients,
            'total' => $total
        ];
    }

    /**
     * Dashboard counters: total/active/inactive clients.
     */
    public static function getClientDashboardCounts()
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $counts = ['total_clients' => 0, 'active_clients' => 0, 'inactive_clients' => 0];

        $result = $conn->query("SELECT status, COUNT(*) AS cnt FROM clients GROUP BY status");
        while ($row = $result->fetch_assoc()) {
            $counts['total_clients'] += (int)$row['cnt'];
            if ($row['status'] === 'Active') {
                $counts['active_clients'] = (int)$row['cnt'];
            } elseif ($row['status'] === 'Inactive') {
                $counts['inactive_clients'] = (int)$row['cnt'];
            }
        }

        return $counts;
    }

    /**
     * Check whether a mobile number is already used by another client.
     * Used for optional duplicate-guard validation.
     */
    public static function mobileExists($mobile, $excludeId = null)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        if ($excludeId) {
            $stmt = $conn->prepare("SELECT id FROM clients WHERE mobile = ? AND id != ? LIMIT 1");
            $stmt->bind_param("si", $mobile, $excludeId);
        } else {
            $stmt = $conn->prepare("SELECT id FROM clients WHERE mobile = ? LIMIT 1");
            $stmt->bind_param("s", $mobile);
        }
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    private static function mapRowToClient($row)
    {
        $client = new Client();

        $client->set_id($row['id']);
        $client->set_client_code($row['client_code']);

        // Branch
        $client->set_branch_id($row['branch_id']);

        if (isset($row['BranchName'])) {
            $client->set_branch_name($row['BranchName']);
        }

        $client->set_company_name($row['company_name']);
        $client->set_client_name($row['client_name']);
        $client->set_mobile($row['mobile']);
        $client->set_alternate_mobile($row['alternate_mobile']);
        $client->set_email($row['email']);
        $client->set_website($row['website']);
        $client->set_gst_number($row['gst_number']);
        $client->set_address($row['address']);
        $client->set_city($row['city']);
        $client->set_state($row['state']);
        $client->set_pincode($row['pincode']);
        $client->set_industry($row['industry']);
        $client->set_status($row['status']);
        $client->set_notes($row['notes']);
        $client->set_created_at($row['created_at']);
        $client->set_updated_at($row['updated_at']);

        if (isset($row['total_projects'])) {
            $client->set_total_projects((int)$row['total_projects']);
        }

        return $client;
    }
}
