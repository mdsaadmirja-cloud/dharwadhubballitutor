<?php
// lms/views/question_management.php
session_start();
require_once '../model/Exam.php';
require_once '../model/Question.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$exam_id = (int)($_GET['exam_id'] ?? 0);
if (!$exam_id) {
    header('Location: admin_exam_management.php');
    exit;
}

$examModel = new Exam();
$questionModel = new Question();
$exam = $examModel->getById($exam_id);
$questions = $questionModel->getByExamId($exam_id);

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
    <title>Question Management - <?php echo htmlspecialchars($exam['title']); ?></title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .question-card {
            margin-bottom: 20px;
        }
        .choice-item {
            padding: 5px 10px;
            margin: 2px 0;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .choice-correct {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Question Management</h1>
                    <div>
                        <button class="btn btn-success" data-toggle="modal" data-target="#bulkUploadModal">
                            <i class="fas fa-upload"></i> Bulk Upload
                        </button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createQuestionModal">
                            <i class="fas fa-plus"></i> Add Question
                        </button>
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
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total Marks:</strong> <?php echo $exam['total_marks']; ?></p>
                                <p><strong>Pass Percentage:</strong> <?php echo $exam['pass_percentage']; ?>%</p>
                                <p><strong>Status:</strong> <span class="badge badge-<?php echo $exam['status'] === 'published' ? 'success' : 'warning'; ?>"><?php echo ucfirst($exam['status']); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Questions List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Questions (<?php echo count($questions); ?>)</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($questions)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No questions added yet. Add your first question to get started.</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($questions as $index => $question): ?>
                        <div class="card question-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Question <?php echo $index + 1; ?></h6>
                                <div>
                                    <span class="badge badge-<?php echo $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger'); ?>">
                                        <?php echo ucfirst($question['difficulty']); ?>
                                    </span>
                                    <span class="badge badge-info"><?php echo $question['marks']; ?> marks</span>
                                    <button class="btn btn-sm btn-warning" onclick="editQuestion(<?php echo $question['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteQuestion(<?php echo $question['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($question['question_text'])); ?></p>
                                
                                <?php if ($question['topic']): ?>
                                <p class="text-muted"><strong>Topic:</strong> <?php echo htmlspecialchars($question['topic']); ?></p>
                                <?php endif; ?>
                                
                                <?php
                                $choices = $questionModel->getChoices($question['id']);
                                if (!empty($choices)):
                                ?>
                                <div class="mt-3">
                                    <strong>Choices:</strong>
                                    <?php foreach ($choices as $choice): ?>
                                    <div class="choice-item <?php echo $choice['is_correct'] ? 'choice-correct' : ''; ?>">
                                        <?php echo htmlspecialchars($choice['choice_text']); ?>
                                        <?php if ($choice['is_correct']): ?>
                                        <span class="badge badge-success ml-2">Correct</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($question['explanation']): ?>
                                <div class="mt-3">
                                    <strong>Explanation:</strong>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($question['explanation'])); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Question Modal -->
    <div class="modal fade" id="createQuestionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Question</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createQuestionForm">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="question_text">Question Text *</label>
                            <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="question_type">Question Type *</label>
                                    <select class="form-control" id="question_type" name="question_type" required>
                                        <option value="single_choice">Single Choice</option>
                                        <option value="multiple_choice">Multiple Choice</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="difficulty">Difficulty *</label>
                                    <select class="form-control" id="difficulty" name="difficulty" required>
                                        <option value="easy">Easy</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="marks">Marks *</label>
                                    <input type="number" class="form-control" id="marks" name="marks" min="1" value="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="negative_marks">Negative Marks</label>
                                    <input type="number" class="form-control" id="negative_marks" name="negative_marks" min="0" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="topic">Topic</label>
                                    <input type="text" class="form-control" id="topic" name="topic">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="explanation">Explanation</label>
                            <textarea class="form-control" id="explanation" name="explanation" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Answer Choices *</label>
                            <div id="choicesContainer">
                                <div class="choice-input mb-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="correct_choice" value="0" required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="choices[0][text]" placeholder="Choice 1" required>
                                    </div>
                                </div>
                                <div class="choice-input mb-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <input type="radio" name="correct_choice" value="1" required>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="choices[1][text]" placeholder="Choice 2" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addChoice()">Add Choice</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Questions</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkUploadForm" enctype="multipart/form-data">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="upload_file">Select File *</label>
                            <input type="file" class="form-control-file" id="upload_file" name="file" accept=".csv,.xlsx,.xls" required>
                            <small class="form-text text-muted">Supported formats: CSV, Excel (.xlsx, .xls)</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6>File Format Requirements:</h6>
                            <ul class="mb-0">
                                <li>First row must contain headers</li>
                                <li>Required columns: question_text, question_type, marks, choice1, choice2, correct_answers</li>
                                <li>Optional columns: negative_marks, explanation, difficulty, topic, choice3, choice4, choice5</li>
                                <li>Download template for reference</li>
                            </ul>
                        </div>
                        
                        <div class="text-center">
                            <a href="../controller/QuestionController.php?action=download_template" class="btn btn-outline-primary">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload Questions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Question Modal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Question</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editQuestionForm">
                    <input type="hidden" id="edit_question_id" name="question_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_question_text">Question Text *</label>
                            <textarea class="form-control" id="edit_question_text" name="question_text" rows="3" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_question_type">Question Type *</label>
                                    <select class="form-control" id="edit_question_type" name="question_type" required>
                                        <option value="single_choice">Single Choice</option>
                                        <option value="multiple_choice">Multiple Choice</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_difficulty">Difficulty *</label>
                                    <select class="form-control" id="edit_difficulty" name="difficulty" required>
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_marks">Marks *</label>
                                    <input type="number" class="form-control" id="edit_marks" name="marks" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_negative_marks">Negative Marks</label>
                                    <input type="number" class="form-control" id="edit_negative_marks" name="negative_marks" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_topic">Topic</label>
                                    <input type="text" class="form-control" id="edit_topic" name="topic">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_explanation">Explanation</label>
                            <textarea class="form-control" id="edit_explanation" name="explanation" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Answer Choices *</label>
                            <div id="editChoicesContainer">
                                <!-- Choices will be populated dynamically -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEditChoice()">Add Choice</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once "footer.php" ?>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    
    <script>
        let choiceCount = 2;
        let editChoiceCount = 0;
        
        // Create Question
        $('#createQuestionForm').on('submit', function(e) {
            e.preventDefault();
            
            // Process choices
            const choices = [];
            const correctChoice = $('input[name="correct_choice"]:checked').val();
            
            $('input[name^="choices["]').each(function(index) {
                const text = $(this).val();
                if (text.trim()) {
                    choices.push({
                        text: text,
                        is_correct: index == correctChoice
                    });
                }
            });
            
            const formData = $(this).serializeArray();
            formData.push({name: 'choices', value: JSON.stringify(choices)});
            
            $.ajax({
                url: '../controller/QuestionController.php?action=create&ajax=1',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Question added successfully!');
                        $('#createQuestionModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while adding the question.');
                }
            });
        });
        
        // Bulk Upload
        $('#bulkUploadForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: '../controller/QuestionController.php?action=bulk_upload&ajax=1',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Questions uploaded successfully!');
                        $('#bulkUploadModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while uploading questions.');
                }
            });
        });
        
        // Edit Question Form
        $('#editQuestionForm').on('submit', function(e) {
            e.preventDefault();
            
            // Process choices
            const choices = [];
            const correctChoices = [];
            
            // Get correct choices based on question type
            if ($('#edit_question_type').val() === 'single_choice') {
                const correctChoice = $('input[name="edit_correct_choice"]:checked').val();
                if (correctChoice !== undefined) {
                    correctChoices.push(parseInt(correctChoice));
                }
            } else {
                $('input[name="edit_correct_choice"]:checked').each(function() {
                    correctChoices.push(parseInt($(this).val()));
                });
            }
            
            $('input[name^="edit_choices["]').each(function(index) {
                const text = $(this).val();
                if (text.trim()) {
                    choices.push({
                        text: text,
                        is_correct: correctChoices.includes(index)
                    });
                }
            });
            
            const formData = $(this).serializeArray();
            formData.push({name: 'choices', value: JSON.stringify(choices)});
            
            $.ajax({
                url: '../controller/QuestionController.php?action=update&ajax=1',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Question updated successfully!');
                        $('#editQuestionModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while updating the question.');
                }
            });
        });
        
        // Add Choice
        function addChoice() {
            if (choiceCount >= 5) {
                alert('Maximum 5 choices allowed');
                return;
            }
            
            const choiceHtml = `
                <div class="choice-input mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="radio" name="correct_choice" value="${choiceCount}" required>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="choices[${choiceCount}][text]" placeholder="Choice ${choiceCount + 1}" required>
                    </div>
                </div>
            `;
            
            $('#choicesContainer').append(choiceHtml);
            choiceCount++;
        }
        
        // Delete Question
        function deleteQuestion(questionId) {
            if (confirm('Are you sure you want to delete this question?')) {
                $.ajax({
                    url: '../controller/QuestionController.php?action=delete&ajax=1',
                    type: 'POST',
                    data: {question_id: questionId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Question deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the question.');
                    }
                });
            }
        }
        
        // Edit Question
        function editQuestion(questionId) {
            $.ajax({
                url: '../controller/QuestionController.php?action=get&ajax=1',
                type: 'GET',
                data: {question_id: questionId},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        populateEditModal(response.question);
                        $('#editQuestionModal').modal('show');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while loading the question.');
                }
            });
        }
        
        // Populate edit modal with question data
        function populateEditModal(question) {
            $('#edit_question_id').val(question.id);
            $('#edit_question_text').val(question.question_text);
            $('#edit_question_type').val(question.question_type);
            $('#edit_marks').val(question.marks);
            $('#edit_negative_marks').val(question.negative_marks);
            $('#edit_explanation').val(question.explanation);
            $('#edit_difficulty').val(question.difficulty);
            $('#edit_topic').val(question.topic);
            
            // Clear existing choices
            $('#editChoicesContainer').empty();
            
            // Add choices
            let choiceIndex = 0;
            question.choices.forEach(function(choice) {
                const isChecked = choice.is_correct ? 'checked' : '';
                const choiceHtml = `
                    <div class="choice-input mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="${question.question_type === 'single_choice' ? 'radio' : 'checkbox'}" 
                                           name="edit_correct_choice" value="${choiceIndex}" ${isChecked}>
                                </div>
                            </div>
                            <input type="text" class="form-control" name="edit_choices[${choiceIndex}][text]" 
                                   value="${choice.choice_text}" placeholder="Choice ${choiceIndex + 1}" required>
                        </div>
                    </div>
                `;
                $('#editChoicesContainer').append(choiceHtml);
                choiceIndex++;
            });
            
            editChoiceCount = choiceIndex;
        }
        
        // Add Edit Choice
        function addEditChoice() {
            if (editChoiceCount >= 5) {
                alert('Maximum 5 choices allowed');
                return;
            }
            
            const questionType = $('#edit_question_type').val();
            const inputType = questionType === 'single_choice' ? 'radio' : 'checkbox';
            const choiceHtml = `
                <div class="choice-input mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="${inputType}" name="edit_correct_choice" value="${editChoiceCount}">
                            </div>
                        </div>
                        <input type="text" class="form-control" name="edit_choices[${editChoiceCount}][text]" placeholder="Choice ${editChoiceCount + 1}" required>
                    </div>
                </div>
            `;
            
            $('#editChoicesContainer').append(choiceHtml);
            editChoiceCount++;
        }
    </script>

