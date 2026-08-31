<?php

require_once "../../DB Operations/dbconnection.php";
require_once "../model/ProjectPaymentModel.php";

class DBprojectpayment
{
    // Add Payment
    public static function addPayment(ProjectPayment $payment)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "INSERT INTO project_payments
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
                ?, ?, ?, ?, ?, ?, ?, NOW()
            )";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $project_id     = $payment->get_project_id();
        $payment_date   = $payment->get_payment_date();
        $amount         = $payment->get_amount();
        $payment_mode   = $payment->get_payment_mode();
        $payment_type   = $payment->get_payment_type();
        $transaction_no = $payment->get_transaction_no();
        $remarks        = $payment->get_remarks();

        $stmt->bind_param(
            "isdssss",
            $project_id,
            $payment_date,
            $amount,
            $payment_mode,
            $payment_type,
            $transaction_no,
            $remarks
        );

        if ($stmt->execute()) {
            $id = $conn->insert_id;
            $stmt->close();
            return $id;
        }

        $stmt->close();
        return false;
    }

    // Update Payment
    public static function updatePayment(ProjectPayment $payment)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "UPDATE project_payments
            SET

                payment_date=?,
                amount=?,
                payment_mode=?,
                payment_type=?,
                transaction_no=?,
                remarks=?

            WHERE id=?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $payment_date   = $payment->get_payment_date();
        $amount         = $payment->get_amount();
        $payment_mode   = $payment->get_payment_mode();
        $payment_type   = $payment->get_payment_type();
        $transaction_no = $payment->get_transaction_no();
        $remarks        = $payment->get_remarks();
        $id             = $payment->get_id();

        $stmt->bind_param(
            "sdssssi",
            $payment_date,
            $amount,
            $payment_mode,
            $payment_type,
            $transaction_no,
            $remarks,
            $id
        );

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    // Delete Payment
    public static function deletePayment($id)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $stmt = $conn->prepare(
            "DELETE FROM project_payments WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    // View Single Payment
    public static function viewPayment($id)
    {
        $payments = self::getAllPayments([
            'payment_id' => $id
        ]);

        if (!empty($payments)) {
            return $payments[0];
        }

        return null;
    }

    // Get All Payments
    public static function getAllPayments($filters = [])
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $where = " WHERE 1=1 ";
        $types = "";
        $params = [];

        if (!empty($filters['project_id'])) {
            $where .= " AND pp.project_id = ? ";
            $types .= "i";
            $params[] = $filters['project_id'];
        }
        if (!empty($filters['payment_id'])) {

            $where .= " AND pp.id = ? ";

            $types .= "i";

            $params[] = $filters['payment_id'];
        }

        if (!empty($filters['payment_mode'])) {
            $where .= " AND pp.payment_mode = ? ";
            $types .= "s";
            $params[] = $filters['payment_mode'];
        }

        if (!empty($filters['payment_type'])) {
            $where .= " AND pp.payment_type = ? ";
            $types .= "s";
            $params[] = $filters['payment_type'];
        }

        if (!empty($filters['branch_id'])) {
            $where .= " AND c.branch_id = ? ";
            $types .= "i";
            $params[] = $filters['branch_id'];
        }

        $sql = "SELECT
                pp.*,

                cp.project_name,

                c.client_name,
                c.company_name,
                c.branch_id,

                b.BranchName

            FROM project_payments pp

            INNER JOIN client_projects cp
                ON cp.id = pp.project_id

            INNER JOIN clients c
                ON c.id = cp.client_id

            LEFT JOIN branch b
                ON b.id = c.branch_id

            $where

            ORDER BY pp.payment_date DESC";

        $stmt = $conn->prepare($sql);

        if ($types != "") {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        $result = $stmt->get_result();

        $payments = [];

        while ($row = $result->fetch_assoc()) {

            $payments[] = self::mapRowToPayment($row);
        }

        $stmt->close();

        return $payments;
    }
    public static function getProjectPaymentSummary($projectId)
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $sql = "SELECT

            cp.id,
            cp.project_name,

            cp.budget,
            cp.advance_amount,
            cp.pending_amount,

            c.company_name,
            c.client_name,

            b.BranchName,

            COALESCE(SUM(pp.amount),0) AS paid_amount

        FROM client_projects cp

        INNER JOIN clients c
            ON c.id = cp.client_id

        LEFT JOIN branch b
            ON b.id = c.branch_id

        LEFT JOIN project_payments pp
            ON pp.project_id = cp.id

        WHERE cp.id = ?

        GROUP BY

            cp.id,
            cp.project_name,
            cp.budget,
            cp.advance_amount,
            cp.pending_amount,
            c.company_name,
            c.client_name,
            b.BranchName";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $projectId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            $received = $row['paid_amount'];

            $row['received'] = $received;

            $row['pending'] = $row['budget'] - $received;

            return $row;
        }

        return null;
    }

    // Dashboard Cards
    public static function getPaymentDashboardCounts()
    {
        $conn = ConnectDb::getInstance()->getConnection();

        $dashboard = [

            'total_received'  => 0,
            'today_collection' => 0,
            'month_collection' => 0,
            'pending_amount'  => 0

        ];

        // ==========================================
        // TOTAL RECEIVED
        //  All Payment Entries
        // ==========================================

        $sql = "
        SELECT
            COALESCE(SUM(amount),0) AS total_received
        FROM project_payments
        ";

        $result = $conn->query($sql);

        $dashboard['total_received'] =
            (float)$result->fetch_assoc()['total_received'];



        // ==========================================
        // TODAY'S COLLECTION
        // Only payment entries of today
        // ==========================================

        $sql = "
        SELECT COALESCE(SUM(amount),0) total

        FROM project_payments

        WHERE payment_date = CURDATE()
    ";

        $result = $conn->query($sql);

        $dashboard['today_collection'] =
            (float)$result->fetch_assoc()['total'];



        // ==========================================
        // THIS MONTH
        // ==========================================

        $sql = "
        SELECT COALESCE(SUM(amount),0) total

        FROM project_payments

        WHERE MONTH(payment_date)=MONTH(CURDATE())
        AND YEAR(payment_date)=YEAR(CURDATE())
    ";

        $result = $conn->query($sql);

        $dashboard['month_collection'] =
            (float)$result->fetch_assoc()['total'];



        // ==========================================
        // PENDING REVENUE
        // Budget - (Advance + Payments)
        // ==========================================

        $sql = "
SELECT

COALESCE(SUM(cp.budget),0)

-

COALESCE((
SELECT SUM(amount)
FROM project_payments
),0)

AS pending

FROM client_projects cp
";

        $result = $conn->query($sql);

        $dashboard['pending_amount'] =
            (float)$result->fetch_assoc()['pending'];



        return $dashboard;
    }

    // Convert DB Row to Model
    private static function mapRowToPayment($row)
    {
        $payment = new ProjectPayment();

        $payment->set_id($row['id']);

        $payment->set_project_id($row['project_id']);
        $payment->set_project_name($row['project_name']);

        $payment->set_company_name($row['company_name']);
        $payment->set_client_name($row['client_name']);

        if (isset($row['BranchName'])) {
            $payment->set_branch_name($row['BranchName']);
        }

        $payment->set_payment_date($row['payment_date']);
        $payment->set_amount($row['amount']);

        $payment->set_payment_mode($row['payment_mode']);
        $payment->set_payment_type($row['payment_type']);

        $payment->set_transaction_no($row['transaction_no']);
        $payment->set_remarks($row['remarks']);

        $payment->set_created_at($row['created_at']);

        return $payment;
    }
}
