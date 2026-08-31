<?php
// lms/views/certificate_verify.php
// Public certificate verification page

require_once '../model/Certificate.php';

$certificate_id = $_GET['certificate_id'] ?? '';
$certificate = null;
$error_message = '';

if (!empty($certificate_id)) {
    $certificateModel = new Certificate();
    $result = $certificateModel->verifyCertificate($certificate_id);
    
    if ($result['valid']) {
        $certificate = $result['certificate'];
    } else {
        $error_message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .verification-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 0;
        }
        .verification-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 20px auto;
            max-width: 600px;
        }
        .certificate-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .status-valid {
            color: #28a745;
        }
        .status-invalid {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="container">
            <div class="verification-card">
                <div class="text-center mb-4">
                    <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                    <h2 class="text-primary">Certificate Verification</h2>
                    <p class="text-muted">Verify the authenticity of a certificate</p>
                </div>
                
                <form method="GET" class="mb-4">
                    <div class="form-group">
                        <label for="certificate_id">Certificate ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="certificate_id" name="certificate_id" 
                                   value="<?php echo htmlspecialchars($certificate_id); ?>" 
                                   placeholder="Enter certificate ID (e.g., CERT-2024-ABC12345)" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Verify
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Verification Failed:</strong> <?php echo htmlspecialchars($error_message); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($certificate): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Certificate Verified Successfully!</strong>
                </div>
                
                <div class="certificate-details">
                    <h5 class="mb-3">Certificate Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Student Name:</strong><br>
                            <?php echo htmlspecialchars($certificate['student_name']); ?></p>
                            
                            <p><strong>Exam Title:</strong><br>
                            <?php echo htmlspecialchars($certificate['exam_title']); ?></p>
                            
                            <p><strong>Exam Code:</strong><br>
                            <?php echo htmlspecialchars($certificate['exam_code']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Score:</strong><br>
                            <?php echo $certificate['score']; ?> out of <?php echo $certificate['total_marks']; ?> marks</p>
                            
                            <p><strong>Percentage:</strong><br>
                            <span class="badge badge-success"><?php echo number_format($certificate['percentage'], 1); ?>%</span></p>
                            
                            <p><strong>Completion Date:</strong><br>
                            <?php echo date('F d, Y', strtotime($certificate['completion_date'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <p><strong>Certificate ID:</strong><br>
                        <code><?php echo $certificate['certificate_id']; ?></code></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="text-center mt-4">
                    <a href="../index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>
</html>
