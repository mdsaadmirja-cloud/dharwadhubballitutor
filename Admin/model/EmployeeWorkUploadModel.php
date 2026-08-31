<?php

require_once __DIR__ . '/../../DB Operations/dbconnection.php';
require_once __DIR__ . '/../Helpers/Mailer.php';


/**
 * Database helper
 */
function db()
{
    return ConnectDb::getInstance()->getConnection();
}


class EmployeeWorkUploadModel
{
    // ================================================================
    // FETCH HELPERS
    // ================================================================

    private static function fetchOne($stmt): ?array
    {
        $result = $stmt->get_result();

        if (!$result) {
            return null;
        }

        $row = $result->fetch_assoc();

        return $row ?: null;
    }


    private static function fetchAll($stmt): array
    {
        $result = $stmt->get_result();

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // ================================================================
    // ADMIN - LIST ALL WORK UPLOADS
    // ================================================================

    public static function adminList(
        string $search = '',
        string $reviewStatus = ''
    ): array {

        $conn = db();

        /*
         * IMPORTANT
         *
         * employee_work_uploads.employee_id
         * stores user.user_id.
         *
         * trainers.user_id points to user.user_id.
         *
         * Therefore:
         *
         * trainers.user_id = employee_work_uploads.employee_id
         *
         * NOT:
         *
         * trainers.id = employee_work_uploads.employee_id
         */

        $sql = "
            SELECT

                w.id,

                w.employee_id,

                w.title,

                w.description,

                w.category_id,

                w.hours_worked,

                w.github_link,

                w.live_url,

                w.drive_link,

                w.next_plan,

                w.status,

                w.review_status,

                w.review_comment,

                w.reviewed_by,

                w.reviewed_on,

                w.created_at,

                w.updated_at,

                /* Trainer information */

                t.id AS trainer_id,

                t.user_id AS trainer_user_id,

                t.Name AS employee_name,

                t.StaffCode AS StaffCode,

                t.Email AS employee_email,

                /* Category */

                tc.name AS category_name,

                tc.color_code

            FROM employee_work_uploads w

            LEFT JOIN trainers t
                ON t.user_id = w.employee_id

            LEFT JOIN task_categories tc
                ON tc.id = w.category_id

            WHERE 1 = 1
        ";


        $params = [];
        $types = '';


        // ============================================================
        // SEARCH
        // ============================================================

        if ($search !== '') {

            $sql .= "
                AND (
                    w.title LIKE ?
                    OR t.Name LIKE ?
                    OR t.StaffCode LIKE ?
                    OR t.Email LIKE ?
                )
            ";

            $searchValue = '%' . $search . '%';

            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;
            $params[] = $searchValue;

            $types .= 'ssss';
        }


        // ============================================================
        // REVIEW STATUS FILTER
        // ============================================================

        if ($reviewStatus !== '') {

            $sql .= "
                AND w.review_status = ?
            ";

            $params[] = $reviewStatus;

            $types .= 's';
        }


        // ============================================================
        // LATEST FIRST
        // ============================================================

        $sql .= "
            ORDER BY w.created_at DESC
        ";


        // ============================================================
        // PREPARE
        // ============================================================

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            throw new Exception(
                'Admin list prepare failed: '
                . $conn->error
            );
        }


        // ============================================================
        // BIND
        // ============================================================

        if ($types !== '') {

            $stmt->bind_param(
                $types,
                ...$params
            );
        }


        // ============================================================
        // EXECUTE
        // ============================================================

        if (!$stmt->execute()) {

            throw new Exception(
                'Admin list execute failed: '
                . $stmt->error
            );
        }


        return self::fetchAll($stmt);
    }


    // ================================================================
    // ADMIN - PENDING COUNT
    // ================================================================

    public static function adminPendingCount(): int
    {
        $conn = db();

        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total

            FROM employee_work_uploads

            WHERE review_status = 'Pending'
        ");


        if (!$stmt) {

            throw new Exception(
                'Pending count prepare failed: '
                . $conn->error
            );
        }


        if (!$stmt->execute()) {

            throw new Exception(
                'Pending count execute failed: '
                . $stmt->error
            );
        }


        $row = self::fetchOne($stmt);

        return (int)($row['total'] ?? 0);
    }


    // ================================================================
    // ADMIN - GET SINGLE UPLOAD
    // ================================================================

    public static function adminGet(
        int $id
    ): ?array {

        $conn = db();

        $stmt = $conn->prepare("
            SELECT

                w.*,

                /* Trainer */

                t.id AS trainer_id,

                t.user_id AS trainer_user_id,

                t.Name AS employee_name,

                t.Email AS employee_email,

                t.StaffCode,

                /* Category */

                tc.name AS category,

                tc.color_code

            FROM employee_work_uploads w

            LEFT JOIN trainers t
                ON t.user_id = w.employee_id

            LEFT JOIN task_categories tc
                ON tc.id = w.category_id

            WHERE w.id = ?

            LIMIT 1
        ");


        if (!$stmt) {

            throw new Exception(
                'Admin get prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'i',
            $id
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Admin get execute failed: '
                . $stmt->error
            );
        }


        $data = self::fetchOne($stmt);


        if (!$data) {
            return null;
        }


        // ============================================================
        // ATTACHMENTS
        // ============================================================

        $fileStmt = $conn->prepare("
            SELECT

                id,

                upload_id,

                file_name,

                file_path,

                created_at

            FROM employee_work_upload_files

            WHERE upload_id = ?

            ORDER BY id ASC
        ");


        if (!$fileStmt) {

            throw new Exception(
                'Attachment query prepare failed: '
                . $conn->error
            );
        }


        $fileStmt->bind_param(
            'i',
            $id
        );


        if (!$fileStmt->execute()) {

            throw new Exception(
                'Attachment query execute failed: '
                . $fileStmt->error
            );
        }


        $data['files'] = self::fetchAll($fileStmt);


        return $data;
    }


    // ================================================================
    // ADMIN - APPROVE
    // ================================================================

    public static function approveUpload(
        int $id,
        ?int $adminId
    ): bool {

        return self::adminReview(
            $id,
            'Approved',
            'Approved — great work.',
            $adminId
        );
    }


    // ================================================================
    // ADMIN - REVIEW
    //
    // Approved       -> Completed
    // Needs Revision -> Submitted
    // Rejected       -> Submitted
    // ================================================================

    public static function adminReview(
        int $id,
        string $status,
        string $comment,
        ?int $adminId
    ): bool {

        $conn = db();


        // ============================================================
        // VALIDATE REVIEW STATUS
        // ============================================================

        $allowedStatuses = [
            'Approved',
            'Needs Revision',
            'Rejected'
        ];


        if (!in_array(
            $status,
            $allowedStatuses,
            true
        )) {

            throw new Exception(
                'Invalid review status.'
            );
        }


        // ============================================================
        // GET UPLOAD + TRAINER
        //
        // IMPORTANT:
        // employee_id = trainers.user_id
        // ============================================================

        $stmt = $conn->prepare("
            SELECT

                w.id,

                w.title,

                w.employee_id,

                t.Name,

                t.Email,

                t.StaffCode

            FROM employee_work_uploads w

            LEFT JOIN trainers t
                ON t.user_id = w.employee_id

            WHERE w.id = ?

            LIMIT 1
        ");


        if (!$stmt) {

            throw new Exception(
                'Review lookup prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'i',
            $id
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Review lookup execute failed: '
                . $stmt->error
            );
        }


        $row = self::fetchOne($stmt);


        if (!$row) {

            return false;
        }


        // ============================================================
        // MAIN STATUS
        // ============================================================

        if ($status === 'Approved') {

            $overallStatus = 'Completed';

        } else {

            $overallStatus = 'Submitted';
        }


        // ============================================================
        // UPDATE REVIEW
        // ============================================================

        $update = $conn->prepare("
            UPDATE employee_work_uploads

            SET

                status = ?,

                review_status = ?,

                review_comment = ?,

                reviewed_by = ?,

                reviewed_on = NOW()

            WHERE id = ?
        ");


        if (!$update) {

            throw new Exception(
                'Review update prepare failed: '
                . $conn->error
            );
        }


        /*
         * reviewed_by can be NULL.
         *
         * Convert missing admin id to NULL.
         */

        if ($adminId === null) {

            $update->bind_param(
                'sssii',
                $overallStatus,
                $status,
                $comment,
                $adminId,
                $id
            );

        } else {

            $update->bind_param(
                'sssii',
                $overallStatus,
                $status,
                $comment,
                $adminId,
                $id
            );
        }


        if (!$update->execute()) {

            throw new Exception(
                'Review update failed: '
                . $update->error
            );
        }


        // ============================================================
        // EMAIL NOTIFICATION
        // ============================================================

        try {

            if (!empty($row['Email'])) {

                if ($status === 'Approved') {

                    Mailer::uploadApproved(
                        $row['Email'],
                        $row['Name'] ?? 'Employee',
                        $row['title']
                    );

                } else {

                    Mailer::uploadReview(
                        $row['Email'],
                        $row['Name'] ?? 'Employee',
                        $row['title'],
                        $status,
                        $comment
                    );
                }
            }

        } catch (Throwable $mailError) {

            /*
             * Do NOT make database review fail because
             * email failed.
             */

            error_log(
                'Work upload review email failed: '
                . $mailError->getMessage()
            );
        }


        return true;
    }


    // ================================================================
    // ADMIN - DELETE UPLOAD
    // ================================================================

    public static function deleteUpload(
        int $id
    ): void {

        $conn = db();


        // ============================================================
        // GET FILES FIRST
        // ============================================================

        $fileStmt = $conn->prepare("
            SELECT file_path

            FROM employee_work_upload_files

            WHERE upload_id = ?
        ");


        if ($fileStmt) {

            $fileStmt->bind_param(
                'i',
                $id
            );

            $fileStmt->execute();

            $files = self::fetchAll($fileStmt);


            // Delete physical files
            foreach ($files as $file) {

                $path = __DIR__
                    . '/../'
                    . ltrim(
                        $file['file_path'],
                        '/\\'
                    );

                if (
                    is_file($path)
                    && file_exists($path)
                ) {

                    @unlink($path);
                }
            }
        }


        // ============================================================
        // DELETE FILE RECORDS
        // ============================================================

        $stmt = $conn->prepare("
            DELETE FROM employee_work_upload_files

            WHERE upload_id = ?
        ");


        if (!$stmt) {

            throw new Exception(
                'Delete files prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'i',
            $id
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Delete files failed: '
                . $stmt->error
            );
        }


        // ============================================================
        // DELETE MAIN UPLOAD
        // ============================================================

        $stmt = $conn->prepare("
            DELETE FROM employee_work_uploads

            WHERE id = ?
        ");


        if (!$stmt) {

            throw new Exception(
                'Delete upload prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'i',
            $id
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Delete upload failed: '
                . $stmt->error
            );
        }
    }


    // ================================================================
    // EMPLOYEE - MY UPLOADS
    // ================================================================

    public static function myUploads(
        int $employeeId
    ): array {

        $conn = db();

        $stmt = $conn->prepare("
            SELECT

                id,

                title,

                description,

                category_id,

                hours_worked,

                github_link,

                live_url,

                drive_link,

                next_plan,

                status,

                review_status,

                review_comment,

                reviewed_by,

                reviewed_on,

                created_at,

                updated_at

            FROM employee_work_uploads

            WHERE employee_id = ?

            ORDER BY created_at DESC
        ");


        if (!$stmt) {

            throw new Exception(
                'My uploads prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'i',
            $employeeId
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'My uploads execute failed: '
                . $stmt->error
            );
        }


        return self::fetchAll($stmt);
    }


    // ================================================================
    // EMPLOYEE - GET ONE SUBMISSION
    // ================================================================

    public static function employeeGet(
        int $id,
        int $employeeId
    ): ?array {

        $conn = db();

        $stmt = $conn->prepare("
            SELECT

                w.*,

                tc.name AS category,

                tc.color_code

            FROM employee_work_uploads w

            LEFT JOIN task_categories tc
                ON tc.id = w.category_id

            WHERE

                w.id = ?

                AND w.employee_id = ?

            LIMIT 1
        ");


        if (!$stmt) {

            throw new Exception(
                'Employee get prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'ii',
            $id,
            $employeeId
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Employee get execute failed: '
                . $stmt->error
            );
        }


        $data = self::fetchOne($stmt);


        if (!$data) {

            return null;
        }


        // ============================================================
        // FILES
        // ============================================================

        $fileStmt = $conn->prepare("
            SELECT

                id,

                upload_id,

                file_name,

                file_path,

                created_at

            FROM employee_work_upload_files

            WHERE upload_id = ?

            ORDER BY id ASC
        ");


        if (!$fileStmt) {

            throw new Exception(
                'Employee files prepare failed: '
                . $conn->error
            );
        }


        $fileStmt->bind_param(
            'i',
            $id
        );


        if (!$fileStmt->execute()) {

            throw new Exception(
                'Employee files execute failed: '
                . $fileStmt->error
            );
        }


        $data['files'] = self::fetchAll($fileStmt);


        return $data;
    }


    // ================================================================
    // EMPLOYEE - CREATE NEW UPLOAD
    // ================================================================

    public static function createUpload(
        int $employeeId,
        array $data,
        array $files
    ): int {

        $conn = db();


        // ============================================================
        // CATEGORY
        // ============================================================

        $categoryId =
            !empty($data['category_id'])
            ? (int)$data['category_id']
            : null;


        // ============================================================
        // INSERT
        // ============================================================

        $stmt = $conn->prepare("
            INSERT INTO employee_work_uploads
            (
                employee_id,
                title,
                description,
                category_id,
                hours_worked,
                github_link,
                live_url,
                drive_link,
                next_plan
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ");


        if (!$stmt) {

            throw new Exception(
                'Create upload prepare failed: '
                . $conn->error
            );
        }


        $hoursWorked =
            (
                isset($data['hours_worked'])
                && $data['hours_worked'] !== ''
            )
            ? (float)$data['hours_worked']
            : null;


        $title =
            (string)($data['title'] ?? '');


        $description =
            (string)($data['description'] ?? '');


        $github =
            (string)($data['github_link'] ?? '');


        $live =
            (string)($data['live_url'] ?? '');


        $drive =
            (string)($data['drive_link'] ?? '');


        $nextPlan =
            (string)($data['next_plan'] ?? '');


        $stmt->bind_param(
            'issidssss',
            $employeeId,
            $title,
            $description,
            $categoryId,
            $hoursWorked,
            $github,
            $live,
            $drive,
            $nextPlan
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Create upload failed: '
                . $stmt->error
            );
        }


        $uploadId = $stmt->insert_id;


        // ============================================================
        // SAVE ATTACHMENTS
        // ============================================================

        self::saveAttachments(
            $conn,
            $uploadId,
            $files
        );


        // ============================================================
        // GET TRAINER NAME
        // ============================================================

        $empName = 'Employee';


        $nameStmt = $conn->prepare("
            SELECT Name

            FROM trainers

            WHERE user_id = ?

            LIMIT 1
        ");


        if ($nameStmt) {

            $nameStmt->bind_param(
                'i',
                $employeeId
            );


            if ($nameStmt->execute()) {

                $row =
                    self::fetchOne($nameStmt);

                if (
                    $row
                    && !empty($row['Name'])
                ) {

                    $empName =
                        $row['Name'];
                }
            }


            $nameStmt->close();
        }


        // ============================================================
        // NOTIFY ADMIN
        // ============================================================

        try {

            Mailer::uploadNotifyAdmin(
                $empName,
                $title
            );

        } catch (Throwable $mailError) {

            error_log(
                'Work upload admin notification failed: '
                . $mailError->getMessage()
            );
        }


        return $uploadId;
    }


    // ================================================================
    // EMPLOYEE - UPDATE / RESUBMIT
    //
    // ONLY WHEN REVIEW STATUS = Needs Revision
    // ================================================================

    public static function updateSubmission(
        int $id,
        int $employeeId,
        array $data,
        array $files
    ): bool {

        $conn = db();


        // ============================================================
        // VERIFY OWNERSHIP
        // ============================================================

        $check = $conn->prepare("
            SELECT

                id,

                title

            FROM employee_work_uploads

            WHERE

                id = ?

                AND employee_id = ?

                AND review_status = 'Needs Revision'

            LIMIT 1
        ");


        if (!$check) {

            throw new Exception(
                'Revision check prepare failed: '
                . $conn->error
            );
        }


        $check->bind_param(
            'ii',
            $id,
            $employeeId
        );


        if (!$check->execute()) {

            throw new Exception(
                'Revision check execute failed: '
                . $check->error
            );
        }


        $existing =
            self::fetchOne($check);


        if (!$existing) {

            return false;
        }


        // ============================================================
        // CATEGORY
        // ============================================================

        $categoryId =
            !empty($data['category_id'])
            ? (int)$data['category_id']
            : null;


        $hoursWorked =
            (
                isset($data['hours_worked'])
                && $data['hours_worked'] !== ''
            )
            ? (float)$data['hours_worked']
            : null;


        $title =
            (string)($data['title'] ?? '');


        $description =
            (string)($data['description'] ?? '');


        $github =
            (string)($data['github_link'] ?? '');


        $live =
            (string)($data['live_url'] ?? '');


        $drive =
            (string)($data['drive_link'] ?? '');


        $nextPlan =
            (string)($data['next_plan'] ?? '');


        // ============================================================
        // UPDATE
        // ============================================================

        $stmt = $conn->prepare("
            UPDATE employee_work_uploads

            SET

                title = ?,

                description = ?,

                category_id = ?,

                hours_worked = ?,

                github_link = ?,

                live_url = ?,

                drive_link = ?,

                next_plan = ?,

                status = 'Submitted',

                review_status = 'Pending',

                review_comment = NULL,

                reviewed_by = NULL,

                reviewed_on = NULL

            WHERE

                id = ?

                AND employee_id = ?
        ");


        if (!$stmt) {

            throw new Exception(
                'Submission update prepare failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'ssidssssii',
            $title,
            $description,
            $categoryId,
            $hoursWorked,
            $github,
            $live,
            $drive,
            $nextPlan,
            $id,
            $employeeId
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Submission update failed: '
                . $stmt->error
            );
        }


        // ============================================================
        // ATTACHMENTS
        //
        // If new files are uploaded:
        // remove old attachment DB records + physical files,
        // then save the new files.
        //
        // This prevents duplicate images after revision.
        // ============================================================

        if (!empty($files['name'][0] ?? null)) {

            self::deleteAttachments(
                $conn,
                $id
            );


            self::saveAttachments(
                $conn,
                $id,
                $files
            );
        }


        // ============================================================
        // GET TRAINER
        // ============================================================

        $empName = 'Employee';


        $nameStmt = $conn->prepare("
            SELECT Name

            FROM trainers

            WHERE user_id = ?

            LIMIT 1
        ");


        if ($nameStmt) {

            $nameStmt->bind_param(
                'i',
                $employeeId
            );


            if ($nameStmt->execute()) {

                $row =
                    self::fetchOne($nameStmt);

                if (
                    $row
                    && !empty($row['Name'])
                ) {

                    $empName =
                        $row['Name'];
                }
            }


            $nameStmt->close();
        }


        // ============================================================
        // NOTIFY ADMIN
        // ============================================================

        try {

            Mailer::uploadNotifyAdmin(
                $empName,
                $title
            );

        } catch (Throwable $mailError) {

            error_log(
                'Revision admin notification failed: '
                . $mailError->getMessage()
            );
        }


        return true;
    }


    // ================================================================
    // SAVE ATTACHMENTS
    // ================================================================

    private static function saveAttachments(
        $conn,
        int $uploadId,
        array $files
    ): void {

        if (
            empty($files)
            || empty($files['name'])
            || !is_array($files['name'])
        ) {
            return;
        }


        $dir =
            __DIR__
            . '/../uploads/work_uploads/';


        if (!is_dir($dir)) {

            if (!mkdir(
                $dir,
                0755,
                true
            )) {

                throw new Exception(
                    'Unable to create upload directory.'
                );
            }
        }


        $stmt = $conn->prepare("
            INSERT INTO employee_work_upload_files
            (
                upload_id,
                file_name,
                file_path
            )

            VALUES
            (
                ?,
                ?,
                ?
            )
        ");


        if (!$stmt) {

            throw new Exception(
                'Attachment insert prepare failed: '
                . $conn->error
            );
        }


        $count =
            count($files['name']);


        for (
            $i = 0;
            $i < $count;
            $i++
        ) {

            if (
                ($files['error'][$i]
                    ?? UPLOAD_ERR_NO_FILE)
                !== UPLOAD_ERR_OK
            ) {
                continue;
            }


            if (
                empty($files['tmp_name'][$i])
                || !is_uploaded_file(
                    $files['tmp_name'][$i]
                )
            ) {
                continue;
            }


            $originalName =
                basename(
                    $files['name'][$i]
                );


            $safeOriginalName =
                preg_replace(
                    '/[^A-Za-z0-9._-]/',
                    '_',
                    $originalName
                );


            $safeName =
                date('YmdHis')
                . '_'
                . uniqid()
                . '_'
                . $safeOriginalName;


            $destination =
                $dir
                . $safeName;


            if (
                !move_uploaded_file(
                    $files['tmp_name'][$i],
                    $destination
                )
            ) {

                continue;
            }


            $relativePath =
                'uploads/work_uploads/'
                . $safeName;


            $stmt->bind_param(
                'iss',
                $uploadId,
                $originalName,
                $relativePath
            );


            if (!$stmt->execute()) {

                /*
                 * If DB insert fails,
                 * remove physical file.
                 */

                if (is_file($destination)) {
                    @unlink($destination);
                }


                throw new Exception(
                    'Attachment insert failed: '
                    . $stmt->error
                );
            }
        }


        $stmt->close();
    }


    // ================================================================
    // DELETE ATTACHMENTS
    // ================================================================

    private static function deleteAttachments(
        $conn,
        int $uploadId
    ): void {

        // ------------------------------------------------------------
        // GET FILE PATHS
        // ------------------------------------------------------------

        $stmt = $conn->prepare("
            SELECT file_path

            FROM employee_work_upload_files

            WHERE upload_id = ?
        ");


        if (!$stmt) {

            throw new Exception(
                'Attachment delete lookup failed: '
                . $conn->error
            );
        }


        $stmt->bind_param(
            'i',
            $uploadId
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Attachment delete lookup execute failed: '
                . $stmt->error
            );
        }


        $files =
            self::fetchAll($stmt);


        // ------------------------------------------------------------
        // DELETE PHYSICAL FILES
        // ------------------------------------------------------------

        foreach ($files as $file) {

            $relativePath =
                ltrim(
                    $file['file_path'] ?? '',
                    '/\\'
                );


            if ($relativePath === '') {
                continue;
            }


            $absolutePath =
                __DIR__
                . '/../'
                . $relativePath;


            if (
                is_file($absolutePath)
                && file_exists($absolutePath)
            ) {

                @unlink($absolutePath);
            }
        }


        // ------------------------------------------------------------
        // DELETE DATABASE RECORDS
        // ------------------------------------------------------------

        $delete = $conn->prepare("
            DELETE FROM employee_work_upload_files

            WHERE upload_id = ?
        ");


        if (!$delete) {

            throw new Exception(
                'Attachment delete prepare failed: '
                . $conn->error
            );
        }


        $delete->bind_param(
            'i',
            $uploadId
        );


        if (!$delete->execute()) {

            throw new Exception(
                'Attachment delete failed: '
                . $delete->error
            );
        }
    }
}