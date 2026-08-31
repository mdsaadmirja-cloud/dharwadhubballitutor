<?php
// lms/model/Exam.php
require_once "../../DB Operations/dbconnection.php";

class Exam
{
    private $db;

    public function __construct()
    {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }

    // Create a new exam
    public function create($data)
    {
        $title = $this->db->real_escape_string($data['title']);
        $code = $this->db->real_escape_string($data['code']);
        $description = $this->db->real_escape_string($data['description']);
        $duration = (int)$data['duration'];
        $total_marks = (int)$data['total_marks'];
        $pass_percentage = (float)$data['pass_percentage'];

        $negative_marking = isset($data['negative_marking']) ? 1 : 0;

        $start_time = $this->db->real_escape_string($data['start_time']);
        $end_time = $this->db->real_escape_string($data['end_time']);

        $randomize_questions = isset($data['randomize_questions']) ? 1 : 0;
        $randomize_choices = isset($data['randomize_choices']) ? 1 : 0;
        $show_results = isset($data['show_results']) ? 1 : 0;
        $show_explanations = isset($data['show_explanations']) ? 1 : 0;
        $allow_re_exam = isset($data['allow_re_exam']) ? 1 : 0;

        /*
         * Keep the old certificate_template field untouched
         * for compatibility with existing records.
         */
        $certificate_template = $this->db->real_escape_string(
            $data['certificate_template'] ?? ''
        );

        /*
         * New certificate template relationship.
         *
         * If no template is selected, NULL will be stored.
         */
        $certificate_template_id = (
            isset($data['certificate_template_id']) &&
            $data['certificate_template_id'] !== ''
        )
            ? (int)$data['certificate_template_id']
            : null;

        $created_by = (int)$data['created_by'];

        $certificateTemplateIdValue = (
            $certificate_template_id === null
        )
            ? "NULL"
            : $certificate_template_id;

        $sql = "INSERT INTO exams (
                    title,
                    code,
                    description,
                    duration,
                    total_marks,
                    pass_percentage,
                    negative_marking,
                    start_time,
                    end_time,
                    randomize_questions,
                    randomize_choices,
                    show_results,
                    show_explanations,
                    allow_re_exam,
                    certificate_template,
                    certificate_template_id,
                    created_by
                ) VALUES (
                    '$title',
                    '$code',
                    '$description',
                    $duration,
                    $total_marks,
                    $pass_percentage,
                    $negative_marking,
                    '$start_time',
                    '$end_time',
                    $randomize_questions,
                    $randomize_choices,
                    $show_results,
                    $show_explanations,
                    $allow_re_exam,
                    '$certificate_template',
                    $certificateTemplateIdValue,
                    $created_by
                )";

        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }

        error_log("Exam create error: " . $this->db->error);
        error_log("Exam create SQL: " . $sql);

        return false;
    }

    // Get exam by ID
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM exams WHERE id = $id";

        error_log($sql);

        $result = $this->db->query($sql);

        return $result ? $result->fetch_assoc() : null;
    }

    // Get exam by code
    public function getByCode($code)
    {
        $code = $this->db->real_escape_string($code);

        $sql = "SELECT * FROM exams WHERE code = '$code'";

        $result = $this->db->query($sql);

        return $result ? $result->fetch_assoc() : null;
    }

    // Get all exams
    public function getAll($created_by = null)
    {
        $sql = "SELECT e.*, u.name as created_by_name
                FROM exams e
                LEFT JOIN users u ON e.created_by = u.id";

        if ($created_by) {
            $created_by = (int)$created_by;

            $sql .= " WHERE e.created_by = $created_by";
        }

        $sql .= " ORDER BY e.created_at DESC";

        error_log($sql);

        $result = $this->db->query($sql);

        $exams = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $exams[] = $row;
            }
        }

        return $exams;
    }

    // Update exam
    public function update($id, $data)
    {
        $id = (int)$id;

        $title = $this->db->real_escape_string($data['title']);
        $code = $this->db->real_escape_string($data['code']);
        $description = $this->db->real_escape_string($data['description']);

        $duration = (int)$data['duration'];
        $total_marks = (int)$data['total_marks'];
        $pass_percentage = (float)$data['pass_percentage'];

        $negative_marking = isset($data['negative_marking']) ? 1 : 0;

        $start_time = $this->db->real_escape_string($data['start_time']);
        $end_time = $this->db->real_escape_string($data['end_time']);

        $randomize_questions = isset($data['randomize_questions']) ? 1 : 0;
        $randomize_choices = isset($data['randomize_choices']) ? 1 : 0;
        $show_results = isset($data['show_results']) ? 1 : 0;
        $show_explanations = isset($data['show_explanations']) ? 1 : 0;
        $allow_re_exam = isset($data['allow_re_exam']) ? 1 : 0;

        $status = $this->db->real_escape_string($data['status']);

        /*
         * Certificate template relationship.
         */
        $certificate_template_id = (
            isset($data['certificate_template_id']) &&
            $data['certificate_template_id'] !== ''
        )
            ? (int)$data['certificate_template_id']
            : null;

        $certificateTemplateIdValue = (
            $certificate_template_id === null
        )
            ? "NULL"
            : $certificate_template_id;

        $sql = "UPDATE exams SET
                    title = '$title',
                    code = '$code',
                    description = '$description',
                    duration = $duration,
                    total_marks = $total_marks,
                    pass_percentage = $pass_percentage,
                    negative_marking = $negative_marking,
                    start_time = '$start_time',
                    end_time = '$end_time',
                    randomize_questions = $randomize_questions,
                    randomize_choices = $randomize_choices,
                    show_results = $show_results,
                    show_explanations = $show_explanations,
                    allow_re_exam = $allow_re_exam,
                    certificate_template_id = $certificateTemplateIdValue,
                    status = '$status',
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = $id";

        $result = $this->db->query($sql);

        if (!$result) {
            error_log("Exam update error: " . $this->db->error);
            error_log("Exam update SQL: " . $sql);
        }

        return $result;
    }

    // Delete exam
    public function delete($id)
    {
        $id = (int)$id;

        $sql = "DELETE FROM exams WHERE id = $id";

        return $this->db->query($sql);
    }

    // Get exams assigned to a student
    public function getAssignedExams($user_id)
    {
        $user_id = (int)$user_id;

        $sql = "SELECT DISTINCT e.*, ea.assigned_at
                FROM exams e
                JOIN exam_assignments ea
                    ON e.id = ea.exam_id
                JOIN group_members gm
                    ON ea.group_id = gm.group_id
                WHERE gm.user_id = $user_id
                AND e.status = 'published'
                AND e.start_time <= CONVERT_TZ(now(), '-07:00', '+05:30')
                AND e.end_time >= CONVERT_TZ(now(), '-07:00', '+05:30')
                ORDER BY e.start_time ASC";

        error_log($sql);

        $result = $this->db->query($sql);

        $exams = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $exams[] = $row;
            }
        }

        return $exams;
    }

    // Check if exam is accessible to student
    public function isAccessibleToStudent($exam_id, $user_id)
    {
        $exam_id = (int)$exam_id;
        $user_id = (int)$user_id;

        $sql = "SELECT COUNT(*) as count
                FROM exam_assignments ea
                JOIN group_members gm
                    ON ea.group_id = gm.group_id
                WHERE ea.exam_id = $exam_id
                AND gm.user_id = $user_id";

        $result = $this->db->query($sql);

        if (!$result) {
            return false;
        }

        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    // Get exam statistics
    public function getStatistics($exam_id)
    {
        $exam_id = (int)$exam_id;

        $sql = "SELECT
                    COUNT(DISTINCT ea.id) as total_attempts,

                    COUNT(
                        DISTINCT CASE
                            WHEN ea.status = 'submitted'
                            THEN ea.id
                        END
                    ) as completed_attempts,

                    AVG(
                        CASE
                            WHEN ea.status = 'submitted'
                            THEN ea.percentage
                        END
                    ) as average_percentage,

                    COUNT(
                        DISTINCT CASE
                            WHEN ea.status = 'submitted'
                            AND ea.percentage >= e.pass_percentage
                            THEN ea.id
                        END
                    ) as passed_attempts

                FROM exam_attempts ea

                JOIN exams e
                    ON ea.exam_id = e.id

                WHERE ea.exam_id = $exam_id";

        $result = $this->db->query($sql);

        return $result ? $result->fetch_assoc() : null;
    }
}