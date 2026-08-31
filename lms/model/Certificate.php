<?php
// lms/model/Certificate.php
require_once "../../DB Operations/dbconnection.php";

class Certificate
{
    private $db;

    public function __construct()
    {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }

    // Generate certificate for exam attempt
    public function generateCertificate($attempt_id)
    {
        $attempt_id = (int)$attempt_id;

        // Get attempt details with exam and user info
        $sql = "SELECT ea.*, 
               e.title as exam_title, 
               e.code as exam_code, 
               e.pass_percentage,
               e.certificate_template,
               e.certificate_template_id,
               ct.template_file,
               u.name as student_name, 
               u.email as student_email
        FROM exam_attempts ea 
        JOIN exams e ON ea.exam_id = e.id 
        LEFT JOIN certificate_templates ct 
            ON e.certificate_template_id = ct.id
        JOIN users u ON ea.user_id = u.id 
        WHERE ea.id = $attempt_id 
        AND ea.status IN ('submitted', 'auto_submitted')";
        error_log($sql);
        $result = $this->db->query($sql);
        $attempt = $result->fetch_assoc();

        if (!$attempt) {
            return false;
        }

        // Check if student passed
        $passed = $attempt['percentage'] >= $attempt['pass_percentage'];

        // Generate certificate data
        $certificate_data = [
            'attempt_id' => $attempt_id,
            'student_name' => $attempt['student_name'],
            'student_email' => $attempt['student_email'],
            'exam_title' => $attempt['exam_title'],
            'exam_code' => $attempt['exam_code'],

            // Selected certificate template
            'certificate_template_id' => $attempt['certificate_template_id'],
            'template_file' => $attempt['template_file'],

            // Keep existing field for backward compatibility
            'certificate_template' => $attempt['certificate_template'],

            'score' => $attempt['obtained_marks'],
            'total_marks' => $attempt['total_marks'],
            'percentage' => $attempt['percentage'],
            'passed' => $passed,
            'completion_date' => $attempt['submitted_at'],
            'certificate_id' => $this->generateCertificateId(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Save certificate to database
        $this->saveCertificate($certificate_data);

        return $certificate_data;
    }

    // Save certificate to database
    private function saveCertificate($data)
    {
        $attempt_id = (int)$data['attempt_id'];
        $student_name = $this->db->real_escape_string($data['student_name']);
        $student_email = $this->db->real_escape_string($data['student_email']);
        $exam_title = $this->db->real_escape_string($data['exam_title']);
        $exam_code = $this->db->real_escape_string($data['exam_code']);
        $score = (int)$data['score'];
        $total_marks = (int)$data['total_marks'];
        $percentage = (float)$data['percentage'];
        $passed = $data['passed'] ? 1 : 0;
        $completion_date = $this->db->real_escape_string($data['completion_date']);
        $certificate_id = $this->db->real_escape_string($data['certificate_id']);
        $created_at = $this->db->real_escape_string($data['created_at']);

        $sql = "INSERT INTO certificates (attempt_id, student_name, student_email, exam_title, exam_code, 
                score, total_marks, percentage, passed, completion_date, certificate_id, created_at) 
                VALUES ($attempt_id, '$student_name', '$student_email', '$exam_title', '$exam_code', 
                $score, $total_marks, $percentage, $passed, '$completion_date', '$certificate_id', '$created_at')
                ON DUPLICATE KEY UPDATE
                student_name = '$student_name',
                student_email = '$student_email',
                exam_title = '$exam_title',
                exam_code = '$exam_code',
                score = $score,
                total_marks = $total_marks,
                percentage = $percentage,
                passed = $passed,
                completion_date = '$completion_date',
                updated_at = CURRENT_TIMESTAMP";

        return $this->db->query($sql);
    }

    // Generate unique certificate ID
    private function generateCertificateId()
    {
        return 'CERT-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }

    // Get certificate by attempt ID
    public function getByAttemptId($attempt_id)
    {
        $attempt_id = (int)$attempt_id;

        $sql = "SELECT c.*,
               e.certificate_template,
               e.certificate_template_id,
               ct.template_file
        FROM certificates c
        JOIN exam_attempts ea
            ON c.attempt_id = ea.id
        JOIN exams e
            ON ea.exam_id = e.id
        LEFT JOIN certificate_templates ct
            ON e.certificate_template_id = ct.id
        WHERE c.attempt_id = $attempt_id";

        $result = $this->db->query($sql);

        return $result ? $result->fetch_assoc() : null;
    }

    // Get certificate by certificate ID
    public function getByCertificateId($certificate_id)
    {
        $certificate_id = $this->db->real_escape_string($certificate_id);
        $sql = "SELECT * FROM certificates WHERE certificate_id = '$certificate_id'";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // Get certificates by user
    public function getByUserId($user_id)
    {
        $user_id = (int)$user_id;
        $sql = "SELECT c.*, ea.exam_id, ea.started_at, ea.submitted_at
                FROM certificates c 
                JOIN exam_attempts ea ON c.attempt_id = ea.id 
                WHERE ea.user_id = $user_id 
                ORDER BY c.created_at DESC";

        $result = $this->db->query($sql);
        $certificates = [];
        while ($row = $result->fetch_assoc()) {
            $certificates[] = $row;
        }
        return $certificates;
    }

    // Verify certificate
    public function verifyCertificate($certificate_id)
    {
        $certificate = $this->getByCertificateId($certificate_id);

        if (!$certificate) {
            return ['valid' => false, 'message' => 'Certificate not found'];
        }

        return [
            'valid' => true,
            'certificate' => $certificate,
            'message' => 'Certificate is valid'
        ];
    }
}
