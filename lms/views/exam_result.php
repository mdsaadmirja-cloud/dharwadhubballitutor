<?php
// lms/views/exam_result.php




require_once '../controller/StudentExamController.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    error_log("here in the first condition");
    header('Location: login.php');
    exit;
}

$attempt_id = (int)($_GET['attempt_id'] ?? 0);
if (!$attempt_id) {
    error_log("here in the second condition");
    header('Location: student_exam_dashboard.php');
    exit;
}

$controller = new StudentExamController();
$_GET['attempt_id'] = $attempt_id;
$result = $controller->getExamResult();



$exam = $result['exam'];
$attempt = $result['attempt'];
$answers = $result['answers'];
$can_show_question_details = $result['can_show_question_details'] ?? false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result - <?php echo htmlspecialchars($exam['title']); ?></title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .result-card {
            margin-bottom: 20px;
        }
        .score-display {
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
        }
        .score-pass {
            color: #28a745;
        }
        .score-fail {
            color: #dc3545;
        }
        .answer-item {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .answer-correct {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .answer-incorrect {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .answer-unanswered {
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
        .explanation {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'student_header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Exam Result</h1>
                    <a href="student_exam_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                
                <!-- Result Summary -->
                <div class="card result-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo htmlspecialchars($exam['title']); ?></h5>
                        <small class="text-muted">Exam Code: <?php echo htmlspecialchars($exam['code']); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="score-display <?php echo $attempt['percentage'] >= $exam['pass_percentage'] ? 'score-pass' : 'score-fail'; ?>">
                                    <?php echo number_format($attempt['percentage'], 1); ?>%
                                </div>
                                <p class="text-muted">Final Score</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="h4"><?php echo $attempt['obtained_marks']; ?>/<?php echo $attempt['total_marks']; ?></div>
                                <p class="text-muted">Marks Obtained</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <!--<div class="h4"><?php //echo $attempt['time_taken'] ? round($attempt['time_taken'] / 60, 2) : 0; ?> min</div>-->
                                <!--<p class="text-muted">Time Taken</p>-->
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="h4">
                                    <span class="badge badge-<?php echo $attempt['percentage'] >= $exam['pass_percentage'] ? 'success' : 'danger'; ?> badge-lg">
                                        <?php echo $attempt['percentage'] >= $exam['pass_percentage'] ? 'PASS' : 'FAIL'; ?>
                                    </span>
                                </div>
                                <p class="text-muted">Result</p>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>Exam Details:</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Duration:</strong> <?php echo $exam['duration']; ?> minutes</li>
                                    <li><strong>Pass Percentage:</strong> <?php echo $exam['pass_percentage']; ?>%</li>
                                    <li><strong>Started:</strong> <?php echo date('M d, Y H:i', strtotime($attempt['started_at'])); ?></li>
                                    <li><strong>Submitted:</strong> <?php echo $attempt['submitted_at'] ? date('M d, Y H:i', strtotime($attempt['submitted_at'])) : 'Not submitted'; ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Performance Summary:</h6>
                                <ul class="list-unstyled">
                                    <?php
                                    $correct_count = 0;
                                    $incorrect_count = 0;
                                    $unanswered_count = 0;
                                    
                                    foreach ($answers as $answer) {
                                        if ($answer['is_correct']) {
                                            $correct_count++;
                                        } elseif ($answer['selected_choices'] && count($answer['selected_choices']) > 0) {
                                            $incorrect_count++;
                                        } else {
                                            $unanswered_count++;
                                        }
                                    }
                                    ?>
                                    <li><strong>Correct Answers:</strong> <?php echo $correct_count; ?></li>
                                    <li><strong>Incorrect Answers:</strong> <?php echo $incorrect_count; ?></li>
                                    <li><strong>Unanswered:</strong> <?php echo $unanswered_count; ?></li>
                                    <li><strong>Total Questions:</strong> <?php echo count($answers); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Question-wise Review -->
                <?php if ($exam['show_results'] && $can_show_question_details): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Question-wise Review</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($answers as $index => $answer): ?>
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Question <?php echo $index + 1; ?></h6>
                                <div>
                                    <span class="badge badge-<?php echo $answer['is_correct'] ? 'success' : ($answer['selected_choices'] && count($answer['selected_choices']) > 0 ? 'danger' : 'warning'); ?>">
                                        <?php echo $answer['is_correct'] ? 'Correct' : ($answer['selected_choices'] && count($answer['selected_choices']) > 0 ? 'Incorrect' : 'Unanswered'); ?>
                                    </span>
                                    <span class="badge badge-info"><?php echo $answer['marks_obtained']; ?>/<?php echo $answer['marks']; ?> marks</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="question-text"><?php echo nl2br(htmlspecialchars($answer['question_text'])); ?></p>
                                
                                <div class="mt-3">
                                    <h6>Your Answer:</h6>
                                    <?php if ($answer['selected_choices'] && count($answer['selected_choices']) > 0): ?>
                                    <div class="answer-item answer-<?php echo $answer['is_correct'] ? 'correct' : 'incorrect'; ?>">
                                        <?php
                                        if (isset($answer['selected_choice_texts']) && !empty($answer['selected_choice_texts'])) {
                                            echo 'Selected: ' . implode(', ', $answer['selected_choice_texts']);
                                        } else {
                                            // Fallback to showing choice IDs if text is not available
                                            echo 'Selected: ' . implode(', ', $answer['selected_choices']);
                                        }
                                        ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="answer-item answer-unanswered">
                                        No answer provided
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($exam['show_explanations'] && isset($answer['correct_choices'])): ?>
                                    <h6 class="mt-3">Correct Answer:</h6>
                                    <div class="answer-item answer-correct">
                                        <?php
                                        foreach ($answer['correct_choices'] as $correct_choice) {
                                            echo '<div>' . htmlspecialchars($correct_choice['choice_text']) . '</div>';
                                        }
                                        ?>
                                    </div>
                                    
                                    <?php if ($answer['explanation']): ?>
                                    <div class="explanation">
                                        <h6>Explanation:</h6>
                                        <p><?php echo nl2br(htmlspecialchars($answer['explanation'])); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php elseif ($exam['show_results'] && !$can_show_question_details): ?>
                <div class="alert alert-info">
                    Detailed question-wise review will be available after the exam ends on
                    <strong><?php echo date('M d, Y H:i', strtotime($exam['end_time'])); ?></strong>.
                </div>
                <?php endif; ?>
                
                <!-- Certificate Section -->
                <?php if ($attempt['percentage'] >= $exam['pass_percentage']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-certificate"></i> Certificate
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                            <h5 class="text-success">Congratulations!</h5>
                            <p class="text-muted">You have successfully passed the examination and are eligible for a certificate.</p>
                        </div>
                        
                        <div id="certificateSection">
                            <button class="btn btn-success btn-lg" onclick="generateCertificate(<?php echo $attempt['id']; ?>)">
                                <i class="fas fa-certificate"></i> Generate Certificate
                            </button>
                        </div>
                        
                        <div id="certificateGenerated" style="display: none;">
                            <div class="alert alert-success">
                                <h6><i class="fas fa-check-circle"></i> Certificate Generated Successfully!</h6>
                                <p class="mb-2">Your certificate has been generated and is ready for download.</p>
                                <div>
                                    <button class="btn btn-primary" onclick="downloadCertificate(<?php echo $attempt['id']; ?>)">
                                        <i class="fas fa-download"></i> Download Certificate
                                    </button>
                                    <!--<button class="btn btn-outline-secondary" onclick="viewCertificate(<?php echo $attempt['id']; ?>)">
                                        <i class="fas fa-eye"></i> View Certificate
                                    </button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Actions -->
                 <!-- <div class="card">
                    <div class="card-body text-center">
                        <a href="student_exam_dashboard.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Exams
                        </a>
                        <?php if ($exam['allow_re_exam']): ?>
                        <button class="btn btn-success" onclick="retakeExam(<?php echo $exam['id']; ?>)">
                            <i class="fas fa-redo"></i> Retake Exam
                        </button>
                        <?php endif; ?>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <script>
        function retakeExam(examId) {
            if (confirm('Are you sure you want to retake this exam? Your previous attempt will be replaced.')) {
                window.location.href = 'student_exam_dashboard.php';
            }
        }
        
        // Generate Certificate
        function generateCertificate(attemptId) {
            if (confirm('Generate certificate for this exam attempt?')) {
                $.ajax({
                    url: '../controller/CertificateController.php?action=generate&ajax=1',
                    type: 'POST',
                    data: {attempt_id: attemptId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#certificateSection').hide();
                            $('#certificateGenerated').show();
                            alert('Certificate generated successfully!');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while generating the certificate.');
                    }
                });
            }
        }
        
        // Download Certificate
        function downloadCertificate(attemptId) {
            window.open('../controller/CertificateController.php?action=download&attempt_id=' + attemptId, '_blank');
        }
        
        // View Certificate
        function viewCertificate(attemptId) {
            window.open('certificate_view.php?attempt_id=' + attemptId, '_blank');
        }
        
        // Check if certificate already exists on page load
        $(document).ready(function() {
            const attemptId = <?php echo $attempt['id']; ?>;
            $.ajax({
                url: '../controller/CertificateController.php?action=get&ajax=1',
                type: 'GET',
                data: {attempt_id: attemptId},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#certificateSection').hide();
                        $('#certificateGenerated').show();
                    }
                }
            });
        });
    </script>
</body>
</html>
