<?php
// lms/views/student_certificates.php
session_start();
require_once '../model/Certificate.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$certificateModel = new Certificate();
$certificates = $certificateModel->getByUserId($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .certificate-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .certificate-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .certificate-icon {
            font-size: 3rem;
            color: #ffc107;
        }
        .score-badge {
            font-size: 1.2rem;
            padding: 8px 16px;
        }
    </style>
</head>
<body>
    <?php include 'student_header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-certificate"></i> My Certificates
                    </h1>
                    <a href="student_exam_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                
                <?php if (empty($certificates)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-certificate fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No Certificates Yet</h5>
                        <p class="text-muted">Complete exams with passing grades to earn certificates.</p>
                        <a href="student_exam_dashboard.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> View Available Exams
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <?php foreach ($certificates as $certificate): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card certificate-card h-100">
                            <div class="card-body text-center">
                                <div class="certificate-icon mb-3">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                
                                <h5 class="card-title"><?php echo htmlspecialchars($certificate['exam_title']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($certificate['exam_code']); ?></p>
                                
                                <div class="mb-3">
                                    <span class="badge badge-success score-badge">
                                        <?php echo number_format($certificate['percentage'], 1); ?>%
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <strong><?php echo $certificate['score']; ?></strong> out of 
                                        <strong><?php echo $certificate['total_marks']; ?></strong> marks
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        Completed: <?php echo date('M d, Y', strtotime($certificate['completion_date'])); ?>
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        Certificate ID: <?php echo $certificate['certificate_id']; ?>
                                    </small>
                                </div>
                                
                                <div class="btn-group w-100" role="group">
                                   
                                    <button class="btn btn-success" onclick="downloadCertificate(<?php echo $certificate['attempt_id']; ?>)">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <script>
        function viewCertificate(attemptId) {
            window.open('certificate_view.php?attempt_id=' + attemptId, '_blank');
        }
        
        function downloadCertificate(attemptId) {
            window.open('../controller/CertificateController.php?action=download&attempt_id=' + attemptId, '_blank');
        }
    </script>
</body>
</html>
