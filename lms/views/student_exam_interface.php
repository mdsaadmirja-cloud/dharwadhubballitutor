<?php
// lms/views/student_exam_interface.php
session_start();
require_once '../model/Exam.php';
require_once '../model/ExamAttempt.php';


if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

$attempt_id = (int)($_GET['attempt_id'] ?? 0);
if (!$attempt_id) {
    header('Location: student_dashboard.php');
    exit;
}

$attemptModel = new ExamAttempt();
$attempt = $attemptModel->getById($attempt_id);

if (!$attempt || $attempt['user_id'] != $_SESSION['user']['id']) {
    header('Location: student_dashboard.php');
    exit;
}

if ($attempt['status'] !== 'in_progress') {
    header('Location: exam_result.php?attempt_id=' . $attempt_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam - <?php echo htmlspecialchars($attempt['exam_title']); ?></title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3c2da5db04.js" crossorigin="anonymous"></script>
    <style>
        /* Base Styles */
        body {
            /* Add padding to prevent content from being hidden by the fixed nav bar on mobile */
            padding-bottom: 150px; 
        }
        .exam-container {
            max-width: 1000px;
            margin: 20px auto;
        }
        .question-card {
            margin-bottom: 20px;
        }
        .choice-item {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .choice-item:hover {
            background-color: #f8f9fa;
        }
        .choice-item.selected {
            background-color: #e3f2fd !important;
            border-color: #2196f3 !important;
        }
        .timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            z-index: 1000;
        }

        /* --- Desktop Navigation Bar --- */
        .question-nav {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
        }
        .question-numbers-container {
            display: inline-block;
        }
        .nav-btn {
            margin: 0 5px;
        }
        .question-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 50%;
            margin: 2px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .question-number.current {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .question-number.answered {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        .question-number.marked {
            background-color: #ffc107;
            color: black;
            border-color: #ffc107;
        }

        /* --- Mobile Responsive Styles --- */
        @media (max-width: 768px) {
            body {
                /* Increase bottom padding to accommodate the taller mobile nav bar */
                padding-bottom: 180px; 
            }
            .exam-container {
                margin: 10px;
            }
            .timer {
                top: 10px;
                right: 10px;
                padding: 5px 10px;
                font-size: 0.9em;
            }

            /* --- Mobile Navigation Bar --- */
            .question-nav {
                /* Dock the nav bar to the bottom, full width */
                left: 0;
                bottom: 0;
                width: 100%;
                transform: none;
                border-radius: 0;
                flex-direction: column; /* Stack elements vertically */
                padding: 5px;
            }

            /* Make the question number list horizontally scrollable */
            .question-numbers-container {
                overflow-x: auto;
                white-space: nowrap;
                width: 100%;
                padding: 5px 0;
                -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
            }
             /* Hide scrollbar for a cleaner look */
            .question-numbers-container::-webkit-scrollbar {
                display: none;
            }

            /* Container for the action buttons */
            .action-buttons {
                display: flex;
                justify-content: space-between;
                width: 100%;
                padding-top: 5px;
            }
            .nav-btn {
                /* Make buttons take up available space */
                flex-grow: 1; 
                margin: 0 2px;
                font-size: 0.8em; /* Slightly smaller font */
                padding: 8px 5px;
            }
            .card-header h4 {
                font-size: 1.2rem;
            }
        }

    </style>
</head>
<body>
    <div class="timer" id="timer">
       <span id="timeDisplay"><i class="fa-solid fa-alarm-clock"></i>--:--</span>
    </div>
    
    <div class="container-fluid exam-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><?php echo htmlspecialchars($attempt['exam_title']); ?></h4>
                        <small class="text-muted">Duration: <?php echo $attempt['duration']; ?> minutes | Total Marks: <?php echo $attempt['total_marks']; ?></small>
                    </div>
                    <div class="card-body">
                        <div id="examContent">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p>Loading exam questions...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="question-nav">
        <div class="question-numbers-container">
          
        </div>
        <div class="action-buttons">
            <button class="btn btn-secondary nav-btn btn-sm" id="prevBtn" onclick="previousQuestion()"><i class="fa-solid fa-arrow-left"></i></button>
            <button class="btn btn-primary nav-btn btn-sm" id="nextBtn" onclick="nextQuestion()"><i class="fa-solid fa-arrow-right"></i></button>
            <button class="btn btn-warning nav-btn" id="markBtn" onclick="markForReview()"><i class="fa-regular fa-square-check"></i></button>
            <button class="btn btn-success nav-btn d-none" id="submitBtn" onclick="submitExam()">Submit</button>
        </div>
    </div>
    
    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Exam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit your exam?</p>
                    <p class="text-muted">Once submitted, you cannot make any changes.</p>
                    <div id="submitSummary"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="confirmSubmit()">Submit Exam</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // No changes are needed for the JavaScript logic.
        // The existing JS will work with the updated HTML and CSS.
        let examData = null;
        let currentQuestion = 0;
        let answers = {};
        let markedQuestions = new Set();
        let timeRemaining = 0;
        let timerInterval = null;
        
        function loadExamData() {
            $.ajax({
                url: '../controller/StudentExamController.php?action=get_exam_questions&attempt_id=<?php echo $attempt_id; ?>&ajax=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        examData = response;
                        timeRemaining = response.exam.duration * 60; // Convert to seconds
                        renderExam();
                        startTimer();
                        loadSavedAnswers();
                    } else {
                        alert('Error: ' + response.message);
                        //window.location.href = 'student_dashboard.php';
                    }
                },
                 error: function(xhr, status, error) {
            console.error("❌ AJAX Error:", status, error);
            console.log("Response Text:", xhr.responseText);
            alert('An error occurred while loading the exam.');
        }
            });
        }
        
        function renderExam() {
            if (!examData || !examData.questions) return;
            const questionNumbers = $('#questionNumbers');
            questionNumbers.empty();
            examData.questions.forEach((q, i) => {
                const number = $(`<span class="question-number" onclick="goToQuestion(${i})">${i + 1}</span>`);
                questionNumbers.append(number);
            });
            renderQuestion(0);
        }
        
        function renderQuestion(index) {
            if (!examData || !examData.questions[index]) return;
            const question = examData.questions[index];
            const container = $('#examContent');
            let html = `
                <div class="question-card">
                    <div class="card-header">
                        <h5>Question ${index + 1} of ${examData.questions.length}</h5>
                        <span class="badge badge-info">${question.marks} marks</span>
                        ${question.difficulty ? '<span class="badge badge-secondary">' + question.difficulty + '</span>' : ''}
                    </div>
                    <div class="card-body">
                        <p class="question-text">${question.question_text}</p>
                        <div class="choices-container">`;
            
            question.choices.forEach((choice, choiceIndex) => {
                const isSelected = answers[index] && answers[index].includes(parseInt(choice.id));
                html += `
                    <div class="choice-item ${isSelected ? 'selected' : ''}" onclick="selectChoice(${index}, ${choice.id})">
                        <input type="${question.question_type === 'single_choice' ? 'radio' : 'checkbox'}" 
                               name="question_${index}" value="${choice.id}" 
                               ${isSelected ? 'checked' : ''} style="display: none;">
                        ${choice.choice_text}
                    </div>`;
            });
            
            html += `</div></div></div>`;
            container.html(html);
            updateNavigation();
        }
        
        function selectChoice(questionIndex, choiceId) {
            if (!examData) return;
            const question = examData.questions[questionIndex];
            if (question.question_type === 'single_choice') {
                answers[questionIndex] = [choiceId, question.id];
            } else {
                if (!answers[questionIndex]) {
                    answers[questionIndex] = [];
                }
                const choiceIdx = answers[questionIndex].indexOf(choiceId);
                if (choiceIdx > -1) {
                    answers[questionIndex].splice(choiceIdx, 1);
                } else {
                    if (answers[questionIndex].length > 0) {
                        let qId = answers[questionIndex].pop();
                        answers[questionIndex].push(choiceId);
                        answers[questionIndex].push(qId);
                    } else {
                        answers[questionIndex].push(choiceId);
                        answers[questionIndex].push(question.id);
                    }
                }
            }
            saveAnswer(questionIndex, answers[questionIndex]);
            renderQuestion(questionIndex);
        }
        
        function saveAnswer(questionIndex, selectedChoices) {
            const questionId = examData.questions[questionIndex].id;
            $.ajax({
                url: '../controller/StudentExamController.php?action=save_answer&ajax=1',
                type: 'POST',
                data: {
                    attempt_id: <?php echo $attempt_id; ?>,
                    question_id: questionId,
                    selected_choices: selectedChoices
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) console.error('Failed to save answer:', response.message);
                },
                error: function() {
                    console.error('Error saving answer');
                }
            });
        }
        
        function loadSavedAnswers() { /* Logic to load answers if needed */ }
        
        function goToQuestion(index) {
            if (index >= 0 && index < examData.questions.length) {
                currentQuestion = index;
                renderQuestion(index);
            }
        }
        
        function nextQuestion() {
            if (currentQuestion < examData.questions.length - 1) {
                goToQuestion(currentQuestion + 1);
            }
        }
        
        function previousQuestion() {
            if (currentQuestion > 0) {
                goToQuestion(currentQuestion - 1);
            }
        }
        
        function markForReview() {
            if (markedQuestions.has(currentQuestion)) {
                markedQuestions.delete(currentQuestion);
            } else {
                markedQuestions.add(currentQuestion);
            }
            updateNavigation();
        }
        
        function updateNavigation() {
            $('.question-number').each(function(index) {
                $(this).removeClass('current answered marked');
                if (index === currentQuestion) $(this).addClass('current');
                else if (answers[index] && answers[index].length > 0) $(this).addClass('answered');
                else if (markedQuestions.has(index)) $(this).addClass('marked');
            });
            $('#prevBtn').prop('disabled', currentQuestion === 0);
            $('#nextBtn').prop('disabled', currentQuestion === examData.questions.length - 1);
            $('#markBtn').html(markedQuestions.has(currentQuestion) ?  '<i class="fa-solid fa-square-check"></i>': '<i class="fa-regular fa-square-check"></i>');
            updateSubmitVisibility();
        }

        function isAllAnswered() {
            if (!examData || !examData.questions) return false;
            const total = examData.questions.length;
            for (let i = 0; i < total; i++) {
                if (!answers[i] || answers[i].length === 0) {
                    return false;
                }
            }
            return true;
        }

        function updateSubmitVisibility() {
            const ready = isAllAnswered();
            const $btn = $('#submitBtn');
            if (ready) {
                $btn.removeClass('d-none');
            } else {
                $btn.addClass('d-none');
            }
        }
        
        function startTimer() {
            updateTimerDisplay();
            timerInterval = setInterval(function() {
                timeRemaining--;
                updateTimerDisplay();
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    autoSubmitExam();
                }
            }, 1000);
        }
        
        function updateTimerDisplay() {
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;
            let display = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            if (hours > 0) display = hours + ':' + display;
            $('#timeDisplay').text(display);
            
            if (timeRemaining <= 300) $('#timer').css('background-color', '#dc3545');
            else if (timeRemaining <= 600) $('#timer').css('background-color', '#ffc107');
        }
        
        function submitExam() {
            const summary = generateSubmitSummary();
            $('#submitSummary').html(summary);
            $('#submitModal').modal('show');
        }
        
        function confirmSubmit() {
            $('#submitModal').modal('hide');
            $.ajax({
                url: '../controller/StudentExamController.php?action=submit_exam&ajax=1',
                type: 'POST',
                data: { attempt_id: <?php echo $attempt_id; ?>, answers: answers },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        clearInterval(timerInterval);
                        alert('Exam submitted successfully!');
                        window.location.href = 'exam_result.php?attempt_id=<?php echo $attempt_id; ?>';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() { alert('An error occurred while submitting the exam.'); }
            });
        }
        
        function autoSubmitExam() {
            alert('Time is up! Your exam will be automatically submitted.');
            confirmSubmit();
        }
        
        function generateSubmitSummary() {
            const totalQuestions = examData.questions.length;
            const answeredQuestions = Object.keys(answers).filter(q => answers[q] && answers[q].length > 0).length;
            const markedCount = markedQuestions.size;
            return `
                <div class="alert alert-info">
                    <h6>Submission Summary:</h6>
                    <ul class="mb-0 list-unstyled">
                        <li><strong>Total Questions:</strong> ${totalQuestions}</li>
                        <li><strong>Answered:</strong> ${answeredQuestions}</li>
                        <li><strong>Unanswered:</strong> ${totalQuestions - answeredQuestions}</li>
                        <li><strong>Marked for Review:</strong> ${markedCount}</li>
                    </ul>
                </div>`;
        }
        
        $(document).ready(function() {
            loadExamData();
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = '';
            });
        });
    </script>
</body>
</html>