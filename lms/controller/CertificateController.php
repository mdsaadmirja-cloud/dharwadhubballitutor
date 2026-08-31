<?php
// lms/controller/CertificateController.php
session_start();
require_once '../model/Certificate.php';
require_once '../model/ExamAttempt.php';
require_once '../model/CertificateTemplate.php';


class CertificateController
{
    private $certificateModel;
    private $attemptModel;
    private $certificateTemplateModel;

    public function __construct()
    {
        $this->certificateModel = new Certificate();
        $this->attemptModel = new ExamAttempt();
        $this->certificateTemplateModel = new CertificateTemplate();
    }

    // Generate certificate for completed exam
    public function generateCertificate()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $attempt_id = (int)$_POST['attempt_id'];
        $user_id = $_SESSION['user']['id'];

        // Verify attempt belongs to user
        $attempt = $this->attemptModel->getById($attempt_id);
        if (!$attempt || $attempt['user_id'] != $user_id) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }

        // Check if attempt is completed
        if (!in_array($attempt['status'], ['submitted', 'auto_submitted'])) {
            return ['success' => false, 'message' => 'Exam must be completed to generate certificate'];
        }

        // Check if student passed
        if ($attempt['percentage'] < $attempt['pass_percentage']) {
            return ['success' => false, 'message' => 'Certificate can only be generated for passing attempts'];
        }

        // Generate certificate
        $certificate = $this->certificateModel->generateCertificate($attempt_id);

        if ($certificate) {
            return ['success' => true, 'message' => 'Certificate generated successfully', 'certificate' => $certificate];
        } else {
            return ['success' => false, 'message' => 'Failed to generate certificate'];
        }
    }

    // Get certificate for attempt
    public function getCertificate()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $attempt_id = (int)$_GET['attempt_id'];
        $user_id = $_SESSION['user']['id'];

        // Verify attempt belongs to user
        $attempt = $this->attemptModel->getById($attempt_id);
        if (!$attempt || $attempt['user_id'] != $user_id) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }

        $certificate = $this->certificateModel->getByAttemptId($attempt_id);

        if ($certificate) {
            return ['success' => true, 'certificate' => $certificate];
        } else {
            return ['success' => false, 'message' => 'Certificate not found'];
        }
    }

    // Get user's certificates
    public function getUserCertificates()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $user_id = $_SESSION['user'];
        $certificates = $this->certificateModel->getByUserId($user_id);

        return ['success' => true, 'certificates' => $certificates];
    }

    // Download certificate as PDF
    public function downloadCertificate()
    {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $attempt_id = (int)$_GET['attempt_id'];
        $user_id = $_SESSION['user']['id'];

        // Verify attempt belongs to user
        $attempt = $this->attemptModel->getById($attempt_id);
        if (!$attempt || $attempt['user_id'] != $user_id) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }

        $certificate = $this->certificateModel->getByAttemptId($attempt_id);

        if (!$certificate) {
            return ['success' => false, 'message' => 'Certificate not found'];
        }

        // Generate PDF
        $this->generatePDF($certificate);
        exit;
    }

    // Generate PDF certificate
    private function generatePDF($certificate)
    {
        require_once '../vendor/fpdf/fpdf.php';

        $pdf = new FPDF('L', 'mm', 'A4'); // Landscape orientation
        $pdf->AddPage();

        // Background image (full-page)
        // ---------------------------------------------------------
        // Resolve selected certificate template
        // ---------------------------------------------------------

        $templatePath = null;

        // Use the template selected for this exam
        if (!empty($certificate['certificate_template_id'])) {

            $template = $this->certificateTemplateModel->getById(
                (int)$certificate['certificate_template_id']
            );

            if ($template && !empty($template['template_file'])) {

                /*
         * Database stores paths like:
         *
         * uploads/certificate_templates/filename.png
         *
         * Since this controller is inside:
         *
         * /lms/controller/
         *
         * dirname(__DIR__) points to:
         *
         * /lms/
         */

                $templatePath = dirname(__DIR__) . '/' .
                    ltrim($template['template_file'], '/');
            }
        }

        // ---------------------------------------------------------
        // Safety fallback for existing certificates
        // ---------------------------------------------------------

        if (empty($templatePath) || !file_exists($templatePath)) {

            $templatePath = dirname(__DIR__) . '/img/Certificatebg.png';
        }

        // ---------------------------------------------------------
        // Use selected template as full-page PDF background
        // ---------------------------------------------------------

        $pdf->Image(
            $templatePath,
            0,
            0,
            $pdf->GetPageWidth(),
            $pdf->GetPageHeight()
        );

        // Set margins and line spacing
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(20, 20, 20);

        // Title - Big & bold
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Ln(40);
        $pdf->Cell(0, 20, 'CERTIFICATE OF ACHIEVEMENT', 0, 1, 'C');
        $pdf->Ln(5);

        // Subtitle
        $pdf->SetFont('Arial', '', 18);
        $pdf->Cell(0, 12, 'This is to certify that', 0, 1, 'C');
        $pdf->Ln(5);

        // Student Name - Highlighted
        $pdf->SetFont('Arial', 'B', 28);
        $pdf->SetTextColor(0, 102, 204); // blue tone
        $pdf->Cell(0, 15, strtoupper($certificate['student_name']), 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(4);

        // Exam information
        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 10, 'has successfully completed the examination', 0, 1, 'C');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(0, 12, '"' . $certificate['exam_title'] . '"', 0, 1, 'C');
        $pdf->Ln(8);

        // Score and percentage
        $pdf->SetFont('Arial', '', 15);
        $pdf->Cell(0, 10, 'with a score of ' . $certificate['score'] . ' out of ' . $certificate['total_marks'] . ' marks', 0, 1, 'C');
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 8, '(' . number_format($certificate['percentage'], 1) . '% - ' . ($certificate['passed'] ? 'PASSED' : 'FAILED') . ')', 0, 1, 'C');
        $pdf->Ln(10);

        // Certificate ID (Left) and Completion Date (Right)
        $pdf->SetFont('Arial', '', 12);
        $completion_date = date('F d, Y', strtotime($certificate['completion_date']));

        $leftText = 'Certificate ID: ' . $certificate['certificate_id'];
        $rightText = 'Completed on: ' . $completion_date;

        // Left & Right text alignment
        $currentY = $pdf->GetY();
        $pdf->SetY($currentY);
        $pdf->Cell(0, 8, $leftText, 0, 0, 'L');
        $pdf->Cell(0, 8, $rightText, 0, 1, 'R');


        // Add signature image (bottom right)
        $signaturePath = '../img/signature.png'; // <-- place the uploaded signature image here
        $pdf->Image($signaturePath, 130, 160, 30); // X=210, Y=150, width=50mm (adjust as needed)

        // Signature text below the image
        $pdf->SetY(180);
        $pdf->SetFont('Arial', 'I', 13);
        $pdf->Cell(0, 10, 'Authorized Signature', 0, 1, 'C');

        // Output PDF for download
        $filename = 'Certificate_' . $certificate['certificate_id'] . '.pdf';
        $pdf->Output('D', $filename);
    }

    // Verify certificate (public access)
    public function verifyCertificate()
    {
        $certificate_id = $_GET['certificate_id'] ?? '';

        if (empty($certificate_id)) {
            return ['success' => false, 'message' => 'Certificate ID is required'];
        }

        $result = $this->certificateModel->verifyCertificate($certificate_id);

        if ($result['valid']) {
            return ['success' => true, 'certificate' => $result['certificate']];
        } else {
            return ['success' => false, 'message' => $result['message']];
        }
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new CertificateController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    switch ($action) {
        case 'generate':
            $result = $controller->generateCertificate();
            break;
        case 'get':
            $result = $controller->getCertificate();
            break;
        case 'get_user_certificates':
            $result = $controller->getUserCertificates();
            break;
        case 'download':
            $result = $controller->downloadCertificate();
            break;
        case 'verify':
            $result = $controller->verifyCertificate();
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
