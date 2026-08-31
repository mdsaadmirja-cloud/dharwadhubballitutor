<?php
// lms/controller/ExamController.php
session_start();
require_once '../model/Exam.php';
require_once '../model/Question.php';
require_once '../model/StudentGroup.php';
require_once '../model/ExamAttempt.php';

class ExamController
{
    private $examModel;
    private $questionModel;
    private $groupModel;
    private $attemptModel;

    public function __construct()
    {
        $this->examModel = new Exam();
        $this->questionModel = new Question();
        $this->groupModel = new StudentGroup();
        $this->attemptModel = new ExamAttempt();
    }

    // Create new exam
    public function createExam()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $certificateTemplateId = isset($_POST['certificate_template_id'])
            && $_POST['certificate_template_id'] !== ''
            ? (int)$_POST['certificate_template_id']
            : null;

        $data = [
            'title' => $_POST['title'],
            'code' => $_POST['code'],
            'description' => $_POST['description'],
            'duration' => $_POST['duration'],
            'total_marks' => $_POST['total_marks'],
            'pass_percentage' => $_POST['pass_percentage'],
            'negative_marking' => isset($_POST['negative_marking']),
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'randomize_questions' => isset($_POST['randomize_questions']),
            'randomize_choices' => isset($_POST['randomize_choices']),
            'show_results' => isset($_POST['show_results']),
            'show_explanations' => isset($_POST['show_explanations']),
            'allow_re_exam' => isset($_POST['allow_re_exam']),

            // Certificate template selected during exam creation
            'certificate_template_id' => $certificateTemplateId,

            'created_by' => $_SESSION['user_id']
        ];

        $exam_id = $this->examModel->create($data);

        if ($exam_id) {
            return [
                'success' => true,
                'message' => 'Exam created successfully',
                'exam_id' => $exam_id
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create exam'
        ];
    }

    // Update exam
    public function updateExam()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_POST['exam_id'];
        $data = [
            'title' => $_POST['title'],
            'code' => $_POST['code'],
            'description' => $_POST['description'],
            'duration' => $_POST['duration'],
            'total_marks' => $_POST['total_marks'],
            'pass_percentage' => $_POST['pass_percentage'],
            'negative_marking' => isset($_POST['negative_marking']),
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'randomize_questions' => isset($_POST['randomize_questions']),
            'randomize_choices' => isset($_POST['randomize_choices']),
            'show_results' => isset($_POST['show_results']),
            'show_explanations' => isset($_POST['show_explanations']),
            'allow_re_exam' => isset($_POST['allow_re_exam']),
            'certificate_template_id' => isset($_POST['certificate_template_id'])
                && $_POST['certificate_template_id'] !== ''
                ? (int)$_POST['certificate_template_id']
                : null,
            'status' => $_POST['status']
        ];

        if ($this->examModel->update($exam_id, $data)) {
            return ['success' => true, 'message' => 'Exam updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update exam'];
        }
    }

    // Delete exam
    public function deleteExam()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_POST['exam_id'];

        if ($this->examModel->delete($exam_id)) {
            return ['success' => true, 'message' => 'Exam deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete exam'];
        }
    }

    // Get exam details
    public function getExam()
    {
        $exam_id = (int)$_GET['exam_id'];
        $exam = $this->examModel->getById($exam_id);

        if ($exam) {
            return ['success' => true, 'exam' => $exam];
        } else {
            return ['success' => false, 'message' => 'Exam not found'];
        }
    }

    // Get all exams
    public function getAllExams()
    {
        $created_by = isset($_SESSION['user']) && $_SESSION['role'] === 'admin' ?
            $_SESSION['user_id'] : null;

        $exams = $this->examModel->getAll($created_by);
        return ['success' => true, 'exams' => $exams];
    }

    // Assign exam to groups
    public function assignExamToGroups()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_POST['exam_id'];
        $group_ids = $_POST['group_ids'];
        $assigned_by = $_SESSION['user_id'];

        $success_count = 0;
        $errors = [];

        foreach ($group_ids as $group_id) {
            if ($this->groupModel->assignExam($exam_id, $group_id, $assigned_by)) {
                $success_count++;
            } else {
                $errors[] = "Failed to assign exam to group $group_id";
            }
        }

        if ($success_count > 0) {
            return ['success' => true, 'message' => "Exam assigned to $success_count groups", 'errors' => $errors];
        } else {
            return ['success' => false, 'message' => 'Failed to assign exam to any group', 'errors' => $errors];
        }
    }

    // Get exam statistics
    public function getExamStatistics()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_GET['exam_id'];
        $stats = $this->examModel->getStatistics($exam_id);

        if ($stats) {
            return ['success' => true, 'statistics' => $stats];
        } else {
            return ['success' => false, 'message' => 'Failed to get statistics'];
        }
    }

    // Get exam attempts
    public function getExamAttempts()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_GET['exam_id'];
        $attempts = $this->attemptModel->getByExamId($exam_id);

        return ['success' => true, 'attempts' => $attempts];
    }

    // Export exam results
    public function exportResults()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_GET['exam_id'];
        $format = $_GET['format'] ?? 'excel';

        $exam = $this->examModel->getById($exam_id);
        $attempts = $this->attemptModel->getByExamId($exam_id);

        if ($format === 'pdf') {
            return $this->exportResultsPDF($exam, $attempts);
        } else {
            return $this->exportResultsExcel($exam, $attempts);
        }
    }

    // Export results as Excel
    private function exportResultsExcel($exam, $attempts)
    {
        $filename = "exam_results_" . $exam['code'] . "_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, [
            'Student Name',
            'Email',
            'Started At',
            'Submitted At',
            'Time Taken (minutes)',
            'Total Marks',
            'Obtained Marks',
            'Percentage',
            'Status',
            'Pass/Fail'
        ]);

        // CSV data
        foreach ($attempts as $attempt) {
            $time_taken = $attempt['time_taken'] ? round($attempt['time_taken'] / 60, 2) : 0;
            $pass_fail = $attempt['percentage'] >= $exam['pass_percentage'] ? 'Pass' : 'Fail';

            fputcsv($output, [
                $attempt['student_name'],
                $attempt['student_email'],
                $attempt['started_at'],
                $attempt['submitted_at'],
                $time_taken,
                $attempt['total_marks'],
                $attempt['obtained_marks'],
                $attempt['percentage'],
                $attempt['status'],
                $pass_fail
            ]);
        }

        fclose($output);
        exit;
    }

    // Export results as PDF
    private function exportResultsPDF($exam, $attempts)
    {
        // This would require a PDF library like TCPDF or FPDF
        // For now, return a message indicating PDF export is not implemented
        return ['success' => false, 'message' => 'PDF export not implemented yet'];
    }

    // Publish exam
    public function publishExam()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_POST['exam_id'];
        $data = ['status' => 'published'];

        if ($this->examModel->update($exam_id, $data)) {
            return ['success' => true, 'message' => 'Exam published successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to publish exam'];
        }
    }

    // Unpublish exam
    public function unpublishExam()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $exam_id = (int)$_POST['exam_id'];
        $data = ['status' => 'draft'];

        if ($this->examModel->update($exam_id, $data)) {
            return ['success' => true, 'message' => 'Exam unpublished successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to unpublish exam'];
        }
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new ExamController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    error_log($action);
    switch ($action) {
        case 'create':
            $result = $controller->createExam();
            break;
        case 'update':
            $result = $controller->updateExam();
            break;
        case 'delete':
            $result = $controller->deleteExam();
            break;
        case 'get':
            $result = $controller->getExam();
            break;
        case 'get_all':
            $result = $controller->getAllExams();
            break;
        case 'assign_groups':
            $result = $controller->assignExamToGroups();
            break;
        case 'get_statistics':
            $result = $controller->getExamStatistics();
            break;
        case 'get_attempts':
            $result = $controller->getExamAttempts();
            break;
        case 'export_results':
            $result = $controller->exportResults();
            break;
        case 'publish':
            $result = $controller->publishExam();
            break;
        case 'unpublish':
            $result = $controller->unpublishExam();
            break;
        default:
            $result = ['success' => false, 'message' => 'Invalid action'];
    }

    if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
