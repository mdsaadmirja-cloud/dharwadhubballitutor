<?php
// lms/views/admin_exam_management.php
session_start();
require_once '../model/Exam.php';
require_once '../model/StudentGroup.php';
require_once '../model/CertificateTemplate.php';
error_log(print_r($_SESSION['user'], true));

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$examModel = new Exam();
$groupModel = new StudentGroup();
$certificateTemplateModel = new CertificateTemplate();

$exams = $examModel->getAll($_SESSION['user_id']);
$groups = $groupModel->getAll($_SESSION['user_id']);
$certificateTemplates = $certificateTemplateModel->getAll();
?>
<?php include 'header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Exam Management</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExamModal">
                    <i class="fas fa-plus"></i> Create New Exam
                </button>
            </div>

            <!-- Exams Grid -->
            <div class="row" id="examsGrid">
                <?php foreach ($exams as $exam): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card exam-card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($exam['title']); ?></h6>
                                <span class="badge badge-<?php echo $exam['status'] === 'published' ? 'success' : 'warning'; ?> status-badge">
                                    <?php echo ucfirst($exam['status']); ?>
                                </span>
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
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-info" onclick="viewExam(<?php echo $exam['id']; ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editExam(<?php echo $exam['id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteExam(<?php echo $exam['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="manageQuestions(<?php echo $exam['id']; ?>)">
                                        <i class="fas fa-question-circle"></i> Questions
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="assignExam(<?php echo $exam['id']; ?>)">
                                        <i class="fas fa-users"></i> Assign
                                    </button>
                                    <button class="btn btn-sm btn-secondary" onclick="viewResults(<?php echo $exam['id']; ?>)">
                                        <i class="fas fa-chart-bar"></i> Results
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Exam Modal -->
<div class="modal fade" id="createExamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createExamForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Exam Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Exam Code *</label>
                                <input type="text" class="form-control" id="code" name="code" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="duration">Duration (minutes) *</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_marks">Total Marks *</label>
                                <input type="number" class="form-control" id="total_marks" name="total_marks" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pass_percentage">Pass Percentage *</label>
                                <input type="number" class="form-control" id="pass_percentage" name="pass_percentage" min="0" max="100" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_time">Start Time *</label>
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_time">End Time *</label>
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificate_template_id">Certificate *</label>
                                <select class="form-control" id="certificate_template_id" name="certificate_template_id" required>
                                    <option value="">Select Certificate Template</option>
                                    <?php foreach ($certificateTemplates as $template): ?>
                                        <?php if (($template['status'] ?? '') === 'active'): ?>
                                            <option value="<?php echo (int)$template['id']; ?>">
                                                <?php echo htmlspecialchars($template['name']); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="negative_marking" name="negative_marking">
                                <label class="form-check-label" for="negative_marking">Enable Negative Marking</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="randomize_questions" name="randomize_questions">
                                <label class="form-check-label" for="randomize_questions">Randomize Questions</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="randomize_choices" name="randomize_choices">
                                <label class="form-check-label" for="randomize_choices">Randomize Answer Choices</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="show_results" name="show_results" checked>
                                <label class="form-check-label" for="show_results">Show Results to Students</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="show_explanations" name="show_explanations">
                                <label class="form-check-label" for="show_explanations">Show Answer Explanations</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="allow_re_exam" name="allow_re_exam">
                                <label class="form-check-label" for="allow_re_exam">Allow Re-exam</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Exam Modal -->
<div class="modal fade" id="editExamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExamForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_exam_id" name="exam_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_title">Exam Title *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_code">Exam Code *</label>
                                <input type="text" class="form-control" id="edit_code" name="code" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_duration">Duration (minutes) *</label>
                                <input type="number" class="form-control" id="edit_duration" name="duration" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_total_marks">Total Marks *</label>
                                <input type="number" class="form-control" id="edit_total_marks" name="total_marks" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_pass_percentage">Pass Percentage *</label>
                                <input type="number" class="form-control" id="edit_pass_percentage" name="pass_percentage" min="0" max="100" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_start_time">Start Time *</label>
                                <input type="datetime-local" class="form-control" id="edit_start_time" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_end_time">End Time *</label>
                                <input type="datetime-local" class="form-control" id="edit_end_time" name="end_time" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_certificate_template_id">Certificate *</label>
                                <select class="form-control" id="edit_certificate_template_id" name="certificate_template_id" required>
                                    <option value="">Select Certificate Template</option>
                                    <?php foreach ($certificateTemplates as $template): ?>
                                        <?php if (($template['status'] ?? '') === 'active'): ?>
                                            <option value="<?php echo (int)$template['id']; ?>">
                                                <?php echo htmlspecialchars($template['name']); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_negative_marking" name="negative_marking">
                                <label class="form-check-label" for="edit_negative_marking">Enable Negative Marking</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_randomize_questions" name="randomize_questions">
                                <label class="form-check-label" for="edit_randomize_questions">Randomize Questions</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_randomize_choices" name="randomize_choices">
                                <label class="form-check-label" for="edit_randomize_choices">Randomize Answer Choices</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_show_results" name="show_results">
                                <label class="form-check-label" for="edit_show_results">Show Results to Students</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_show_explanations" name="show_explanations">
                                <label class="form-check-label" for="edit_show_explanations">Show Answer Explanations</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="edit_allow_re_exam" name="allow_re_exam">
                                <label class="form-check-label" for="edit_allow_re_exam">Allow Re-exam</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_status">Status *</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Exam Modal -->
<div class="modal fade" id="assignExamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Exam to Groups</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignExamForm">
                <div class="modal-body">
                    <input type="hidden" id="assign_exam_id" name="exam_id">
                    <div class="form-group">
                        <label>Select Groups:</label>
                        <?php foreach ($groups as $group): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="group_ids[]" value="<?php echo $group['id']; ?>" id="group_<?php echo $group['id']; ?>">
                                <label class="form-check-label" for="group_<?php echo $group['id']; ?>">
                                    <?php echo htmlspecialchars($group['name']); ?>
                                    <span class="text-muted">(<?php echo $group['member_count']; ?> members)</span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    // Create Exam
    $('#createExamForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '../controller/ExamController.php?action=create&ajax=1',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Exam created successfully!');
                    $('#createExamModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while creating the exam.');
            }
        });
    });

    // Assign Exam
    function assignExam(examId) {
        $('#assign_exam_id').val(examId);
        $('#assignExamModal').modal('show');
    }

    $('#assignExamForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '../controller/ExamController.php?action=assign_groups&ajax=1',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Exam assigned successfully!');
                    $('#assignExamModal').modal('hide');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while assigning the exam.');
            }
        });
    });

    // Edit Exam
    function editExam(examId) {
        // Load exam data
        $.ajax({
            url: '../controller/ExamController.php?action=get&exam_id=' + examId + '&ajax=1',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const exam = response.exam;

                    // Populate form fields
                    $('#edit_exam_id').val(exam.id);
                    $('#edit_title').val(exam.title);
                    $('#edit_code').val(exam.code);
                    $('#edit_description').val(exam.description);
                    $('#edit_duration').val(exam.duration);
                    $('#edit_total_marks').val(exam.total_marks);
                    $('#edit_pass_percentage').val(exam.pass_percentage);
                    $('#edit_start_time').val(exam.start_time.replace(' ', 'T'));
                    $('#edit_end_time').val(exam.end_time.replace(' ', 'T'));
                    $('#edit_certificate_template_id').val(exam.certificate_template_id || '');
                    $('#edit_status').val(exam.status);

                    // Set checkboxes
                    $('#edit_negative_marking').prop('checked', exam.negative_marking == 1);
                    $('#edit_randomize_questions').prop('checked', exam.randomize_questions == 1);
                    $('#edit_randomize_choices').prop('checked', exam.randomize_choices == 1);
                    $('#edit_show_results').prop('checked', exam.show_results == 1);
                    $('#edit_show_explanations').prop('checked', exam.show_explanations == 1);
                    $('#edit_allow_re_exam').prop('checked', exam.allow_re_exam == 1);

                    // Show modal
                    $('#editExamModal').modal('show');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while loading exam data.');
            }
        });
    }

    // Update Exam
    $('#editExamForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '../controller/ExamController.php?action=update&ajax=1',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Exam updated successfully!');
                    $('#editExamModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating the exam.');
            }
        });
    });

    // Delete Exam
    function deleteExam(examId) {
        if (confirm('Are you sure you want to delete this exam? This action cannot be undone and will also delete all related data (attempts, answers, etc.).')) {
            $.ajax({
                url: '../controller/ExamController.php?action=delete&ajax=1',
                type: 'POST',
                data: {
                    exam_id: examId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Exam deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the exam.');
                }
            });
        }
    }

    // Other functions
    function viewExam(examId) {
        window.location.href = 'exam_details.php?id=' + examId;
    }

    function manageQuestions(examId) {
        debugger;
        window.location.href = 'question_management.php?exam_id=' + examId;
    }

    function viewResults(examId) {
        window.location.href = 'exam_results.php?id=' + examId;
    }
</script>
</body>

</html>