<?php

require_once __DIR__ . '/../../DB Operations/dbconnection.php';
require_once __DIR__ . '/../Helpers/Mailer.php';

function db()
{
    return ConnectDb::getInstance()->getConnection();
}

class TaskModel
{
    // ================================================================
    // ADMIN: LIST + LOOKUPS
    // ================================================================

    public static function listTasks(string $search, string $status, string $priority): array
    {
        $conn = db();

        $sql = "SELECT
            t.id,
            t.title,
            t.priority,
            t.end_date,
            t.status,
            tc.name AS category_name,
            tc.color_code,

            GROUP_CONCAT(
                DISTINCT e.Name
                SEPARATOR ', '
            ) AS assignee_names,

            /* Employee assignment progress */
            COALESCE(
                ROUND(AVG(ta.progress_percent)),
                0
            ) AS progress_percent,

            CASE
                WHEN SUM(
                    CASE
                        WHEN ta.status = 'Needs Revision'
                        THEN 1
                        ELSE 0
                    END
                ) > 0
                THEN 'Needs Revision'

                WHEN COUNT(ta.id) > 0
                     AND SUM(
                        CASE
                            WHEN ta.status = 'Completed'
                            THEN 1
                            ELSE 0
                        END
                     ) = COUNT(ta.id)
                THEN 'Completed'

                WHEN SUM(
                    CASE
                        WHEN ta.status = 'Review'
                        THEN 1
                        ELSE 0
                    END
                ) > 0
                THEN 'Review'

                WHEN SUM(
                    CASE
                        WHEN ta.status = 'Running'
                        THEN 1
                        ELSE 0
                    END
                ) > 0
                THEN 'Running'

                ELSE t.status
            END AS assignment_status,

            br.BranchName

        FROM tasks t

        LEFT JOIN task_categories tc
            ON tc.id = t.category_id

        LEFT JOIN task_assignments ta
            ON ta.task_id = t.id

        LEFT JOIN trainers e
            ON e.id = ta.employee_id

        LEFT JOIN branch br
            ON br.id = e.BranchId

        WHERE 1=1";

        $params = [];
        $types = '';

        /* Search */
        if ($search !== '') {

            $sql .= " AND t.title LIKE ?";

            $params[] = "%{$search}%";
            $types .= 's';
        }

        /* Priority */
        if ($priority !== '') {

            $sql .= " AND t.priority = ?";

            $params[] = $priority;
            $types .= 's';
        }

        /*
     * Group task + employee assignments
     */
        $sql .= " GROUP BY t.id";

        /*
     * Status filtering happens after
     * assignment_status is calculated.
     */
        if ($status !== '') {

            $sql = "
            SELECT *
            FROM (
                {$sql}
            ) AS task_list

            WHERE assignment_status = ?

            ORDER BY id DESC
        ";

            $params[] = $status;
            $types .= 's';
        } else {

            $sql .= " ORDER BY t.created_at DESC";
        }

        /* Prepare */
        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            throw new Exception(
                'Task list query prepare failed: ' . $conn->error
            );
        }

        /* Bind parameters */
        if ($types !== '') {

            $stmt->bind_param(
                $types,
                ...$params
            );
        }

        /* Execute */
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getLookups(): array
    {
        $conn = db();

        // Categories
        $categoryResult = $conn->query("
        SELECT id, name, color_code
        FROM task_categories
        ORDER BY name
    ");

        if ($categoryResult === false) {
            throw new Exception(
                'Category query failed: ' . $conn->error
            );
        }

        $categories = $categoryResult->fetch_all(MYSQLI_ASSOC);


        // Employees = trainers
        $employeeResult = $conn->query("
        SELECT
            t.id,
            t.Name,
            t.Designation,
            b.BranchName
        FROM trainers t
        LEFT JOIN branch b ON b.id = t.BranchId
        ORDER BY t.Name
    ");

        if ($employeeResult === false) {
            throw new Exception(
                'Trainer query failed: ' . $conn->error
            );
        }

        $employees = $employeeResult->fetch_all(MYSQLI_ASSOC);


        return [
            'categories' => $categories,
            'employees' => $employees
        ];
    }

    // ================================================================
    // ADMIN: CREATE / EDIT / DELETE
    // ================================================================

    public static function createTask(array $data, array $employeeIds, ?int $createdBy): int
    {
        $conn = db();
        $conn->begin_transaction();
        try {
            $categoryId = $data['category_id'] !== '' ? (int)$data['category_id'] : null;

            $stmt = $conn->prepare("
                INSERT INTO tasks (title, description, priority, category_id, start_date, end_date,
                                    reminder_before_minutes, send_reminder_email, status, created_by)
                VALUES (?,?,?,?,?,?,?,?,'Pending',?)
            ");
            $stmt->bind_param(
                'sssissiii',
                $data['title'],
                $data['description'],
                $data['priority'],
                $categoryId,
                $data['start_date'],
                $data['end_date'],
                $data['reminder_before_minutes'],
                $data['send_reminder_email'],
                $createdBy
            );
            $stmt->execute();
            $taskId = $stmt->insert_id;
            $stmt->close();

            self::syncAssignments($taskId, $employeeIds, notifyNew: true, task: [
                'title' => $data['title'],
                'description' => $data['description'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            $conn->commit();
            return $taskId;
        } catch (\Throwable $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public static function updateTask(int $taskId, array $data, array $employeeIds): void
    {
        $conn = db();
        $categoryId = $data['category_id'] !== '' ? (int)$data['category_id'] : null;

        $stmt = $conn->prepare("
            UPDATE tasks SET title=?, description=?, priority=?, category_id=?, start_date=?, end_date=?,
                              reminder_before_minutes=?, send_reminder_email=?
            WHERE id=?
        ");
        $stmt->bind_param(
            'sssisssii',
            $data['title'],
            $data['description'],
            $data['priority'],
            $categoryId,
            $data['start_date'],
            $data['end_date'],
            $data['reminder_before_minutes'],
            $data['send_reminder_email'],
            $taskId
        );
        $stmt->execute();
        $stmt->close();

        self::syncAssignments($taskId, $employeeIds, notifyNew: true, task: [
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);
    }

    // Adds newly-checked employees as new assignments (and emails them).
    // Removes assignments only if they are still 'Pending' (never touched) to avoid
    // wiping out an employee's in-progress work by accident.
    private static function syncAssignments(int $taskId, array $employeeIds, bool $notifyNew, array $task): void
    {
        $conn = db();

        $existing = [];
        $res = $conn->query("SELECT id, employee_id FROM task_assignments WHERE task_id = {$taskId}");
        while ($row = $res->fetch_assoc()) {
            $existing[(int)$row['employee_id']] = (int)$row['id'];
        }

        $employeeIds = array_map('intval', $employeeIds);

        // Remove unchecked employees that never started work
        $toRemove = array_diff(array_keys($existing), $employeeIds);
        foreach ($toRemove as $empId) {
            $conn->query("DELETE FROM task_assignments WHERE id = {$existing[$empId]} AND status = 'Pending'");
        }

        // Add newly checked employees
        $toAdd = array_diff($employeeIds, array_keys($existing));
        if (empty($toAdd)) return;

        $stmt = $conn->prepare("INSERT INTO task_assignments (task_id, employee_id, status) VALUES (?, ?, 'Pending')");
        foreach ($toAdd as $empId) {
            $stmt->bind_param('ii', $taskId, $empId);
            $stmt->execute();
        }
        $stmt->close();

        if ($notifyNew) {
            $placeholders = implode(',', array_fill(0, count($toAdd), '?'));
            $types = str_repeat('i', count($toAdd));
            $stmt = $conn->prepare("SELECT id, Name, Email FROM trainers WHERE id IN ({$placeholders})");
            $stmt->bind_param($types, ...$toAdd);
            $stmt->execute();
            $emps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            foreach ($emps as $emp) {
                Mailer::taskAssigned($emp['Email'], $emp['Name'], $task['title'], $task['description'], $task['start_date'], $task['end_date']);
            }
        }
    }

    public static function deleteTask(int $taskId): void
    {
        db()->query("DELETE FROM tasks WHERE id = {$taskId}"); // cascades to assignments/progress/reviews
    }

    // ================================================================
    // ADMIN: VIEW / REVIEW / APPROVE
    // ================================================================

    // $assignmentId optional; defaults to the first assignment on the task
    // (recommended: one employee per task for the cleanest review flow).
    public static function getReviewData(int $taskId, ?int $assignmentId = null): ?array
    {
        $conn = db();

        // ============================================================
        // 1. GET TASK
        // ============================================================

        $stmt = $conn->prepare("
        SELECT *
        FROM tasks
        WHERE id = ?
        LIMIT 1
    ");

        if (!$stmt) {
            throw new Exception(
                'Task query prepare failed: ' . $conn->error
            );
        }

        $stmt->bind_param('i', $taskId);

        if (!$stmt->execute()) {
            throw new Exception(
                'Task query execute failed: ' . $stmt->error
            );
        }

        $task = $stmt->get_result()->fetch_assoc();

        if (!$task) {
            return null;
        }


        // ============================================================
        // 2. GET ALL ASSIGNMENTS
        //
        // IMPORTANT:
        // employee_id in task_assignments points to trainers.id
        // ============================================================

        if ($assignmentId) {

            $stmt = $conn->prepare("
            SELECT
                ta.*,
                tr.Name,
                tr.Email,
                tr.Phone,
                tr.StaffCode
            FROM task_assignments ta
            LEFT JOIN trainers tr
                ON tr.id = ta.employee_id
            WHERE ta.id = ?
              AND ta.task_id = ?
            ORDER BY ta.id ASC
        ");

            if (!$stmt) {
                throw new Exception(
                    'Assignment query prepare failed: ' . $conn->error
                );
            }

            $stmt->bind_param(
                'ii',
                $assignmentId,
                $taskId
            );
        } else {

            $stmt = $conn->prepare("
            SELECT
                ta.*,
                tr.Name,
                tr.Email,
                tr.Phone,
                tr.StaffCode
            FROM task_assignments ta
            LEFT JOIN trainers tr
                ON tr.id = ta.employee_id
            WHERE ta.task_id = ?
            ORDER BY ta.id ASC
        ");

            if (!$stmt) {
                throw new Exception(
                    'Assignment query prepare failed: ' . $conn->error
                );
            }

            $stmt->bind_param(
                'i',
                $taskId
            );
        }


        if (!$stmt->execute()) {
            throw new Exception(
                'Assignment query execute failed: ' . $stmt->error
            );
        }

        $assignments = $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);


        // ============================================================
        // 3. GET PROGRESS FOR ALL ASSIGNMENTS
        // ============================================================

        $progress = [];

        if (!empty($assignments)) {

            $progressStmt = $conn->prepare("
            SELECT *
            FROM task_progress_updates
            WHERE assignment_id = ?
            ORDER BY created_at DESC
        ");

            if (!$progressStmt) {
                throw new Exception(
                    'Progress query prepare failed: ' . $conn->error
                );
            }


            foreach ($assignments as $assignment) {

                $assignmentIdForProgress =
                    (int)$assignment['id'];

                $progressStmt->bind_param(
                    'i',
                    $assignmentIdForProgress
                );

                if (!$progressStmt->execute()) {
                    throw new Exception(
                        'Progress query execute failed: ' .
                            $progressStmt->error
                    );
                }

                $rows = $progressStmt
                    ->get_result()
                    ->fetch_all(MYSQLI_ASSOC);

                foreach ($rows as $row) {
                    $progress[] = $row;
                }
            }

            $progressStmt->close();
        }


        // ============================================================
        // 4. KEEP FIRST ASSIGNMENT FOR BACKWARD COMPATIBILITY
        // ============================================================

        $assignment = !empty($assignments)
            ? $assignments[0]
            : null;


        // ============================================================
        // 5. RETURN DATA
        // ============================================================

        return [
            'task'        => $task,

            // New format used by View Task
            'assignments' => $assignments,

            // Keep old key so existing logic doesn't break
            'assignment'  => $assignment,

            'progress'    => $progress
        ];
    }

    public static function approveTask(int $taskId, ?int $assignmentId, ?int $adminId): bool
    {
        $conn = db();

        $where = $assignmentId
            ? "ta.id = {$assignmentId}"
            : "ta.task_id = {$taskId}";

        // Get assignment
        $stmt = $conn->prepare("
        SELECT 
            ta.id,
            ta.employee_id,
            e.Name,
            e.Email,
            t.title
        FROM task_assignments ta
        JOIN trainers e ON e.id = ta.employee_id
        JOIN tasks t ON t.id = ta.task_id
        WHERE {$where}
          AND ta.status != 'Completed'
    ");

        if (!$stmt) {
            throw new Exception(
                "Assignment query failed: " . $conn->error
            );
        }

        $stmt->execute();

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if (empty($rows)) {
            return false;
        }

        foreach ($rows as $row) {

            // 1. Mark assignment completed
            $upd = $conn->prepare("
    UPDATE task_assignments
    SET 
        status = 'Completed',
        progress_percent = 100
    WHERE id = ?
");

            if (!$upd) {
                throw new Exception(
                    "Assignment update failed: " . $conn->error
                );
            }

            $assignmentIdValue = (int)$row['id'];

            $upd->bind_param(
                'i',
                $assignmentIdValue
            );

            $upd->execute();


            // 2. Add review record
            $reviewText = 'Approved as complete';
            $reviewStatus = 'Approved';

            $rev = $conn->prepare("
            INSERT INTO task_reviews
            (
                task_id,
                assignment_id,
                review_text,
                review_status,
                reviewed_by
            )
            VALUES (?, ?, ?, ?, ?)
        ");

            if (!$rev) {
                throw new Exception(
                    "Review insert failed: " . $conn->error
                );
            }

            $taskIdValue = (int)$taskId;
            $reviewedBy = $adminId !== null ? (int)$adminId : null;

            $rev->bind_param(
                'iissi',
                $taskIdValue,
                $assignmentIdValue,
                $reviewText,
                $reviewStatus,
                $reviewedBy
            );

            $rev->execute();


            // 3. Send email
            if (!empty($row['Email'])) {
                Mailer::taskApproved(
                    $row['Email'],
                    $row['Name'],
                    $row['title']
                );
            }
        }


        // 4. Check whether all assignments are completed
        $remainingResult = $conn->query("
        SELECT COUNT(*) AS c
        FROM task_assignments
        WHERE task_id = {$taskId}
          AND status != 'Completed'
    ");

        if (!$remainingResult) {
            throw new Exception(
                "Remaining assignment check failed: " . $conn->error
            );
        }

        $remaining = (int)$remainingResult
            ->fetch_assoc()['c'];


        // 5. Mark main task completed
        if ($remaining === 0) {

            $taskUpdate = $conn->query("
            UPDATE tasks
            SET status = 'Completed'
            WHERE id = {$taskId}
        ");

            if (!$taskUpdate) {
                throw new Exception(
                    "Task completion update failed: " . $conn->error
                );
            }
        }

        return true;
    }

    public static function sendReview(int $taskId, ?int $assignmentId, string $reviewText, ?int $adminId): bool
    {
        $conn = db();

        $where = $assignmentId ? "id = {$assignmentId}" : "task_id = {$taskId}";
        $stmt = $conn->prepare("SELECT ta.id, ta.employee_id, e.Name, e.Email, t.title
                                 FROM task_assignments ta
                                 JOIN trainers e ON e.id = ta.employee_id
                                 JOIN tasks t ON t.id = ta.task_id
                                 WHERE ta.{$where}");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (empty($rows)) return false;

        foreach ($rows as $row) {
            $upd = $conn->prepare("UPDATE task_assignments SET status='Needs Revision' WHERE id=?");
            $upd->bind_param('i', $row['id']);
            $upd->execute();

            $rev = $conn->prepare("INSERT INTO task_reviews (task_id, assignment_id, review_text, review_status, reviewed_by) VALUES (?,?,?,'Needs Revision',?)");
            $rev->bind_param('iisi', $taskId, $row['id'], $reviewText, $adminId);
            $rev->execute();

            Mailer::taskNeedsRevision($row['Email'], $row['Name'], $row['title'], $reviewText);
        }

        $conn->query("UPDATE tasks SET status='Needs Revision' WHERE id = {$taskId}");
        return true;
    }

    // ================================================================
    // EMPLOYEE SIDE
    // ================================================================

    public static function getMyTasks(int $employeeId): array
    {
        $conn = db();
        $stmt = $conn->prepare("
            SELECT ta.id AS assignment_id, ta.status, ta.progress_percent,
                   t.id AS task_id, t.title, t.priority, t.end_date, t.start_date,
                   tc.name AS category_name, tc.color_code
            FROM task_assignments ta
            JOIN tasks t ON t.id = ta.task_id
            LEFT JOIN task_categories tc ON tc.id = t.category_id
            WHERE ta.employee_id = ?
            ORDER BY (ta.status='Completed'), t.end_date IS NULL, t.end_date ASC
        ");
        $stmt->bind_param('i', $employeeId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getMyTaskDetail(int $assignmentId, int $employeeId): ?array
    {
        $conn = db();
        $stmt = $conn->prepare("
            SELECT ta.*, t.title, t.description, t.id AS task_id
            FROM task_assignments ta
            JOIN tasks t ON t.id = ta.task_id
            WHERE ta.id = ? AND ta.employee_id = ?
        ");
        $stmt->bind_param('ii', $assignmentId, $employeeId);
        $stmt->execute();
        $assignment = $stmt->get_result()->fetch_assoc();
        if (!$assignment) return null;

        $stmt = $conn->prepare("SELECT * FROM task_checklist_items WHERE task_id = ?");
        $stmt->bind_param('i', $assignment['task_id']);
        $stmt->execute();
        $checklist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Latest admin review (if any) so the employee sees why it was sent back
        $stmt = $conn->prepare("SELECT review_text, review_status, reviewed_on FROM task_reviews WHERE assignment_id = ? ORDER BY reviewed_on DESC LIMIT 1");
        $stmt->bind_param('i', $assignmentId);
        $stmt->execute();
        $reviewRow = $stmt->get_result()->fetch_assoc();
        $review = $reviewRow ? [
            'review_text'   => $reviewRow['review_text'],
            'review_status' => $reviewRow['review_status'],
            'reviewed_on'   => $reviewRow['reviewed_on'],
        ] : null;

        // Work history: what the employee did on their last update — shown so
        // they have context ("what I did yesterday") before logging today's work.
        $stmt = $conn->prepare("SELECT * FROM task_progress_updates WHERE assignment_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->bind_param('i', $assignmentId);
        $stmt->execute();
        $history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $assignment['checklist']    = $checklist;
        $assignment['review']       = $review;
        $assignment['work_history'] = $history;      // full recent history
        $assignment['last_update']  = $history[0] ?? null; // most recent one, for quick display

        return $assignment;
    }

    public static function updateProgress(int $assignmentId, int $employeeId, array $data, ?array $file): bool
    {
        $conn = db();

        $stmt = $conn->prepare("SELECT ta.id, ta.task_id, t.title FROM task_assignments ta JOIN tasks t ON t.id = ta.task_id WHERE ta.id = ? AND ta.employee_id = ?");
        $stmt->bind_param('ii', $assignmentId, $employeeId);
        $stmt->execute();
        $assignment = $stmt->get_result()->fetch_assoc();
        if (!$assignment) return false;

        $attachmentPath = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $dir = __DIR__ . '/../uploads/task_updates/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file['name']);
            move_uploaded_file($file['tmp_name'], $dir . $safeName);
            $attachmentPath = 'uploads/task_updates/' . $safeName;
        }

        $progress = (int)$data['progress_percent'];

        $stmt = $conn->prepare("
            INSERT INTO task_progress_updates (assignment_id, task_id, progress_percent, work_summary, issues, hours_worked, attachment_path)
            VALUES (?,?,?,?,?,?,?)
        ");
        $stmt->bind_param(
            'iiissds',
            $assignmentId,
            $assignment['task_id'],
            $progress,
            $data['work_summary'],
            $data['issues'],
            $data['hours_worked'],
            $attachmentPath
        );
        $stmt->execute();

        $newStatus = $progress >= 100 ? 'Review' : 'Running';
        $stmt = $conn->prepare("UPDATE task_assignments SET progress_percent=?, status=? WHERE id=?");
        $stmt->bind_param('isi', $progress, $newStatus, $assignmentId);
        $stmt->execute();

        // Notify admin every time an employee submits an update
        $empResult = $conn->query(
            "SELECT Name FROM trainers WHERE id = {$employeeId}"
        );

        $empName = 'Employee';

        if ($empResult) {
            $empRow = $empResult->fetch_assoc();

            if ($empRow && !empty($empRow['Name'])) {
                $empName = $empRow['Name'];
            }
        }

        // Send task update email to admin
        Mailer::taskUpdatedNotifyAdmin(
            $empName,
            $assignment['title'],
            $data['work_summary']
        );

        return true;
    }

    // ================================================================
    // CRON: REMINDERS
    // ================================================================

    // Once-a-day nudge for every active (not completed) assignment whose task
    // window (start_date..end_date) includes today, and send_reminder_email=1.
    public static function getAssignmentsNeedingDailyReminder(): array
    {
        $conn = db();
        $today = date('Y-m-d');
        return $conn->query("
            SELECT ta.id AS assignment_id, e.Email, e.Name, t.title, t.end_date
            FROM task_assignments ta
            JOIN tasks t ON t.id = ta.task_id
            JOIN trainers e ON e.id = ta.employee_id
            WHERE ta.status != 'Completed'
              AND t.send_reminder_email = 1
              AND t.start_date IS NOT NULL AND t.end_date IS NOT NULL
              AND '{$today}' BETWEEN t.start_date AND t.end_date
              AND NOT EXISTS (
                  SELECT 1 FROM task_reminder_log l
                  WHERE l.assignment_id = ta.id AND l.reminder_type='daily' AND l.reminder_date = '{$today}'
              )
        ")->fetch_all(MYSQLI_ASSOC);
    }

    // Assignments where "now" is within `reminder_before_minutes` of the
    // deadline (end_date 23:59:59), not yet reminded today, not completed.
    public static function getAssignmentsNeedingDeadlineReminder(): array
    {
        $conn = db();
        $today = date('Y-m-d');
        return $conn->query("
            SELECT ta.id AS assignment_id, e.Email, e.Name, t.title, t.end_date, t.reminder_before_minutes
            FROM task_assignments ta
            JOIN tasks t ON t.id = ta.task_id
            JOIN trainers e ON e.id = ta.employee_id
            WHERE ta.status != 'Completed'
              AND t.send_reminder_email = 1
              AND t.end_date = '{$today}'
              AND TIMESTAMPDIFF(MINUTE, NOW(), CONCAT(t.end_date, ' 23:59:59')) <= t.reminder_before_minutes
              AND TIMESTAMPDIFF(MINUTE, NOW(), CONCAT(t.end_date, ' 23:59:59')) >= 0
              AND NOT EXISTS (
                  SELECT 1 FROM task_reminder_log l
                  WHERE l.assignment_id = ta.id AND l.reminder_type='deadline' AND l.reminder_date = '{$today}'
              )
        ")->fetch_all(MYSQLI_ASSOC);
    }

    public static function markReminderSent(int $assignmentId, string $type): void
    {
        $conn = db();
        $stmt = $conn->prepare("INSERT IGNORE INTO task_reminder_log (assignment_id, reminder_type, reminder_date) VALUES (?, ?, CURDATE())");
        $stmt->bind_param('is', $assignmentId, $type);
        $stmt->execute();
    }
}
