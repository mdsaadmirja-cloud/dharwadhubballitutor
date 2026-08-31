<?php
// lms/views/student_batch_progress.php
session_start();
require_once '../model/Batch.php';
require_once '../model/ClassSession.php';
require_once '../model/SyllabusCoverage.php';
require_once '../model/StudentGroup.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$batchModel = new Batch();
$sessionModel = new ClassSession();
$syllabusModel = new SyllabusCoverage();
$groupModel = new StudentGroup();

// Get student's batches
$studentBatches = $groupModel->getGroupsForUser($_SESSION['user_id']);
?>
<?php include 'student_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">My Batch Progress</h1>
            </div>
            
            <?php if (empty($studentBatches)): ?>
            <div class="alert alert-info">
                <h4 class="alert-heading">No Batches Assigned</h4>
                <p>You are not currently enrolled in any batches. Please contact your administrator to get assigned to a batch.</p>
            </div>
            <?php else: ?>
            
            <!-- Batch Progress Cards -->
            <div class="row">
                <?php foreach ($studentBatches as $batch): ?>
                <?php 
                $batchDetails = $batchModel->getById($batch['id']);
                $progress = $syllabusModel->getStudentSyllabusProgress($_SESSION['user_id'], $batch['id']);
                $attendance = $sessionModel->getStudentAttendanceSummary($_SESSION['user_id'], $batch['id']);
                ?>
                <div class="col-lg-6 mb-4">
                    <div class="card batch-progress-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($batchDetails['name']); ?></h6>
                            <span class="badge badge-<?php echo $batchDetails['batch_status'] === 'active' ? 'success' : ($batchDetails['batch_status'] === 'upcoming' ? 'warning' : 'info'); ?>">
                                <?php echo ucfirst($batchDetails['batch_status']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Batch Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Start Date</small><br>
                                    <strong><?php echo date('M d, Y', strtotime($batchDetails['batch_start_date'])); ?></strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">End Date</small><br>
                                    <strong><?php echo date('M d, Y', strtotime($batchDetails['batch_end_date'])); ?></strong>
                                </div>
                            </div>
                            
                            <!-- Progress Bars -->
                            <?php if ($progress): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Syllabus Progress</small>
                                    <small class="text-muted"><?php echo number_format($progress['completion_percentage'], 1); ?>%</small>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?php echo $progress['completion_percentage']; ?>%"></div>
                                </div>
                                <small class="text-muted">
                                    <?php echo $progress['completed_topics']; ?> of <?php echo $progress['total_topics']; ?> topics completed
                                </small>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Attendance Summary -->
                            <?php if (!empty($attendance)): ?>
                            <?php 
                            $totalSessions = count($attendance);
                            $presentSessions = count(array_filter($attendance, function($a) { return $a['attendance_status'] === 'present'; }));
                            $attendanceRate = $totalSessions > 0 ? ($presentSessions / $totalSessions) * 100 : 0;
                            ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Attendance Rate</small>
                                    <small class="text-muted"><?php echo number_format($attendanceRate, 1); ?>%</small>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                         style="width: <?php echo $attendanceRate; ?>%"></div>
                                </div>
                                <small class="text-muted">
                                    <?php echo $presentSessions; ?> of <?php echo $totalSessions; ?> sessions attended
                                </small>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Quick Stats -->
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-right">
                                        <h6 class="text-primary mb-0"><?php echo $batchDetails['total_sessions_completed'] ?? 0; ?></h6>
                                        <small class="text-muted">Sessions</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-right">
                                        <h6 class="text-success mb-0"><?php echo $progress ? $progress['completed_topics'] : 0; ?></h6>
                                        <small class="text-muted">Topics</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-info mb-0"><?php echo $batchDetails['current_students'] ?? 0; ?></h6>
                                    <small class="text-muted">Students</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-sm" onclick="viewDetailedProgress(<?php echo $batch['id']; ?>)">
                                <i class="fas fa-chart-line"></i> View Details
                            </button>
                            <button class="btn btn-info btn-sm" onclick="viewUpcomingSessions(<?php echo $batch['id']; ?>)">
                                <i class="fas fa-calendar"></i> Upcoming
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Detailed Progress Modal -->
<div class="modal fade" id="detailedProgressModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detailed Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailedProgressContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Sessions Modal -->
<div class="modal fade" id="upcomingSessionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upcoming Sessions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="upcomingSessionsContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Attendance History Modal -->
<div class="modal fade" id="attendanceHistoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="attendanceHistoryContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function viewDetailedProgress(batchId) {
    $('#detailedProgressContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#detailedProgressModal').modal('show');
    
    $.ajax({
        url: '../controller/BatchController.php?action=get_student_progress&ajax=1',
        type: 'GET',
        data: { 
            student_id: <?php echo $_SESSION['user_id']; ?>,
            batch_id: batchId 
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayDetailedProgress(response);
            } else {
                $('#detailedProgressContent').html('<div class="alert alert-danger">Error: ' + response.message + '</div>');
            }
        },
        error: function() {
            $('#detailedProgressContent').html('<div class="alert alert-danger">An error occurred while loading progress details.</div>');
        }
    });
}

function displayDetailedProgress(data) {
    var html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Syllabus Progress</h6>
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: ${data.syllabus_progress.completion_percentage}%">
                        ${data.syllabus_progress.completion_percentage.toFixed(1)}%
                    </div>
                </div>
                <p><strong>${data.syllabus_progress.completed_topics}</strong> of <strong>${data.syllabus_progress.total_topics}</strong> topics completed</p>
                
                <h6 class="mt-4">Topic Status</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Status</th>
                                <th>Coverage</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    data.syllabus_progress.topics.forEach(function(topic) {
        var statusClass = 'secondary';
        var statusText = 'Not Started';
        
        if (topic.status === 'completed') {
            statusClass = 'success';
            statusText = 'Completed';
        } else if (topic.status === 'in_progress') {
            statusClass = 'warning';
            statusText = 'In Progress';
        } else if (topic.status === 'review_needed') {
            statusClass = 'danger';
            statusText = 'Review Needed';
        }
        
        html += `
            <tr>
                <td>${topic.topic_name}</td>
                <td><span class="badge badge-${statusClass}">${statusText}</span></td>
                <td>${topic.coverage_percentage || 0}%</td>
            </tr>
        `;
    });
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6>Recent Attendance</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Session</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    if (data.attendance_history && data.attendance_history.length > 0) {
        data.attendance_history.slice(0, 10).forEach(function(attendance) {
            var statusClass = 'success';
            var statusText = 'Present';
            
            if (attendance.attendance_status === 'absent') {
                statusClass = 'danger';
                statusText = 'Absent';
            } else if (attendance.attendance_status === 'late') {
                statusClass = 'warning';
                statusText = 'Late';
            } else if (attendance.attendance_status === 'excused') {
                statusClass = 'info';
                statusText = 'Excused';
            }
            
            html += `
                <tr>
                    <td>${new Date(attendance.session_date).toLocaleDateString()}</td>
                    <td>${attendance.session_title}</td>
                    <td><span class="badge badge-${statusClass}">${statusText}</span></td>
                </tr>
            `;
        });
    } else {
        html += '<tr><td colspan="3" class="text-center">No attendance records</td></tr>';
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <button class="btn btn-info btn-sm" onclick="viewFullAttendanceHistory(${data.batch_id})">
                        <i class="fas fa-history"></i> View Full History
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#detailedProgressContent').html(html);
}

function viewUpcomingSessions(batchId) {
    $('#upcomingSessionsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#upcomingSessionsModal').modal('show');
    
    // Simulate loading upcoming sessions
    setTimeout(function() {
        $('#upcomingSessionsContent').html(`
            <div class="list-group">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Mathematics - Algebra Basics</h6>
                        <small>Tomorrow</small>
                    </div>
                    <p class="mb-1">Introduction to algebraic expressions and equations</p>
                    <small>10:00 AM - 12:00 PM</small>
                </div>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Physics - Mechanics</h6>
                        <small>Day after tomorrow</small>
                    </div>
                    <p class="mb-1">Understanding motion and forces</p>
                    <small>2:00 PM - 4:00 PM</small>
                </div>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Chemistry - Atomic Structure</h6>
                        <small>Next week</small>
                    </div>
                    <p class="mb-1">Fundamentals of atomic theory</p>
                    <small>10:00 AM - 12:00 PM</small>
                </div>
            </div>
        `);
    }, 1000);
}

function viewFullAttendanceHistory(batchId) {
    $('#attendanceHistoryContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#attendanceHistoryModal').modal('show');
    
    // Simulate loading full attendance history
    setTimeout(function() {
        $('#attendanceHistoryContent').html(`
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Session Title</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-01-15</td>
                            <td>Mathematics - Introduction</td>
                            <td>10:00 AM</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>2024-01-17</td>
                            <td>Physics - Basics</td>
                            <td>2:00 PM</td>
                            <td><span class="badge badge-warning">Late</span></td>
                            <td>Arrived 15 minutes late</td>
                        </tr>
                        <tr>
                            <td>2024-01-19</td>
                            <td>Chemistry - Lab Session</td>
                            <td>10:00 AM</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `);
    }, 1000);
}

// Auto-refresh progress every 5 minutes
setInterval(function() {
    // You can implement auto-refresh functionality here
    console.log('Auto-refreshing progress...');
}, 300000);
</script>
</body>
</html>
