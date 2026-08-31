<?php
// lms/views/certificate_view.php
session_start();
require_once '../model/Certificate.php';
require_once '../model/ExamAttempt.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$attempt_id = (int)($_GET['attempt_id'] ?? 0);
error_log($attempt_id);
if (!$attempt_id) {
    header('Location: student_exam_dashboard.php');
    exit;
}

$certificateModel = new Certificate();
$attemptModel = new ExamAttempt();

// Verify attempt belongs to user
$attempt = $attemptModel->getById($attempt_id);
if (!$attempt || $attempt['user_id'] != $_SESSION['user']['id']) {
    header('Location: student_exam_dashboard.php');
    exit;
}

$certificate = $certificateModel->getByAttemptId($attempt_id);

if (!$certificate) {
    header('Location: exam_result.php?attempt_id=' . $attempt_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo htmlspecialchars($certificate['exam_title']); ?></title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .certificate-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .certificate {
            background: url('../img/Certificatebg.png');
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 60px;
            margin: 20px auto;
            max-width: 800px;
            position: relative;
            border: 8px solid #f8f9fa;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            pointer-events: none;
        }
        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .certificate-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .certificate-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 30px;
        }
        .student-name {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
            text-align: center;
        }
        .exam-details {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin: 30px 0;
        }
        .score-display {
            font-size: 3rem;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }
        .certificate-footer {
            margin-top: 40px;
            text-align: center;
        }
        .certificate-id {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 20px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        @media print {
            .certificate-container {
                background: white;
                padding: 0;
            }
            .print-button {
                display: none;
            }
            .certificate {
                background:url('../img/Certificatebg.png');
                box-shadow: none;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="print-button">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Certificate
            </button>
            <button class="btn btn-success" onclick="downloadCertificate()">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
        
        <div class="certificate">
            <div class="certificate-header">
                <div class="certificate-title">Certificate of Completion</div>
                <div class="certificate-subtitle">This is to certify that</div>
            </div>
            
            <div class="student-name">
                <?php echo htmlspecialchars($certificate['student_name']); ?>
            </div>
            
            <div class="text-center mb-4">
                <p class="h5">has successfully completed the examination</p>
                <p class="h4 text-primary font-weight-bold">"<?php echo htmlspecialchars($certificate['exam_title']); ?>"</p>
            </div>
            
            <div class="exam-details">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Exam Details:</h6>
                        <p><strong>Exam Code:</strong> <?php echo htmlspecialchars($certificate['exam_code']); ?></p>
                        <p><strong>Completion Date:</strong> <?php echo date('F d, Y', strtotime($certificate['completion_date'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Performance:</h6>
                        <div class="score-display">
                            <?php echo number_format($certificate['percentage'], 1); ?>%
                        </div>
                        <p class="text-center">
                            <strong><?php echo $certificate['score']; ?></strong> out of 
                            <strong><?php echo $certificate['total_marks']; ?></strong> marks
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="certificate-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="border-top pt-3">
                            <p class="mb-0"><strong>Authorized Signature</strong></p>
                            <div style="height: 50px;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-top pt-3">
                            <p class="mb-0"><strong>Date</strong></p>
                            <p><?php echo date('F d, Y'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="certificate-id">
                    <small>Certificate ID: <?php echo $certificate['certificate_id']; ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function downloadCertificate() {
            window.open('../controller/CertificateController.php?action=download&attempt_id=<?php echo $attempt_id; ?>', '_blank');
        }
        
        // Auto-print when opened in new window
        if (window.opener) {
            setTimeout(function() {
                window.print();
            }, 1000);
        }
    </script>
</body>
</html>
