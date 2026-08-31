<?php
// lms/views/student_exam_dashboard.php
session_start();
require_once '../model/Exam.php';
require_once '../model/ExamAttempt.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$examModel = new Exam();
$attemptModel = new ExamAttempt();
$assignedExams = $examModel->getAssignedExams($_SESSION['user']['id']);
$examHistory = $attemptModel->getByUserId($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Exams - Student Dashboard</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .exam-card {
            transition: transform 0.2s;
        }
        .exam-card:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.8em;
        }
        .exam-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .status-upcoming {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-ongoing {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        .status-ended {
            background-color: #ffebee;
            color: #c62828;
        }
        .status-completed {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
    </style>
</head>
<body>
    <?php include 'student_header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">My Exams</h1>
                </div>
                
                <!-- Assigned Exams -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Assigned Exams</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($assignedExams)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No exams assigned to you yet.</p>
                        </div>
                        <?php else: ?>
                        <div class="row">
                            <?php foreach ($assignedExams as $exam): ?>
                            <?php
                            $now = new DateTime();
                            $now->add(new DateInterval('PT5H30M'));
                            $stringDate = $now->format('Y-m-d H:i:s');
                           
                            $start_time = new DateTime($exam['start_time']);
                            
                          
                            $end_time = new DateTime($exam['end_time']);
                            $stringDate = $start_time->format('Y-m-d H:i:s');
                           
                            $status = 'upcoming';
                            $statusText = 'Upcoming';
                            
                            if ($now >= $start_time && $now <= $end_time) {
                               
                                $status = 'ongoing';
                                $statusText = 'Ongoing';
                            } elseif ($now > $end_time) {
                                $status = 'ended';
                                $statusText = 'Ended';
                            }
                            
                            // Check if user has attempted
                            $userAttempt = null;
                            foreach ($examHistory as $attempt) {
                                if ($attempt['exam_id'] == $exam['id']) {
                                    $userAttempt = $attempt;
                                    if ($attempt['status'] === 'submitted' || $attempt['status'] === 'auto_submitted') {
                                        $status = 'completed';
                                        $statusText = 'Completed';
                                    }
                                    break;
                                }
                            }
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card exam-card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($exam['title']); ?></h6>
                                        <span class="exam-status status-<?php echo $status; ?>"><?php echo $statusText; ?></span>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            <strong>Code:</strong> <?php echo htmlspecialchars($exam['code']); ?><br>
                                            <strong>Duration:</strong> <?php echo $exam['duration']; ?> minutes<br>
                                            <strong>Total Marks:</strong> <?php echo $exam['total_marks']; ?><br>
                                            <strong>Pass %:</strong> <?php echo $exam['pass_percentage']; ?>%<br>
                                            <strong>Start:</strong> <?php echo date('M d, Y H:i', strtotime($exam['start_time'])); ?><br>
                                            <strong>End:</strong> <?php echo date('M d, Y H:i', strtotime($exam['end_time'])); ?>
                                        </p>
                                        
                                        <?php if ($exam['description']): ?>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($exam['description'], 0, 100)); ?>...</p>
                                        <?php endif; ?>
                                        
                                        <?php if ($userAttempt): ?>
                                        <div class="mt-3">
                                            <p class="mb-1"><strong>Your Attempt:</strong></p>
                                            <p class="mb-1">Status: <span class="badge badge-<?php echo $userAttempt['status'] === 'submitted' ? 'success' : 'warning'; ?>"><?php echo ucfirst($userAttempt['status']); ?></span></p>
                                            <?php if ($userAttempt['status'] === 'submitted' || $userAttempt['status'] === 'auto_submitted'): ?>
                                            <p class="mb-1">Score: <?php echo $userAttempt['obtained_marks']; ?>/<?php echo $userAttempt['total_marks']; ?> (<?php echo number_format($userAttempt['percentage'], 2); ?>%)</p>
                                            <p class="mb-0">Result: <span class="badge badge-<?php echo $userAttempt['percentage'] >= $exam['pass_percentage'] ? 'success' : 'danger'; ?>"><?php echo $userAttempt['percentage'] >= $exam['pass_percentage'] ? 'Pass' : 'Fail'; ?></span></p>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <?php if ($status === 'ongoing' && !$userAttempt): ?>
                                        <button class="btn btn-success btn-block" onclick="startExam(<?php echo $exam['id']; ?>)">
                                            <i class="fas fa-play"></i> Start Exam
                                        </button>
                                        <?php elseif ($status === 'ongoing' && $userAttempt && $userAttempt['status'] === 'in_progress'): ?>
                                        <button class="btn btn-warning btn-block" onclick="continueExam(<?php echo $userAttempt['id']; ?>)">
                                            <i class="fas fa-play"></i> Continue Exam
                                        </button>
                                        <?php elseif ($status === 'completed' && $exam['show_results']): ?>
                                        <button class="btn btn-info btn-block" onclick="viewResult(<?php echo $userAttempt['id']; ?>)">
                                            <i class="fas fa-eye"></i> View Result
                                        </button>
                                        <?php elseif ($status === 'upcoming'): ?>
                                        <button class="btn btn-secondary btn-block" disabled>
                                            <i class="fas fa-clock"></i> Exam Not Started
                                        </button>
                                        <?php elseif ($status === 'ended'): ?>
                                        <button class="btn btn-secondary btn-block" disabled>
                                            <i class="fas fa-times"></i> Exam Ended
                                        </button>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-outline-primary btn-block mt-2" onclick="viewInstructions(<?php echo $exam['id']; ?>)">
                                            <i class="fas fa-info-circle"></i> Instructions
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Exam History -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Exam History</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($examHistory)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No exam history available.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Code</th>
                                        <th>Started</th>
                                        <th>Submitted</th>
                                        <th>Duration</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($examHistory as $attempt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($attempt['exam_title']); ?></td>
                                        <td><?php echo htmlspecialchars($attempt['exam_code']); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($attempt['started_at'])); ?></td>
                                        <td><?php echo $attempt['submitted_at'] ? date('M d, Y H:i', strtotime($attempt['submitted_at'])) : '-'; ?></td>
                                        <td><?php echo $attempt['time_taken'] ? round($attempt['time_taken'] / 60, 2) . ' min' : '-'; ?></td>
                                        <td><?php echo $attempt['obtained_marks']; ?>/<?php echo $attempt['total_marks']; ?></td>
                                        <td><?php echo number_format($attempt['percentage'], 2); ?>%</td>
                                        <td>
                                            <?php if ($attempt['status'] === 'submitted' || $attempt['status'] === 'auto_submitted'): ?>
                                            <span class="badge badge-<?php echo $attempt['percentage'] >= 50 ? 'success' : 'danger'; ?>">
                                                <?php echo $attempt['percentage'] >= 50 ? 'Pass' : 'Fail'; ?>
                                            </span>
                                            <?php else: ?>
                                            <span class="badge badge-secondary">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $attempt['status'] === 'submitted' ? 'success' : ($attempt['status'] === 'auto_submitted' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($attempt['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($attempt['status'] === 'submitted' || $attempt['status'] === 'auto_submitted'): ?>
                                            <button class="btn btn-sm btn-info" onclick="viewResult(<?php echo $attempt['id']; ?>)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <script>
        function startExam(examId) {
            if (confirm('Are you ready to start the exam? Once started, the timer will begin.')) {
                $.ajax({
                    url: '../controller/StudentExamController.php?action=start_exam&ajax=1',
                    type: 'POST',
                    data: {exam_id: examId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'student_exam_interface.php?attempt_id=' + response.attempt_id;
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while starting the exam.');
                    }
                });
            }
        }
        
        function continueExam(attemptId) {
            window.location.href = 'student_exam_interface.php?attempt_id=' + attemptId;
        }
        
        function viewResult(attemptId) {
            window.location.href = 'exam_result.php?attempt_id=' + attemptId;
        }
        
        function viewInstructions(examId) {
            $.ajax({
                url: '../controller/StudentExamController.php?action=get_exam_instructions&exam_id=' + examId + '&ajax=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showInstructionsModal(response.exam);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while loading instructions.');
                }
            });
        }
        
        function showInstructionsModal(exam) {
            const modalHtml = `
                <div class="modal fade" id="instructionsModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Exam Instructions - ${exam.title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Exam Details:</h6>
                                        <ul>
                                            <li><strong>Code:</strong> ${exam.code}</li>
                                            <li><strong>Duration:</strong> ${exam.duration} minutes</li>
                                            <li><strong>Total Marks:</strong> ${exam.total_marks}</li>
                                            <li><strong>Pass Percentage:</strong> ${exam.pass_percentage}%</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Timing:</h6>
                                        <ul>
                                            <li><strong>Start Time:</strong> ${new Date(exam.start_time).toLocaleString()}</li>
                                            <li><strong>End Time:</strong> ${new Date(exam.end_time).toLocaleString()}</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <h6>Instructions:</h6>
                                <ul>
                                    <li>Read each question carefully before answering</li>
                                    <li>You have ${exam.duration} minutes to complete the exam</li>
                                    <li>Use the navigation buttons to move between questions</li>
                                    <li>You can mark questions for review and come back to them later</li>
                                    <li>Your answers are automatically saved as you progress</li>
                                    <li>Once you submit the exam, you cannot make any changes</li>
                                    ${exam.negative_marking ? '<li>Negative marking is enabled for incorrect answers</li>' : ''}
                                    ${exam.randomize_questions ? '<li>Questions are randomized for each student</li>' : ''}
                                    ${exam.randomize_choices ? '<li>Answer choices are randomized</li>' : ''}
                                </ul>
                                
                                ${exam.description ? '<h6>Description:</h6><p>' + exam.description + '</p>' : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(modalHtml);
            $('#instructionsModal').modal('show');
            
            $('#instructionsModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }
    </script>
</body>
</html>
