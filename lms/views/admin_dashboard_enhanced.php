<?php
// lms/views/admin_dashboard_enhanced.php
session_start();
require_once '../model/Exam.php';
require_once '../model/StudentGroup.php';
require_once '../model/User.php';
require_once '../Utilities/ExamHelper.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$examModel = new Exam();
$groupModel = new StudentGroup();
$userModel = new User();
$examHelper = new ExamHelper();

$exams = $examModel->getAll($_SESSION['user_id']);
$groups = $groupModel->getAll($_SESSION['user_id']);
$students = $userModel->getAllStudents();
$analytics = $examHelper->getExamAnalytics($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Exam Management</title>
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
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Exam Management Dashboard</h1>
            <div>
                <a href="admin_exam_management.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Manage Exams
                </a>
                <a href="student_group_management.php" class="btn btn-success">
                    <i class="fas fa-users"></i> Manage Groups
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Exams</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $analytics['total_exams'] ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                    Published Exams</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $analytics['published_exams'] ?? 0; ?></div>
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
                                    Total Attempts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $analytics['total_attempts'] ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                    Avg Performance</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($analytics['avg_performance'] ?? 0, 1); ?>%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Exams -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Exams</h6>
                        <a href="admin_exam_management.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($exams)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No exams created yet. <a href="admin_exam_management.php">Create your first exam</a></p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th>Start Time</th>
                                        <th>Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($exams, 0, 5) as $exam): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($exam['title']); ?></td>
                                        <td><?php echo htmlspecialchars($exam['code']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $exam['status'] === 'published' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($exam['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y H:i', strtotime($exam['start_time'])); ?></td>
                                        <td><?php echo $exam['duration']; ?> min</td>
                                        <td>
                                            <a href="question_management.php?exam_id=<?php echo $exam['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-question-circle"></i>
                                            </a>
                                            <a href="exam_results.php?id=<?php echo $exam['id']; ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-chart-bar"></i>
                                            </a>
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

            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Student Groups:</span>
                                <strong><?php echo count($groups); ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Students:</span>
                                <strong><?php echo count($students); ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Completed Attempts:</span>
                                <strong><?php echo $analytics['completed_attempts'] ?? 0; ?></strong>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="admin_exam_management.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Create Exam
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Performance Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Exams -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Currently Active Exams</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        $activeExams = array_filter($exams, function($exam) {
                            $now = new DateTime();
                            $start = new DateTime($exam['start_time']);
                            $end = new DateTime($exam['end_time']);
                            return $exam['status'] === 'published' && $now >= $start && $now <= $end;
                        });
                        ?>
                        
                        <?php if (empty($activeExams)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No active exams at the moment.</p>
                        </div>
                        <?php else: ?>
                        <div class="row">
                            <?php foreach ($activeExams as $exam): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-left-success">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    <?php echo htmlspecialchars($exam['title']); ?>
                                                </div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                    Code: <?php echo htmlspecialchars($exam['code']); ?>
                                                </div>
                                                <div class="text-xs text-muted">
                                                    Ends: <?php echo date('M d, Y H:i', strtotime($exam['end_time'])); ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-play-circle fa-2x text-success"></i>
                                            </div>
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
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <script>
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{
                    data: [
                        <?php echo $analytics['completed_attempts'] ? round(($analytics['avg_performance'] / 100) * $analytics['completed_attempts']) : 0; ?>,
                        <?php echo $analytics['completed_attempts'] ? round(((100 - $analytics['avg_performance']) / 100) * $analytics['completed_attempts']) : 0; ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
