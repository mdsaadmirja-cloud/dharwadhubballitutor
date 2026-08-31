<?php
// TaskModel.php lives at: C:\wamp64\www\Admin\Model\TaskModel.php
// dbconnection.php lives at: C:\wamp64\www\DB Operations\dbconnection.php
// So from Admin/Model/, go up twice to www/, then into "DB Operations"
require_once __DIR__ . '/../../DB Operations/dbconnection.php';

class TaskModel
{
    private $conn;

    public function __construct()
    {
        // Make mysqli throw exceptions on any error instead of failing silently.
        // Without this, a failed INSERT (e.g. a foreign key violation) just
        // returns false and everything downstream keeps going as if it worked.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = ConnectDb::getInstance()->getConnection();
    }

    // ------------------------------------------------------------------
    // LOOKUPS (for dropdowns on the Assign Task form)
    // ------------------------------------------------------------------

    // ---------------------------------------------------------
    // Get Trainer ID from Logged-in User
    // ---------------------------------------------------------
    public function getTrainerIdByUserId($userId)
    {
        $stmt = $this->conn->prepare("
        SELECT id
        FROM trainers
        WHERE user_id = ?
        LIMIT 1
    ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return (int)$row['id'];
        }

        return null;
    }
    public function getCategories()
    {
        $result = $this->conn->query("SELECT id, name, color_code FROM task_categories WHERE is_active = 1 ORDER BY name");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEmployees()
    {
        $sql = "SELECT t.id, t.StaffCode, t.Name, t.Email, t.Phone, t.Designation, t.Department,
                       t.BranchId, b.BranchName, t.ShiftID, s.ShiftName, s.EndTime
                FROM trainers t
                LEFT JOIN branch b ON b.id = t.BranchId
                LEFT JOIN shifts s ON s.id = t.ShiftID
                WHERE t.Status = 'Active'
                ORDER BY t.Name";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEmployeeById($employeeId)
    {
        $stmt = $this->conn->prepare(
            "SELECT t.id, t.StaffCode, t.Name, t.Email, t.Phone, t.Designation, t.Department,
                    t.BranchId, b.BranchName, t.ShiftID, s.ShiftName, s.StartTime, s.EndTime
             FROM trainers t
             LEFT JOIN branch b ON b.id = t.BranchId
             LEFT JOIN shifts s ON s.id = t.ShiftID
             WHERE t.id = ?"
        );
        $stmt->bind_param('i', $employeeId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ------------------------------------------------------------------
    // TASK LIST / READ
    // ------------------------------------------------------------------

    /**
     * @param array $filters optional keys: status, priority, category_id, branch_id, employee_id, search
     */
    public function getAllTasks($filters = [])
    {
        $sql = "SELECT
                    t.id, t.title, t.priority, t.status, t.start_date, t.end_date,
                    t.estimated_hours, t.created_at,
                    c.name AS category_name, c.color_code,
                    br.BranchName,
                    COUNT(ta.id) AS assignee_count,
                    SUM(CASE WHEN ta.status = 'Completed' THEN 1 ELSE 0 END) AS completed_count,
                    SUM(CASE WHEN ta.status = 'Late' THEN 1 ELSE 0 END) AS late_count,
                    ROUND(AVG(ta.progress_percent), 0) AS avg_progress
                FROM tasks t
                LEFT JOIN task_categories c ON c.id = t.category_id
                LEFT JOIN branch br ON br.id = t.branch_id
                LEFT JOIN task_assignments ta ON ta.task_id = t.id
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = ?";
            $params[] = $filters['status'];
            $types .= 's';
        }
        if (!empty($filters['priority'])) {
            $sql .= " AND t.priority = ?";
            $params[] = $filters['priority'];
            $types .= 's';
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = ?";
            $params[] = $filters['category_id'];
            $types .= 'i';
        }
        if (!empty($filters['branch_id'])) {
            $sql .= " AND t.branch_id = ?";
            $params[] = $filters['branch_id'];
            $types .= 'i';
        }
        if (!empty($filters['employee_id'])) {
            $sql .= " AND ta.employee_id = ?";
            $params[] = $filters['employee_id'];
            $types .= 'i';
        }
        if (!empty($filters['search'])) {
            $sql .= " AND t.title LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
            $types .= 's';
        }

        $sql .= " GROUP BY t.id ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTaskById($taskId)
    {
        $stmt = $this->conn->prepare(
            "SELECT t.*, c.name AS category_name, br.BranchName
             FROM tasks t
             LEFT JOIN task_categories c ON c.id = t.category_id
             LEFT JOIN branch br ON br.id = t.branch_id
             WHERE t.id = ?"
        );
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        $task = $stmt->get_result()->fetch_assoc();
        if (!$task) return null;

        $task['assignees'] = $this->getAssignmentsByTask($taskId);
        $task['checklist'] = $this->getChecklist($taskId);
        return $task;
    }

    public function getAssignmentsByTask($taskId)
    {
        $stmt = $this->conn->prepare(
            "SELECT ta.*, tr.Name, tr.Email, tr.StaffCode, tr.PhotoFile
             FROM task_assignments ta
             JOIN trainers tr ON tr.id = ta.employee_id
             WHERE ta.task_id = ?
             ORDER BY tr.Name"
        );
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getChecklist($taskId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM task_checklists WHERE task_id = ? ORDER BY sort_order");
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ------------------------------------------------------------------
    // EMPLOYEE-FACING: "MY TASKS"
    // ------------------------------------------------------------------

    /**
     * All tasks assigned to one employee, with computed lock/remaining-time state.
     */
    public function getMyAssignments($employeeId)
    {
        $stmt = $this->conn->prepare(
            "SELECT ta.id AS assignment_id, ta.task_id, ta.status, ta.progress_percent,
                    ta.checkout_time, ta.window_opens_at, ta.window_closes_at,
                    ta.submitted_at, ta.is_locked,
                    t.title, t.description, t.priority, t.end_date,
                    c.name AS category_name, c.color_code
             FROM task_assignments ta
             JOIN tasks t ON t.id = ta.task_id
             LEFT JOIN task_categories c ON c.id = t.category_id
             WHERE ta.employee_id = ?
             ORDER BY
                CASE ta.status WHEN 'Locked' THEN 3 WHEN 'Completed' THEN 2 ELSE 1 END,
                t.end_date ASC"
        );
        $stmt->bind_param('i', $employeeId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $now = time();
        foreach ($rows as &$row) {
            $row['window_open_now'] = false;
            if ($row['window_opens_at'] && $row['window_closes_at'] && !$row['is_locked'] && !$row['submitted_at']) {
                $opens = strtotime($row['window_opens_at']);
                $closes = strtotime($row['window_closes_at']);
                $row['window_open_now'] = ($now >= $opens && $now <= $closes);
            }
        }
        return $rows;
    }

    public function getAssignmentDetail($assignmentId)
    {
        $stmt = $this->conn->prepare(
            "SELECT ta.*, t.title, t.description, t.priority, t.start_date, t.end_date, t.estimated_hours
             FROM task_assignments ta
             JOIN tasks t ON t.id = ta.task_id
             WHERE ta.id = ?"
        );
        $stmt->bind_param('i', $assignmentId);
        $stmt->execute();
        $assignment = $stmt->get_result()->fetch_assoc();
        if (!$assignment) return null;

        $assignment['checklist'] = $this->getChecklist($assignment['task_id']);

        $histStmt = $this->conn->prepare(
            "SELECT * FROM task_progress WHERE assignment_id = ? ORDER BY submitted_at DESC"
        );
        $histStmt->bind_param('i', $assignmentId);
        $histStmt->execute();
        $assignment['progress_history'] = $histStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $fileStmt = $this->conn->prepare(
            "SELECT * FROM task_files WHERE assignment_id = ? ORDER BY uploaded_at DESC"
        );
        $fileStmt->bind_param('i', $assignmentId);
        $fileStmt->execute();
        $assignment['files'] = $fileStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $assignment;
    }

    public function saveAssignmentFile($taskId, $assignmentId, $uploadedBy, $fileName, $filePath, $fileType, $fileSizeKb)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO task_files (task_id, assignment_id, uploaded_by, file_name, file_path, file_type, file_size_kb)
             VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->bind_param('iiisssi', $taskId, $assignmentId, $uploadedBy, $fileName, $filePath, $fileType, $fileSizeKb);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function addComment($taskId, $assignmentId, $employeeId, $comment)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO task_comments (task_id, assignment_id, employee_id, comment) VALUES (?,?,?,?)"
        );
        $stmt->bind_param('iiis', $taskId, $assignmentId, $employeeId, $comment);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    // ------------------------------------------------------------------
    // CREATE
    // ------------------------------------------------------------------

    /**
     * Creates the task, assigns it to one or more employees, and seeds
     * per-employee window times based on each employee's shift checkout time.
     * Returns the new task id.
     */
    public function createTask($data, $employeeIds, $checklistItems, $createdBy)
    {
        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO tasks
                    (title, description, category_id, priority, status, branch_id, department_name,
                     start_date, end_date, estimated_hours,
                     reminder_before_minutes, submission_window_minutes,
                     send_reminder_email, lock_after_window, send_thankyou_email,
                     notify_admin_if_missed, auto_escalate, created_by)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
            );
            $status = 'Pending';
            // Param order: title(s) description(s) category_id(i) priority(s) status(s)
            //   branch_id(i) department_name(s) start_date(s) end_date(s) estimated_hours(d)
            //   reminder_before_minutes(i) submission_window_minutes(i) send_reminder_email(i)
            //   lock_after_window(i) send_thankyou_email(i) notify_admin_if_missed(i) auto_escalate(i) created_by(i)
            $stmt->bind_param(
                'ssississsdiiiiiiii',
                $data['title'],
                $data['description'],
                $data['category_id'],
                $data['priority'],
                $status,
                $data['branch_id'],
                $data['department_name'],
                $data['start_date'],
                $data['end_date'],
                $data['estimated_hours'],
                $data['reminder_before_minutes'],
                $data['submission_window_minutes'],
                $data['send_reminder_email'],
                $data['lock_after_window'],
                $data['send_thankyou_email'],
                $data['notify_admin_if_missed'],
                $data['auto_escalate'],
                $createdBy
            );
            $stmt->execute();
            $taskId = $this->conn->insert_id;

            // Checklist
            if (!empty($checklistItems)) {
                $order = 0;
                $chkStmt = $this->conn->prepare(
                    "INSERT INTO task_checklists (task_id, item_text, sort_order) VALUES (?,?,?)"
                );
                foreach ($checklistItems as $item) {
                    $item = trim($item);
                    if ($item === '') continue;
                    $chkStmt->bind_param('isi', $taskId, $item, $order);
                    $chkStmt->execute();
                    $order++;
                }
            }

            // Assignments — one row per employee, with shift-based window snapshot
            foreach ($employeeIds as $employeeId) {
                $employee = $this->getEmployeeById($employeeId);
                if (!$employee) continue;

                $checkoutTime = $employee['EndTime']; // e.g. '18:00:00'
                $reminderMinutes = (int)$data['reminder_before_minutes'];
                $windowMinutes = (int)$data['submission_window_minutes'];

                // window_opens_at = today's checkout time minus reminder minutes
                // window_closes_at = window_opens_at + window minutes
                $today = date('Y-m-d');
                $windowOpens = null;
                $windowCloses = null;
                if ($checkoutTime) {
                    $checkoutTs = strtotime("$today $checkoutTime");
                    $opensTs = $checkoutTs - ($reminderMinutes * 60);
                    $closesTs = $opensTs + ($windowMinutes * 60);
                    $windowOpens = date('Y-m-d H:i:s', $opensTs);
                    $windowCloses = date('Y-m-d H:i:s', $closesTs);
                }

                $assignStmt = $this->conn->prepare(
                    "INSERT INTO task_assignments
                        (task_id, employee_id, designation_snapshot, department_snapshot,
                         branch_id, shift_id, status, checkout_time,
                         window_opens_at, window_closes_at, assigned_by)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?)"
                );
                $status = 'Pending';
                // Param order: task_id(i) employee_id(i) designation(s) department(s)
                //   branch_id(i) shift_id(i) status(s) checkout_time(s)
                //   window_opens_at(s) window_closes_at(s) assigned_by(i)  = 11 values
                $assignStmt->bind_param(
                    'iissiissssi',
                    $taskId,
                    $employeeId,
                    $employee['Designation'],
                    $employee['Department'],
                    $employee['BranchId'],
                    $employee['ShiftID'],
                    $status,
                    $checkoutTime,
                    $windowOpens,
                    $windowCloses,
                    $createdBy
                );
                $assignStmt->execute();
                $assignmentId = $this->conn->insert_id;

                $this->logActivity($taskId, $assignmentId, $createdBy, 'assigned', "Task assigned to {$employee['Name']}");
                $this->notify($employeeId, $taskId, $assignmentId, 'assigned', "You have been assigned a new task: {$data['title']}");
            }

            $this->conn->commit();
            return $taskId;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // ------------------------------------------------------------------
    // UPDATE
    // ------------------------------------------------------------------

    public function updateTask($taskId, $data, $changedBy)
    {
        $stmt = $this->conn->prepare(
            "UPDATE tasks SET
                title = ?, description = ?, category_id = ?, priority = ?,
                branch_id = ?, department_name = ?, start_date = ?, end_date = ?,
                estimated_hours = ?
             WHERE id = ?"
        );
        // Param order: title(s) description(s) category_id(i) priority(s) branch_id(i)
        //   department_name(s) start_date(s) end_date(s) estimated_hours(d) taskId(i) = 10 values
        $stmt->bind_param(
            'ssisisssdi',
            $data['title'],
            $data['description'],
            $data['category_id'],
            $data['priority'],
            $data['branch_id'],
            $data['department_name'],
            $data['start_date'],
            $data['end_date'],
            $data['estimated_hours'],
            $taskId
        );
        $stmt->execute();

        $this->logActivity($taskId, null, $changedBy, 'updated', 'Task details updated');
        return $stmt->affected_rows;
    }

    public function updateAssignmentProgress($assignmentId, $progressPercent, $workSummary, $issues, $hoursWorked)
    {
        // Guard: don't accept updates on a locked assignment even if the request bypasses the UI
        $check = $this->conn->prepare("SELECT is_locked FROM task_assignments WHERE id = ?");
        $check->bind_param('i', $assignmentId);
        $check->execute();
        $row = $check->get_result()->fetch_assoc();
        if (!$row) {
            throw new Exception('Assignment not found');
        }
        if ((int)$row['is_locked'] === 1) {
            throw new Exception('Submission window closed. Please contact your reporting manager.');
        }

        $this->conn->begin_transaction();
        try {
            // Log the individual progress entry
            $stmt = $this->conn->prepare(
                "INSERT INTO task_progress (assignment_id, progress_percent, work_summary, issues, hours_worked)
                 VALUES (?,?,?,?,?)"
            );
            $stmt->bind_param('iissd', $assignmentId, $progressPercent, $workSummary, $issues, $hoursWorked);
            $stmt->execute();

            // Update the rollup on the assignment itself
            $newStatus = $progressPercent >= 100 ? 'Completed' : 'Running';
            $stmt2 = $this->conn->prepare(
                "UPDATE task_assignments
                 SET progress_percent = ?, status = ?, submitted_at = NOW()
                 WHERE id = ?"
            );
            $stmt2->bind_param('isi', $progressPercent, $newStatus, $assignmentId);
            $stmt2->execute();

            $row = $this->conn->query("SELECT task_id, employee_id FROM task_assignments WHERE id = $assignmentId")->fetch_assoc();
            $this->logActivity($row['task_id'], $assignmentId, $row['employee_id'], 'submitted', "Progress updated to {$progressPercent}%");

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    /** Called by the cron job once a submission window closes without a submission. */
    public function lockAssignment($assignmentId)
    {
        $stmt = $this->conn->prepare(
            "UPDATE task_assignments SET is_locked = 1, locked_at = NOW(), status = 'Locked'
             WHERE id = ? AND submitted_at IS NULL"
        );
        $stmt->bind_param('i', $assignmentId);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    // ------------------------------------------------------------------
    // DELETE
    // ------------------------------------------------------------------

    public function deleteTask($taskId)
    {
        // ON DELETE CASCADE on task_assignments/task_progress/etc. handles the rest
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function removeAssignee($assignmentId)
    {
        $stmt = $this->conn->prepare("DELETE FROM task_assignments WHERE id = ?");
        $stmt->bind_param('i', $assignmentId);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    // ------------------------------------------------------------------
    // HELPERS
    // ------------------------------------------------------------------

    private function logActivity($taskId, $assignmentId, $actorId, $type, $description)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO task_activity (task_id, assignment_id, actor_id, activity_type, description)
             VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param('iiiss', $taskId, $assignmentId, $actorId, $type, $description);
        $stmt->execute();
    }

    private function notify($recipientId, $taskId, $assignmentId, $type, $message)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO task_notifications (recipient_id, task_id, assignment_id, type, message)
             VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param('iiiss', $recipientId, $taskId, $assignmentId, $type, $message);
        $stmt->execute();
    }
    public function getLatestReview($assignmentId)
    {
        $stmt = $this->conn->prepare("
        SELECT
            review_text,
            review_status,
            reviewed_on
        FROM task_reviews
        WHERE assignment_id=?
        ORDER BY review_id DESC
        LIMIT 1
    ");

        $stmt->bind_param("i", $assignmentId);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}
