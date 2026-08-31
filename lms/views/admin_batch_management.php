<?php
// lms/views/admin_batch_management.php
session_start();
require_once '../model/Batch.php';
require_once '../model/SyllabusCoverage.php';
require_once '../model/User.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$batchModel = new Batch();
$syllabusModel = new SyllabusCoverage();
$userModel = new User();

$batches = $batchModel->getAll(null);
$syllabi = $syllabusModel->getAllSyllabi();
$instructors = $userModel->getAllTeachers(); // You may need to filter for instructors only
?>
<?php include 'header.php'; ?>

<!-- Removed bootstrap-select CSS to avoid hiding native select under Bootstrap 5 -->

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Batch Management</h1>
                <div>
                    <button class="btn btn-success mr-2" data-bs-toggle="modal" data-bs-target="#createSyllabusModal">
                        <i class="fas fa-book"></i> Create Syllabus
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBatchModal">
                        <i class="fas fa-plus"></i> Create New Batch
                    </button>
                </div>
            </div>
            
            <!-- Batch Status Tabs -->
            <ul class="nav nav-tabs" id="batchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All Batches</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">Upcoming</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="false">Active</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed</button>
                </li>
            </ul>
            
            <!-- Batch Tabs Content -->
            <div class="tab-content" id="batchTabsContent">
                <!-- All Batches -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div class="row mt-4" id="allBatchesGrid">
                        <?php foreach ($batches as $batch): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card batch-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($batch['name']); ?></h6>
                                    <span class="badge badge-<?php echo $batch['batch_status'] === 'active' ? 'success' : ($batch['batch_status'] === 'upcoming' ? 'warning' : 'info'); ?> status-badge">
                                        <?php echo ucfirst($batch['batch_status']); ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong>Start Date:</strong> <?php echo date('M d, Y', strtotime($batch['batch_start_date'])); ?><br>
                                        <strong>End Date:</strong> <?php echo date('M d, Y', strtotime($batch['batch_end_date'])); ?><br>
                                        <strong>Duration:</strong> <?php echo $batch['total_duration_days']; ?> days<br>
                                        <strong>Students:</strong> <?php echo $batch['current_students']; ?>/<?php echo $batch['max_students']; ?><br>
                                        <strong>Instructor:</strong> <?php echo htmlspecialchars($batch['instructor_name'] ?? 'Not assigned'); ?><br>
                                        <strong>Syllabus:</strong> <?php echo htmlspecialchars($batch['syllabus_title'] ?? 'Not assigned'); ?><br>
                                        <strong>Progress:</strong> <?php echo number_format($batch['syllabus_completion_percentage'] ?? 0, 1); ?>%
                                    </p>
                                    <?php if ($batch['description']): ?>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars(substr($batch['description'], 0, 100)); ?>...</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <button class="btn btn-sm btn-info" onclick="viewBatch(<?php echo $batch['id']; ?>)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="editBatch(<?php echo $batch['id']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="manageSessions(<?php echo $batch['id']; ?>)">
                                            <i class="fas fa-calendar"></i> Sessions
                                        </button>
                                        <button class="btn btn-sm btn-primary" onclick="manageStudents(<?php echo $batch['id']; ?>)">
                                            <i class="fas fa-users"></i> Students
                                        </button>
                                        <button class="btn btn-sm btn-secondary" onclick="viewProgress(<?php echo $batch['id']; ?>)">
                                            <i class="fas fa-chart-line"></i> Progress
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteBatch(<?php echo $batch['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Other tabs will be populated via AJAX -->
                <div class="tab-pane fade" id="upcoming" role="tabpanel">
                    <div class="row mt-4" id="upcomingBatchesGrid">
                        <!-- Content loaded via AJAX -->
                    </div>
                </div>
                <div class="tab-pane fade" id="active" role="tabpanel">
                    <div class="row mt-4" id="activeBatchesGrid">
                        <!-- Content loaded via AJAX -->
                    </div>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <div class="row mt-4" id="completedBatchesGrid">
                        <!-- Content loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<!-- Create Batch Modal -->
<div class="modal fade" id="createBatchModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBatchForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Batch Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_students">Max Students *</label>
                                <input type="number" class="form-control" id="max_students" name="max_students" min="1" value="30" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_start_date">Start Date *</label>
                                <input type="date" class="form-control" id="batch_start_date" name="batch_start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_end_date">End Date *</label>
                                <input type="date" class="form-control" id="batch_end_date" name="batch_end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_duration_days">Total Duration (Days) *</label>
                                <input type="number" class="form-control" id="total_duration_days" name="total_duration_days" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fees">Fees</label>
                                <input type="number" class="form-control" id="fees" name="fees" min="0" step="0.01" value="0.00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="syllabus_id">Syllabus</label>
                                <select class="form-control" id="syllabus_id" name="syllabus_id">
                                    <option value="">Select Syllabus</option>
                                    <?php foreach ($syllabi as $syllabus): ?>
                                    <option value="<?php echo $syllabus['id']; ?>"><?php echo htmlspecialchars($syllabus['title']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructor_id">Instructor</label>
                                <select class="form-control" id="instructor_id" name="instructor_id">
                                    <option value="">Select Instructor</option>
                                    <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?php echo $instructor['id']; ?>"><?php echo htmlspecialchars($instructor['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_schedule">Class Schedule (JSON format)</label>
                        <textarea class="form-control" id="class_schedule" name="class_schedule" rows="3" placeholder='{"monday": "10:00-12:00", "wednesday": "10:00-12:00", "friday": "10:00-12:00"}'></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Syllabus Modal -->
<div class="modal fade" id="createSyllabusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Syllabus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSyllabusForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="syllabus_title">Syllabus Title *</label>
                        <input type="text" class="form-control" id="syllabus_title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="syllabus_description">Description</label>
                        <textarea class="form-control" id="syllabus_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_id">Course ID</label>
                                <input type="text" class="form-control" id="course_id" name="course_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_duration_hours">Total Duration (Hours)</label>
                                <input type="number" class="form-control" id="total_duration_hours" name="total_duration_hours" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Syllabus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Batch Details Modal -->
<div class="modal fade" id="batchDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batch Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="batchDetailsContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Session Management Modal -->
<div class="modal fade" id="sessionManagementModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Session Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="sessionManagementContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Batch Modal -->
<div class="modal fade" id="editBatchModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBatchForm">
                <input type="hidden" id="edit_batch_id" name="batch_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Batch Name *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_max_students">Max Students *</label>
                                <input type="number" class="form-control" id="edit_max_students" name="max_students" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_batch_status">Batch Status</label>
                        <select class="form-control" id="edit_batch_status" name="batch_status">
                            <option value="upcoming">Upcoming</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_batch_start_date">Start Date *</label>
                                <input type="date" class="form-control" id="edit_batch_start_date" name="batch_start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_batch_end_date">End Date *</label>
                                <input type="date" class="form-control" id="edit_batch_end_date" name="batch_end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_total_duration_days">Total Duration (Days) *</label>
                                <input type="number" class="form-control" id="edit_total_duration_days" name="total_duration_days" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_fees">Fees</label>
                                <input type="number" class="form-control" id="edit_fees" name="fees" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_syllabus_id">Syllabus</label>
                                <select class="form-control" id="edit_syllabus_id" name="syllabus_id">
                                    <option value="">Select Syllabus</option>
                                    <?php foreach ($syllabi as $syllabus): ?>
                                    <option value="<?php echo $syllabus['id']; ?>"><?php echo htmlspecialchars($syllabus['title']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_instructor_id">Instructor</label>
                                <select class="form-control" id="edit_instructor_id" name="instructor_id">
                                    <option value="">Select Instructor</option>
                                    <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?php echo $instructor['id']; ?>"><?php echo htmlspecialchars($instructor['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_class_schedule">Class Schedule (JSON format)</label>
                        <textarea class="form-control" id="edit_class_schedule" name="class_schedule" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Session Modal -->
<div class="modal fade" id="createSessionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSessionForm">
                <input type="hidden" id="session_batch_id" name="batch_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="session_title">Session Title *</label>
                        <input type="text" class="form-control" id="session_title" name="session_title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_date">Session Date *</label>
                                <input type="date" class="form-control" id="session_date" name="session_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_time">Session Time</label>
                                <input type="time" class="form-control" id="session_time" name="session_time">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_instructor_id">Instructor *</label>
                                <select class="form-control" id="session_instructor_id" name="instructor_id" required>
                                    <option value="">Select Instructor</option>
                                    <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?php echo $instructor['id']; ?>"><?php echo htmlspecialchars($instructor['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                                <label for="session_type">Session Type *</label>
                                <select class="form-control" id="session_type" name="session_type" required>
                                    <option value="lecture">Lecture</option>
                                    <option value="lab">Lab</option>
                                    <option value="review">Review</option>
                                    <option value="exam">Exam</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="session_description">Description</label>
                        <textarea class="form-control" id="session_description" name="session_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Session</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage Students Modal -->
<div class="modal fade" id="manageStudentsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="manageStudentsForm">
                    <input type="hidden" id="student_management_batch_id" name="batch_id">
                    <div class="form-group">
                        <label for="student_ids">Select Students</label>
                        <div id="student_filter_group" class="mb-2">
                            <input type="text" class="form-control" id="student_filter" placeholder="Search students by name or email...">
                        </div>
                        <select multiple class="form-control selectpicker" id="student_ids" name="student_ids[]" data-live-search="true" title="Choose one or more students...">
                            <!-- Students will be loaded here via AJAX -->
                        </select>
                        <small class="form-text text-muted">You can select multiple students using checkboxes in this dropdown.</small>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Current Batch Members (select to remove)</label>
                        <div id="current_members_container" class="border rounded p-2" style="max-height: 220px; overflow:auto;">
                            <!-- Current members will be listed here with checkboxes -->
                        </div>
                        <button type="button" class="btn btn-outline-danger mt-2" onclick="removeSelectedMembers()">Remove Selected</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="$('#manageStudentsForm').submit()">Save Changes</button>
            </div>
        </div>
    </div>
</div>



<script>
// Create Batch
$('#createBatchForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '../controller/BatchController.php?action=create_batch&ajax=1',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Batch created successfully!');
                (function(){
                    var el = document.getElementById('createBatchModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide(); }
                })();
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while creating the batch.');
        }
    });
});

// Create Syllabus
$('#createSyllabusForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '../controller/BatchController.php?action=create_syllabus&ajax=1',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Syllabus created successfully!');
                (function(){
                    var el = document.getElementById('createSyllabusModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide(); }
                })();
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while creating the syllabus.');
        }
    });
});

// Load batches by status when tabs are clicked
$('#batchTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).data("bs-target");
    var status = target.substring(1); // Remove #
    
    if (status !== 'all') {
        loadBatchesByStatus(status);
    }
});

function loadBatchesByStatus(status) {
    var gridId = status + 'BatchesGrid';
    
    $.ajax({
        url: '../controller/BatchController.php?action=get_batches_by_status&ajax=1',
        type: 'GET',
        data: { status: status },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var html = '';
                response.batches.forEach(function(batch) {
                    html += generateBatchCard(batch);
                });
                $('#' + gridId).html(html);
            }
        },
        error: function() {
            $('#' + gridId).html('<div class="col-12"><p class="text-center">Error loading batches</p></div>');
        }
    });
}

function generateBatchCard(batch) {
    var statusClass = batch.batch_status === 'active' ? 'success' : (batch.batch_status === 'upcoming' ? 'warning' : 'info');
    var startDate = new Date(batch.batch_start_date).toLocaleDateString();
    var endDate = new Date(batch.batch_end_date).toLocaleDateString();
    
    return `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card batch-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">${batch.name}</h6>
                    <span class="badge badge-${statusClass} status-badge">
                        ${batch.batch_status.charAt(0).toUpperCase() + batch.batch_status.slice(1)}
                    </span>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Start Date:</strong> ${startDate}<br>
                        <strong>End Date:</strong> ${endDate}<br>
                        <strong>Duration:</strong> ${batch.total_duration_days} days<br>
                        <strong>Students:</strong> ${batch.current_students}/${batch.max_students}<br>
                        <strong>Instructor:</strong> ${batch.instructor_name || 'Not assigned'}<br>
                        <strong>Syllabus:</strong> ${batch.syllabus_title || 'Not assigned'}<br>
                        <strong>Progress:</strong> ${(Number(batch.syllabus_completion_percentage) || 0).toFixed(1)}%
                    </p>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-sm btn-info" onclick="viewBatch(${batch.id})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="editBatch(${batch.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-success" onclick="manageSessions(${batch.id})">
                            <i class="fas fa-calendar"></i> Sessions
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="manageStudents(${batch.id})">
                            <i class="fas fa-users"></i> Students
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="viewProgress(${batch.id})">
                            <i class="fas fa-chart-line"></i> Progress
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteBatch(${batch.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Batch management functions
function viewBatch(batchId) {
    $.ajax({
        url: '../controller/BatchController.php?action=get_batch&ajax=1',
        type: 'GET',
        data: { batch_id: batchId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayBatchDetails(response.batch);
                (function(){
                    var el = document.getElementById('batchDetailsModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show(); }
                })();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while loading batch details.');
        }
    });
}

function displayBatchDetails(batch) {
    var html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Basic Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Name:</strong></td><td>${batch.name}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge badge-info">${batch.batch_status}</span></td></tr>
                    <tr><td><strong>Start Date:</strong></td><td>${new Date(batch.batch_start_date).toLocaleDateString()}</td></tr>
                    <tr><td><strong>End Date:</strong></td><td>${new Date(batch.batch_end_date).toLocaleDateString()}</td></tr>
                    <tr><td><strong>Duration:</strong></td><td>${batch.total_duration_days} days</td></tr>
                    <tr><td><strong>Max Students:</strong></td><td>${batch.max_students}</td></tr>
                    <tr><td><strong>Fees:</strong></td><td>₹${batch.fees}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Progress Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Sessions Scheduled:</strong></td><td>${batch.total_sessions_scheduled || 0}</td></tr>
                    <tr><td><strong>Sessions Completed:</strong></td><td>${batch.total_sessions_completed || 0}</td></tr>
                    <tr><td><strong>Syllabus Progress:</strong></td><td>${(Number(batch.syllabus_completion_percentage) || 0).toFixed(1)}%</td></tr>
                    <tr><td><strong>Avg Attendance:</strong></td><td>${(Number(batch.average_attendance_rate) || 0).toFixed(1)}%</td></tr>
                    <tr><td><strong>Performance:</strong></td><td>${batch.overall_batch_performance || '-'}</td></tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Batch Students (${batch.members ? batch.members.length : 0} student${batch.members && batch.members.length !== 1 ? 's' : ''})</h6>
                <div style="max-height: 300px; overflow:auto;">
                    ${batch.members && batch.members.length ? 
                        `<table class="table table-bordered table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>College</th>
                                    <th>Added Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${batch.members.map(function(s, index) {
                                    return `<tr>
                                        <td>${index + 1}</td>
                                        <td><strong>${s.name || 'N/A'}</strong></td>
                                        <td>${s.email || 'N/A'}</td>
                                        <td>${s.college || '-'}</td>
                                        <td>${s.added_at ? new Date(s.added_at).toLocaleDateString() : '-'}</td>
                                    </tr>`;
                                }).join('')}
                            </tbody>
                        </table>` 
                        : '<div class="alert alert-info">No students assigned to this batch yet.</div>'}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Recent Sessions</h6>
                <div class="list-group" style="max-height: 200px; overflow:auto;">${
                    batch.recent_sessions && batch.recent_sessions.length ?
                    batch.recent_sessions.map(function(session) {
                        return `<div class="list-group-item"><strong>${session.session_title}</strong><br><small>${new Date(session.session_date).toLocaleDateString()} - ${session.status}</small></div>`;
                    }).join('')
                    : '<div class="list-group-item">No recent sessions</div>'
                }</div>
            </div>
        </div>
    `;
    $('#batchDetailsContent').html(html);
}

function manageSessions(batchId) {
    // Always use a fresh sessions table with a create button
    var tableHtml = `
                    <button class="btn btn-primary mb-3" onclick="createSession(${batchId})">
            <i class="fas fa-plus"></i> Create New Session
                    </button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Status</th>
                    <th>Instructor</th>
                                </tr>
                            </thead>
            <tbody id="batchSessionsTableBody">
                <tr><td colspan="6" class="text-center">Sessions will be loaded here</td></tr>
                            </tbody>
                        </table>
    `;
    $('#sessionManagementContent').html(tableHtml);
    (function(){
        var el = document.getElementById('sessionManagementModal');
        if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show(); }
    })();
    loadBatchSessions(batchId);
}

function loadBatchSessions(batchId) {
    $.ajax({
        url: '../controller/BatchController.php?action=get_sessions_for_batch&ajax=1',
        type: 'GET',
        data: { batch_id: batchId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                renderBatchSessionsTable(response.sessions);
            } else {
                $('#batchSessionsTableBody').html('<tr><td colspan="6" class="text-center">Failed to load</td></tr>');
            }
        },
        error: function() {
            $('#batchSessionsTableBody').html('<tr><td colspan="6" class="text-center">Network error</td></tr>');
        }
    });
}

var currentBatchSessions = {};

function renderBatchSessionsTable(sessions) {
    var html = '';
    sessions.forEach(function(session) {
        var statusOptions = ['scheduled', 'completed', 'postponed', 'cancelled'].map(function(st) {
            var selected = (session.status === st) ? 'selected' : '';
            return `<option value="${st}" ${selected}>${st.charAt(0).toUpperCase() + st.slice(1)}</option>`;
        }).join('');
        html += `<tr 
            data-session-id="${session.id}"
            data-session-date="${session.session_date || ''}"
            data-session-time="${session.session_time || ''}"
            data-duration-minutes="${session.duration_minutes || 60}"
            data-topic-id="${session.topic_id || ''}"
            data-lesson-id="${session.lesson_id || ''}"
            data-instructor-id="${session.instructor_id || ''}"
            data-session-type="${session.session_type || ''}"
            data-session-title="${session.session_title || ''}"
            data-session-description="${session.session_description || ''}"
            data-homework-assigned="${session.homework_assigned || ''}"
            data-notes="${session.notes || ''}"
        >` +
            '<td>' + (session.session_date || '-') + '</td>' +
            '<td>' + (session.session_time || '-') + '</td>' +
            '<td>' + (session.session_title || '-') + '</td>' +
            '<td>' + (session.session_type || '-') + '</td>' +
            `<td>
                <select class="form-control form-control-sm session-status-select" data-session-id="${session.id}">
                    ${statusOptions}
                </select>
                <button class="btn btn-sm btn-info update-session-status mt-1" data-session-id="${session.id}">Update</button>
            </td>` +
            '<td>' + (session.instructor_name || '-') + '</td>' +
        '</tr>';
    });
    if (!sessions.length) {
        html = '<tr><td colspan="6" class="text-center">No sessions yet</td></tr>';
    }
    $('#batchSessionsTableBody').html(html);
}

$(document).on('click', '.update-session-status', function() {
    console.log('clicked update button!');
    var $btn = $(this);
    var sessionId = $btn.data('session-id');
    var $tr = $btn.closest('tr');
    var newStatus = $('.session-status-select[data-session-id="' + sessionId + '"]').val();
    var payload = {
        session_id: $tr.data('session-id'),
        session_date: $tr.data('session-date'),
        session_time: $tr.data('session-time'),
        duration_minutes: $tr.data('duration-minutes'),
        topic_id: $tr.data('topic-id'),
        lesson_id: $tr.data('lesson-id'),
        instructor_id: $tr.data('instructor-id'),
        session_type: $tr.data('session-type'),
        session_title: $tr.data('session-title'),
        session_description: $tr.data('session-description'),
        homework_assigned: $tr.data('homework-assigned'),
        notes: $tr.data('notes'),
        status: newStatus
    };
    console.log('AJAX payload:', payload);
    $.ajax({
        url: '../controller/BatchController.php?action=update_session&ajax=1',
        type: 'POST',
        data: payload,
        dataType: 'json',
        success: function(response) {
            alert(response.success ? 'Session status updated!' : 'Error: ' + response.message);
            var batchId = $('#session_batch_id').val();
            if (batchId) loadBatchSessions(batchId);
        },
        error: function() {
            alert('An error occurred while updating session status.');
        }
    });
});

function editBatch(batchId) {
    debugger;
    $.ajax({
        url: '../controller/BatchController.php?action=get_batch&ajax=1',
        type: 'GET',
        data: { batch_id: batchId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var batch = response.batch;
                $('#edit_batch_id').val(batch.id);
                $('#edit_name').val(batch.name);
                $('#edit_description').val(batch.description);
                $('#edit_batch_start_date').val(batch.batch_start_date);
                $('#edit_batch_end_date').val(batch.batch_end_date);
                $('#edit_total_duration_days').val(batch.total_duration_days);
                $('#edit_class_schedule').val(batch.class_schedule);
                $('#edit_syllabus_id').val(batch.syllabus_id);
                $('#edit_instructor_id').val(batch.instructor_id);
                $('#edit_max_students').val(batch.max_students);
                $('#edit_fees').val(batch.fees);
                $('#edit_batch_status').val(batch.batch_status);
                
                (function(){
                    var el = document.getElementById('editBatchModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show(); }
                })();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while fetching batch details.');
        }
    });
}

$('#editBatchForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '../controller/BatchController.php?action=update_batch&ajax=1',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Batch updated successfully!');
                (function(){
                    var el = document.getElementById('editBatchModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide(); }
                })();
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while updating the batch.');
        }
    });
});

function manageStudents(batchId) {
    $('#student_management_batch_id').val(batchId);

    $.ajax({
        url: '../controller/BatchController.php?action=get_student_management_data&ajax=1',
        type: 'GET',
        data: { batch_id: batchId },
    dataType: 'json',
    success: function(response) {
            console.log('get_student_management_data response:', response);
            if (response.success) {
                var studentSelect = $('#student_ids');
                studentSelect.empty(); // Clear existing options

                // Normalize assigned IDs to strings to avoid type mismatches
                var assignedIds = new Set((response.memberIds || []).map(function(id){ return String(id); }));
                if (response.allStudents && response.allStudents.length) {
                    // Sort students alphabetically by name (case-insensitive) before listing
                    response.allStudents.sort(function(a, b) {
                        var an = (a.name || '').toString().toLowerCase();
                        var bn = (b.name || '').toString().toLowerCase();
                        if (an < bn) return -1;
                        if (an > bn) return 1;
                        return 0;
                    });
                    // Show only unassigned students for append-only UX
                    response.allStudents.forEach(function(student) {
                        if (assignedIds.has(String(student.id))) { return; }
                        var label = (student.name || 'Unnamed') + ' (' + (student.email || 'no-email') + ')';
                        var option = new Option(label, student.id, false, false);
                        studentSelect.append(option);
                    });
                } else {
                    var option = new Option('No students found', '', false, false);
                    option.disabled = true;
                    studentSelect.append(option);
                }

                // Render current members list with checkboxes to remove
                var membersHtml = '';
                if (response.members && response.members.length) {
                    // Sort current members alphabetically by name (case-insensitive)
                    response.members.sort(function(a, b) {
                        var an = (a.name || '').toString().toLowerCase();
                        var bn = (b.name || '').toString().toLowerCase();
                        if (an < bn) return -1;
                        if (an > bn) return 1;
                        return 0;
                    });
                    response.members.forEach(function(m) {
                        membersHtml += '<div class="form-check">' +
                            '<input class="form-check-input" type="checkbox" name="remove_member_ids[]" value="' + m.id + '" id="rm_' + m.id + '">' +
                            '<label class="form-check-label" for="rm_' + m.id + '">' + (m.name || 'Unnamed') + ' (' + (m.email || 'no-email') + ')</label>' +
                        '</div>';
                    });
                } else {
                    membersHtml = '<div class="text-muted">No members in this batch yet.</div>';
                }
                $('#current_members_container').html(membersHtml);

                // Ensure the control is a visible native multi-select (Bootstrap 5 fallback)
                window.currentBatchMemberIds = response.memberIds || [];

                if ($.fn && $.fn.selectpicker) {
                    $('.selectpicker').selectpicker('refresh');
                    $('#student_filter_group').hide();
                } else {
                    // Fallback: convert to a visible native multi-select
                    var $sel = $('#student_ids');
                    // Remove any bootstrap-select artifacts/classes
                    $sel.removeClass('selectpicker').removeAttr('data-live-search');
                    $sel.next('.bootstrap-select').remove();
                    // Ensure it's multi-select and visible
                    $sel.attr('multiple', true);
                    var optCount = $sel.find('option').length;
                    $sel.attr('size', Math.min(10, Math.max(6, optCount)));
                    $sel.css({ height: 'auto', display: 'block', visibility: 'visible' });
                    // Show and bind manual search for native multi-select
                    $('#student_filter_group').show();
                    $('#student_filter').off('input').on('input', function() {
                        var q = $(this).val().toString().toLowerCase();
                        $('#student_ids option').each(function() {
                            var txt = ($(this).text() || '').toLowerCase();
                            var match = !q || txt.indexOf(q) !== -1;
                            // Use hidden attribute for best cross-browser behavior on <option>
                            if (match) {
                                this.hidden = false;
                            } else {
                                this.hidden = true;
                            }
                        });
                    });
                }
                // Remove any legacy handling of a non-existent search element

                (function(){
                    var el = document.getElementById('manageStudentsModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show(); }
                })();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while fetching student data.');
        }
    });
}

$('#manageStudentsForm').on('submit', function(e) {
    e.preventDefault();

    var batchId = $('#student_management_batch_id').val();
    // Append-only: send only new selections (unassigned list)
    var selectedIds = ($('#student_ids').val() || []).map(function(id){ return String(id); });

    $.ajax({
        url: '../controller/BatchController.php?action=append_batch_members&ajax=1',
        type: 'POST',
        // Use bracket notation so PHP parses array correctly as $_POST['student_ids']
        data: { batch_id: batchId, 'student_ids[]': selectedIds },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Students updated successfully!');
                (function(){
                    var el = document.getElementById('manageStudentsModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide(); }
                })();
                location.reload(); // Reload to see student count changes
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while updating students.');
        }
    });
});

function viewProgress(batchId) {
    $.ajax({
        url: '../controller/BatchController.php?action=get_batch&ajax=1',
        type: 'GET',
        data: { batch_id: batchId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showProgressModal(response.batch);
            } else {
                alert('Failed to load batch progress: ' + response.message);
            }
        },
        error: function() {
            alert('Error loading progress data from server.');
        }
    });
}

function showProgressModal(batch) {
    var html = `
        <div class="container-fluid p-3">
            <h5>Progress for "${batch.name}"</h5>
            <table class="table table-bordered">
                <tr><th>Sessions Scheduled</th><td>${Number(batch.total_sessions_scheduled) || 0}</td></tr>
                <tr><th>Sessions Completed</th><td>${Number(batch.total_sessions_completed) || 0}</td></tr>
                <tr><th>Syllabus Progress</th><td>${(Number(batch.syllabus_completion_percentage) || 0).toFixed(1)}%</td></tr>
                <tr><th>Average Attendance Rate</th><td>${(Number(batch.average_attendance_rate) || 0).toFixed(1)}%</td></tr>
                <tr><th>Performance</th><td>${batch.overall_batch_performance || '-'}</td></tr>
            </table>
        </div>`;
    if ($('#progressModal').length === 0) {
        $('body').append(`
            <div class="modal fade" id="progressModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Batch Progress</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="progressModalBody"></div>
                </div>
                </div>
            </div>
        `);
    }
    $('#progressModalBody').html(html);
    (function(){
        var el = document.getElementById('progressModal');
        if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show(); }
    })();
}

function removeSelectedMembers() {
    var batchId = $('#student_management_batch_id').val();
    var ids = [];
    $('input[name="remove_member_ids[]"]:checked').each(function() { ids.push($(this).val()); });
    if (!ids.length) { alert('Please select at least one student to remove.'); return; }

    $.ajax({
        url: '../controller/BatchController.php?action=remove_batch_members&ajax=1',
        type: 'POST',
        data: { batch_id: batchId, 'student_ids[]': ids },
        dataType: 'json',
        success: function(resp) {
            if (resp.success) {
                alert('Selected students removed');
                manageStudents(batchId); // reload lists
            } else {
                alert('Error: ' + resp.message);
            }
        },
        error: function() { alert('Network error while removing students'); }
    });
}

function deleteBatch(batchId) {
    if (confirm('Are you sure you want to delete this batch? This action cannot be undone.')) {
        $.ajax({
            url: '../controller/BatchController.php?action=delete_batch&ajax=1',
            type: 'POST',
            data: { batch_id: batchId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Batch deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting the batch.');
            }
        });
    }
}

function createSession(batchId) {
    $('#createSessionForm')[0].reset();
    $('#session_batch_id').val(batchId);
    (function(){
        var el = document.getElementById('createSessionModal');
        if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show(); }
    })();
}

$('#createSessionForm').on('submit', function(e) {
    e.preventDefault();
    var batchId = $('#session_batch_id').val();
    $.ajax({
        url: '../controller/BatchController.php?action=create_session&ajax=1',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Session created successfully!');
                (function(){
                    var el = document.getElementById('createSessionModal');
                    if (el) { (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).hide(); }
                })();
                loadBatchSessions(batchId); // reload session list
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while creating the session.');
        }
    });
});

</script>
<!-- bootstrap-select JS removed due to incompatibility with Bootstrap 5; using plain multi-select fallback -->
</body>
</html>
