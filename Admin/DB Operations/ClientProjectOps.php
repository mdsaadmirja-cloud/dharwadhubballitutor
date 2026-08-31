<?php

/**
 * ClientProjectOps.php
 * Static DB operations class for the `client_projects` table.
 */

require_once __DIR__ . "/../model/ClientProjectModel.php";
require_once __DIR__ . "/../../DB Operations/dbconnection.php"; // TODO: confirm actual path, must set $conn (mysqli)

class DBclientproject
{
    /**
     * Insert a new project. Returns new id on success, false on failure.
     * pending_amount is always server-calculated: budget - advance_amount.
     */
    public static function addProject(
        ClientProject $project,
        $paymentMode,
        $paymentDate,
        $transactionNo,
        $remarks
    ) {
        $conn = ConnectDb::getInstance()->getConnection();

        $pending = $project->get_budget() - $project->get_advance_amount();

        $sql = "INSERT INTO client_projects
            (client_id, project_name, project_type, technology, description,
             start_date, expected_delivery, completed_date, budget,
             advance_amount, pending_amount, priority, project_status,
             progress, remarks, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        // Store all values in variables
        $clientId       = $project->get_client_id();
        $projectName    = $project->get_project_name();
        $projectType    = $project->get_project_type();
        $technology     = $project->get_technology();
        $description    = $project->get_description();
        $startDate      = $project->get_start_date();
        $expected       = $project->get_expected_delivery();
        $completed      = $project->get_completed_date();
        $budget         = $project->get_budget();
        $advance        = $project->get_advance_amount();
        $priority       = $project->get_priority();
        $status         = $project->get_project_status();
        $progress       = method_exists($project, 'get_progress') ? $project->get_progress() : 0;
        $remarks        = method_exists($project, 'get_remarks') ? $project->get_remarks() : "";

        $stmt->bind_param(
            "isssssssdddssis",
            $clientId,
            $projectName,
            $projectType,
            $technology,
            $description,
            $startDate,
            $expected,
            $completed,
            $budget,
            $advance,
            $pending,
            $priority,
            $status,
            $progress,
            $remarks
        );

        if ($stmt->execute()) {

            $newId = $conn->insert_id;

            $stmt->close();

            // =====================================
            // Insert Initial Advance Payment
            // =====================================

            if ($advance > 0) {

                $paymentSql = "INSERT INTO project_payments
        (
            project_id,
            payment_date,
            amount,
            payment_mode,
            payment_type,
            transaction_no,
            remarks,
            created_at
        )
        VALUES
        (
            ?, ?, ?, ?, 'Advance', ?, ?, NOW()
        )";

                $paymentStmt = $conn->prepare($paymentSql);

                if ($paymentStmt) {

                    $paymentStmt->bind_param(
                        "isdsss",
                        $newId,
                        $paymentDate,
                        $advance,
                        $paymentMode,
                        $transactionNo,
                        $remarks
                    );

                    $paymentStmt->execute();
                    $paymentStmt->close();
                }
            }

            return $newId;
        }

        $stmt->close();

        return false;
    }

    /**
     * Update an existing project. pending_amount recalculated server-side.
     */
    public static function updateProject(ClientProject $project)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $pending = $project->get_budget() - $project->get_advance_amount();

        $sql = "UPDATE client_projects SET
                client_id=?,
                project_name=?,
                project_type=?,
                technology=?,
                description=?,
                start_date=?,
                expected_delivery=?,
                completed_date=?,
                budget=?,
                advance_amount=?,
                pending_amount=?,
                priority=?,
                project_status=?,
                progress=?,
                remarks=?
            WHERE id=?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        // Store everything in variables
        $id          = $project->get_id();
        $clientId    = $project->get_client_id();
        $projectName = $project->get_project_name();
        $projectType = $project->get_project_type();
        $technology  = $project->get_technology();
        $description = $project->get_description();
        $startDate   = $project->get_start_date();
        $expected    = $project->get_expected_delivery();
        $completed   = $project->get_completed_date();
        $budget      = $project->get_budget();
        $advance     = $project->get_advance_amount();
        $priority    = $project->get_priority();
        $status      = $project->get_project_status();
        $progress    = $project->get_progress();
        $remarks     = $project->get_remarks();

        $stmt->bind_param(
            "isssssssdddssisi",
            $clientId,
            $projectName,
            $projectType,
            $technology,
            $description,
            $startDate,
            $expected,
            $completed,
            $budget,
            $advance,
            $pending,
            $priority,
            $status,
            $progress,
            $remarks,
            $id
        );

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function deleteProject($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $stmt = $conn->prepare(
            "DELETE FROM client_projects WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }
    public static function getProjectById($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "SELECT
            cp.*,
            c.company_name,
            c.client_name,
            c.branch_id,
            b.BranchName

        FROM client_projects cp

        INNER JOIN clients c
            ON c.id = cp.client_id

        LEFT JOIN branch b
            ON b.id = c.branch_id

        WHERE cp.id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $stmt->close();
            return self::mapRowToProject($row);
        }

        $stmt->close();
        return null;
    }

    /**
     * All projects belonging to one client (used on Client Profile page).
     */
    public static function getProjectsByClientId($clientId)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $stmt = $conn->prepare(
            "SELECT cp.*, c.company_name, c.client_name
             FROM client_projects cp
             INNER JOIN clients c ON c.id = cp.client_id
             WHERE cp.client_id = ?
             ORDER BY cp.created_at DESC"
        );
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = self::mapRowToProject($row);
        }
        $stmt->close();
        return $projects;
    }

    /**
     * Global project list with search/filter + pagination
     * (Company, Client Name, Mobile, Email are joined from clients;
     *  Technology, Project Status, Date Range are native columns).
     *
     * $filters keys: search, technology, project_status, date_from, date_to, page, per_page
     * Returns ['data' => ClientProject[], 'total' => int]
     */
    public static function getAllProjects($filters = [])
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $search    = isset($filters['search']) ? trim($filters['search']) : '';
        $tech      = isset($filters['technology']) ? trim($filters['technology']) : '';
        $status    = isset($filters['project_status']) ? trim($filters['project_status']) : '';
        $dateFrom  = isset($filters['date_from']) ? trim($filters['date_from']) : '';
        $dateTo    = isset($filters['date_to']) ? trim($filters['date_to']) : '';
        $page      = isset($filters['page']) ? max(1, (int)$filters['page']) : 1;
        $perPage   = isset($filters['per_page']) ? max(1, (int)$filters['per_page']) : 10;
        $offset    = ($page - 1) * $perPage;

        $where  = " WHERE 1=1 ";
        $types  = "";
        $params = [];

        if ($search !== '') {
            $where .= " AND (c.company_name LIKE ? OR c.client_name LIKE ?
                              OR c.mobile LIKE ? OR c.email LIKE ? OR cp.project_name LIKE ?) ";
            $like = "%{$search}%";
            for ($i = 0; $i < 5; $i++) {
                $types .= "s";
                $params[] = $like;
            }
        }

        if ($tech !== '') {
            $where .= " AND cp.technology LIKE ? ";
            $types .= "s";
            $params[] = "%{$tech}%";
        }

        if ($status !== '') {
            $where .= " AND cp.project_status = ? ";
            $types .= "s";
            $params[] = $status;
        }

        if ($dateFrom !== '' && $dateTo !== '') {
            $where .= " AND cp.start_date BETWEEN ? AND ? ";
            $types .= "ss";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }

        $baseFrom = " FROM client_projects cp
              INNER JOIN clients c ON c.id = cp.client_id
              LEFT JOIN branch b ON b.id = c.branch_id
              " . $where;
        $countStmt = $conn->prepare("SELECT COUNT(*) AS cnt" . $baseFrom);
        if ($types !== '') {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $total = (int)$countStmt->get_result()->fetch_assoc()['cnt'];
        $countStmt->close();

        $dataSql = "SELECT
                cp.*,
                c.company_name,
                c.client_name,
                c.branch_id,
                b.BranchName
            " . $baseFrom . "
            ORDER BY cp.created_at DESC
            LIMIT ? OFFSET ?";

        $dataTypes  = $types . "ii";
        $dataParams = $params;
        $dataParams[] = $perPage;
        $dataParams[] = $offset;

        $stmt = $conn->prepare($dataSql);
        $stmt->bind_param($dataTypes, ...$dataParams);
        $stmt->execute();
        $result = $stmt->get_result();

        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = self::mapRowToProject($row);
        }
        $stmt->close();

        return ['data' => $projects, 'total' => $total];
    }

    /**
     * Dashboard project counters + total revenue (sum of budgets on
     * non-cancelled projects).
     */
    public static function getProjectDashboardCounts()
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $counts = [
            'total_projects'     => 0,
            'running_projects'   => 0,
            'completed_projects' => 0,
            'pending_projects'   => 0,
            'total_revenue'      => 0.00,
        ];

        $result = $conn->query("
        SELECT project_status, COUNT(*) AS cnt
        FROM client_projects
        GROUP BY project_status
    ");

        while ($row = $result->fetch_assoc()) {

            $counts['total_projects'] += (int)$row['cnt'];

            switch ($row['project_status']) {

                case 'Development':
                case 'Testing':
                case 'Maintenance':
                    $counts['running_projects'] += (int)$row['cnt'];
                    break;

                case 'Completed':
                    $counts['completed_projects'] += (int)$row['cnt'];
                    break;

                case 'Planning':
                    $counts['pending_projects'] += (int)$row['cnt'];
                    break;
            }
        }

        $revResult = $conn->query("
        SELECT COALESCE(SUM(budget),0) AS total_revenue
        FROM client_projects
        WHERE project_status != 'Cancelled'
    ");

        $counts['total_revenue'] =
            (float)$revResult->fetch_assoc()['total_revenue'];

        return $counts;
    }

    private static function mapRowToProject($row)
    {
        $p = new ClientProject();

        $p->set_id($row['id']);
        $p->set_client_id($row['client_id']);
        if (isset($row['branch_id'])) {
            $p->set_branch_id($row['branch_id']);
        }

        if (isset($row['BranchName'])) {
            $p->set_branch_name($row['BranchName']);
        }
        $p->set_project_name($row['project_name']);
        $p->set_project_type($row['project_type']);
        $p->set_technology($row['technology']);
        $p->set_description($row['description']);
        $p->set_start_date($row['start_date']);
        $p->set_expected_delivery($row['expected_delivery']);
        $p->set_completed_date($row['completed_date']);
        $p->set_budget($row['budget']);
        $p->set_advance_amount($row['advance_amount']);
        $p->set_pending_amount($row['pending_amount']);
        $p->set_priority($row['priority']);
        $p->set_project_status($row['project_status']);

        // NEW
        if (isset($row['progress'])) {
            $p->set_progress($row['progress']);
        }

        if (isset($row['remarks'])) {
            $p->set_remarks($row['remarks']);
        }

        $p->set_created_at($row['created_at']);

        if (isset($row['company_name'])) {
            $p->set_company_name($row['company_name']);
        }

        if (isset($row['client_name'])) {
            $p->set_client_name($row['client_name']);
        }

        return $p;
    }
}
