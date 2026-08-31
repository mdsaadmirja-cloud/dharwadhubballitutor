<?php
// lms/views/exam_results_analytics.php
session_start();
require_once '../model/Exam.php';
require_once '../model/ExamAttempt.php';
require_once '../Utilities/ExamHelper.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$exam_id = (int)($_GET['id'] ?? 0);
if (!$exam_id) {
    header('Location: admin_exam_management.php');
    exit;
}

$examModel = new Exam();
$attemptModel = new ExamAttempt();
$examHelper = new ExamHelper();

$exam = $examModel->getById($exam_id);
$attempts = $attemptModel->getByExamId($exam_id);
$statistics = $examHelper->calculateExamStatistics($exam_id);
$difficultyDistribution = $examHelper->getQuestionDifficultyDistribution($exam_id);
$topicPerformance = $examHelper->getTopicWisePerformance($exam_id);
$accessStats = $examHelper->getExamAccessStats($exam_id);

if (!$exam) {
    header('Location: admin_exam_management.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results & Analytics - <?php echo htmlspecialchars($exam['title']); ?></title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        .performance-badge {
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Exam Results & Analytics</h1>
            <div>
                <a href="admin_exam_management.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Exams
                </a>
                <a href="../controller/ExamController.php?action=export_results&exam_id=<?php echo $exam_id; ?>&format=excel" class="btn btn-success">
                    <i class="fas fa-download"></i> Export Results
                </a>
            </div>
        </div>
        
        <!-- Exam Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Exam Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($exam['title']); ?></p>
                        <p><strong>Code:</strong> <?php echo htmlspecialchars($exam['code']); ?></p>
                        <p><strong>Duration:</strong> <?php echo $exam['duration']; ?> minutes</p>
                        <p><strong>Total Marks:</strong> <?php echo $exam['total_marks']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Pass Percentage:</strong> <?php echo $exam['pass_percentage']; ?>%</p>
                        <p><strong>Start Time:</strong> <?php echo date('M d, Y H:i', strtotime($exam['start_time'])); ?></p>
                        <p><strong>End Time:</strong> <?php echo date('M d, Y H:i', strtotime($exam['end_time'])); ?></p>
                        <p><strong>Status:</strong> <span class="badge badge-<?php echo $exam['status'] === 'published' ? 'success' : 'warning'; ?>"><?php echo ucfirst($exam['status']); ?></span></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Attempts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $statistics['total_attempts'] ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Completed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $statistics['completed_attempts'] ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2 stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Average Score</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($statistics['average_percentage'] ?? 0, 1); ?>%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2 stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pass Rate</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($statistics['pass_rate'] ?? 0, 1); ?>%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Performance Charts -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Performance Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Results Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detailed Results</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="resultsTable">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Started</th>
                                        <th>Submitted</th>
                                        <th>Time Taken</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attempts as $attempt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($attempt['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($attempt['student_email']); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($attempt['started_at'])); ?></td>
                                        <td><?php echo $attempt['submitted_at'] ? date('M d, Y H:i', strtotime($attempt['submitted_at'])) : '-'; ?></td>
                                        <td><?php echo $attempt['time_taken'] ? ExamHelper::formatDuration($attempt['time_taken']) : '-'; ?></td>
                                        <td><?php echo $attempt['obtained_marks']; ?>/<?php echo $attempt['total_marks']; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $attempt['percentage'] >= 80 ? 'success' : ($attempt['percentage'] >= 60 ? 'warning' : 'danger'); ?> performance-badge">
                                                <?php echo number_format($attempt['percentage'], 1); ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $attempt['percentage'] >= $exam['pass_percentage'] ? 'success' : 'danger'; ?>">
                                                <?php echo $attempt['percentage'] >= $exam['pass_percentage'] ? 'Pass' : 'Fail'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $attempt['status'] === 'submitted' ? 'success' : ($attempt['status'] === 'auto_submitted' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($attempt['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Analytics Sidebar -->
            <div class="col-lg-4">
                <!-- Access Statistics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Access Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Assigned Students:</span>
                                <strong><?php echo $accessStats['total_assigned_students']; ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Students Attempted:</span>
                                <strong><?php echo $accessStats['students_attempted']; ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Students Completed:</span>
                                <strong><?php echo $accessStats['students_completed']; ?></strong>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Attempt Rate:</span>
                                <strong><?php echo number_format($accessStats['attempt_rate'], 1); ?>%</strong>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Completion Rate:</span>
                                <strong><?php echo number_format($accessStats['completion_rate'], 1); ?>%</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Question Difficulty Distribution -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Question Difficulty</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($difficultyDistribution as $difficulty => $data): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-capitalize"><?php echo $difficulty; ?>:</span>
                                <strong><?php echo $data['count']; ?> questions</strong>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-<?php echo $difficulty === 'easy' ? 'success' : ($difficulty === 'medium' ? 'warning' : 'danger'); ?>" 
                                     style="width: <?php echo ($data['count'] / array_sum(array_column($difficultyDistribution, 'count'))) * 100; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Topic Performance -->
                <?php if (!empty($topicPerformance)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Topic Performance</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach (array_slice($topicPerformance, 0, 5) as $topic): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span><?php echo htmlspecialchars($topic['topic']); ?>:</span>
                                <strong><?php echo number_format($topic['accuracy'], 1); ?>%</strong>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-info" style="width: <?php echo $topic['accuracy']; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#resultsTable').DataTable({
                "pageLength": 25,
                "order": [[6, "desc"]], // Sort by percentage descending
                "columnDefs": [
                    { "orderable": false, "targets": [8] }
                ]
            });
        });
        
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'],
                datasets: [{
                    label: 'Number of Students',
                    data: [
                        <?php
                        $ranges = [0, 0, 0, 0, 0];
                        foreach ($attempts as $attempt) {
                            if ($attempt['status'] === 'submitted' || $attempt['status'] === 'auto_submitted') {
                                $percentage = $attempt['percentage'];
                                if ($percentage <= 20) $ranges[0]++;
                                elseif ($percentage <= 40) $ranges[1]++;
                                elseif ($percentage <= 60) $ranges[2]++;
                                elseif ($percentage <= 80) $ranges[3]++;
                                else $ranges[4]++;
                            }
                        }
                        echo implode(',', $ranges);
                        ?>
                    ],
                    backgroundColor: [
                        '#dc3545',
                        '#fd7e14',
                        '#ffc107',
                        '#20c997',
                        '#28a745'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
