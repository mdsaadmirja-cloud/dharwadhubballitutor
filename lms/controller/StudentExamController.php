<?php
// lms/controller/StudentExamController.php

require_once '../model/Exam.php';
require_once '../model/Question.php';
require_once '../model/ExamAttempt.php';
require_once '../model/StudentGroup.php';
session_start();
class StudentExamController {
    private $examModel;
    private $questionModel;
    private $attemptModel;
    private $groupModel;
    
    public function __construct() {
        $this->examModel = new Exam();
        $this->questionModel = new Question();
        $this->attemptModel = new ExamAttempt();
        $this->groupModel = new StudentGroup();
    }
    
    // Get assigned exams for student
    public function getAssignedExams() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        $exams = $this->examModel->getAssignedExams($user_id);
        
        return ['success' => true, 'exams' => $exams];
    }
    
    // Start exam
    public function startExam() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $exam_id = (int)$_POST['exam_id'];
        $user_id = $_SESSION['user']['id'];
        
        // Check if exam is accessible to student
        if (!$this->examModel->isAccessibleToStudent($exam_id, $user_id)) {
            return ['success' => false, 'message' => 'You are not authorized to take this exam'];
        }
        
        // Check if exam is within time window
        $exam = $this->examModel->getById($exam_id);
        if (!$exam) {
            return ['success' => false, 'message' => 'Exam not found'];
        }
        
       date_default_timezone_set('Asia/Kolkata'); // set your timezone

$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
$start_time = new DateTime($exam['start_time'], new DateTimeZone('Asia/Kolkata'));
$end_time = new DateTime($exam['end_time'], new DateTimeZone('Asia/Kolkata'));
        
        if ($now < $start_time) {
            return ['success' => false, 'message' => 'Exam has not started yet'];
        }
        
        if ($now > $end_time) {
            return ['success' => false, 'message' => 'Exam has ended'];
        }
        
        // Check if user can retake exam
        if (!$this->attemptModel->canRetake($exam_id, $user_id)) {
            return ['success' => false, 'message' => 'You have already attempted this exam'];
        }
        
        // Start exam attempt
        $attempt_id = $this->attemptModel->start($exam_id, $user_id);
        
        if ($attempt_id) {
            return ['success' => true, 'message' => 'Exam started successfully', 'attempt_id' => $attempt_id];
        } else {
            return ['success' => false, 'message' => 'Failed to start exam'];
        }
    }
     public static function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } else if (is_string($mixed)) {
        // Convert only if not already UTF-8
        $mixed = mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
    }
    return $mixed;
}
    // Get exam questions for student
    public function getExamQuestions() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
           return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $attempt_id = (int)$_GET['attempt_id'];
        $attempt = $this->attemptModel->getById($attempt_id);
        
        if (!$attempt || $attempt['user_id'] != $_SESSION['user']['id']) {
          return ['success' => false, 'message' => 'Invalid attempt'];
        }
        
        if ($attempt['status'] !== 'in_progress') {
            return ['success' => false, 'message' => 'Exam is not in progress'];
        }
        
        // Check if session is valid
        if (!$this->attemptModel->isSessionValid($attempt_id)) {
            return ['success' => false, 'message' => 'Invalid session'];
        }
        
        $exam = $this->examModel->getById($attempt['exam_id']);
        $questions = $this->questionModel->getExamQuestionsWithChoices(
            $attempt['exam_id'], 
            $exam['randomize_questions'], 
            $exam['randomize_choices']
        );
        
        // Remove correct answer indicators for students
        foreach ($questions as &$question) {
            foreach ($question['choices'] as &$choice) {
               
                unset($choice['is_correct']);
            }
        }
        
      
        return [
            'success' => true, 
            'exam' => $exam,
            'attempt' => $attempt,
            'questions'=>$questions
        ];
        
         
    }
    
   
    
    // Save answer
    public function saveAnswer() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $attempt_id = (int)$_POST['attempt_id'];
        $question_id = (int)$_POST['question_id'];
        $selected_choices = $_POST['selected_choices'] ?? [];
        
        $attempt = $this->attemptModel->getById($attempt_id);
        
        if (!$attempt || $attempt['user_id'] != $_SESSION['user']['id']) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }
        
        if ($attempt['status'] !== 'in_progress') {
            return ['success' => false, 'message' => 'Exam is not in progress'];
        }
        
        // Check if session is valid
        if (!$this->attemptModel->isSessionValid($attempt_id)) {
            return ['success' => false, 'message' => 'Invalid session'];
        }
        
        if ($this->attemptModel->saveAnswer($attempt_id, $question_id, $selected_choices)) {
            return ['success' => true, 'message' => 'Answer saved successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to save answer'];
        }
    }
    
    // Submit exam
    public function submitExam() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $attempt_id = (int)$_POST['attempt_id'];
        $answers = $_POST['answers'] ?? [];
        
        $attempt = $this->attemptModel->getById($attempt_id);
        
        if (!$attempt || $attempt['user_id'] != $_SESSION['user']['id']) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }
        
        if ($attempt['status'] !== 'in_progress') {
            return ['success' => false, 'message' => 'Exam is not in progress'];
        }
        
        // Check if session is valid
        if (!$this->attemptModel->isSessionValid($attempt_id)) {
            return ['success' => false, 'message' => 'Invalid session'];
        }
        
        if ($this->attemptModel->submit($attempt_id, $answers)) {
            return ['success' => true, 'message' => 'Exam submitted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to submit exam'];
        }
    }
    
    // Get exam result
    public function getExamResult() {
       
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $attempt_id = (int)$_GET['attempt_id'];
        $attempt = $this->attemptModel->getById($attempt_id);
        
        
        if (!$attempt || $attempt['user_id'] != $_SESSION['user']['id']) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }
        
        if ($attempt['status'] === 'in_progress') {
            return ['success' => false, 'message' => 'Exam is still in progress'];
        }
        
        $exam = $this->examModel->getById($attempt['exam_id']);
        
        if (!$exam['show_results']) {
            return ['success' => false, 'message' => 'Results are not available yet'];
        }
         
       
        
        $answers = $this->attemptModel->getAnswers($attempt_id);

        // Determine whether question-wise details can be shown (only after exam end time)
        $canShowQuestionDetails = false;
        if (!empty($exam['end_time'])) {
            try {
                $tz = new \DateTimeZone('Asia/Kolkata');
                $now = new \DateTime('now', $tz);
                $end = new \DateTime($exam['end_time'], $tz);
                $canShowQuestionDetails = $now >= $end;
            } catch (\Exception $e) {
                $canShowQuestionDetails = false;
            }
        }
        
        // Add choice text for selected answers and correct answers
        foreach ($answers as &$answer) {
            $question = $this->questionModel->getWithChoices($answer['question_id']);
          
            if ($question) {
                // Add selected choice text
                $answer['selected_choice_texts'] = [];
                
              
                if ($answer['selected_choices'] && is_array($answer['selected_choices'])) {
                    
                    
                    foreach ($answer['selected_choices'] as $selected_choice_id) {
                        foreach ($question['choices'] as $choice) {
                            if ($choice['id'] == $selected_choice_id) {
                                $answer['selected_choice_texts'][] = $choice['choice_text'];
                                break;
                            }
                        }
                    }
                }
                
                // Add correct answers if explanations are enabled
                if ($exam['show_explanations']) {
                    $answer['correct_choices'] = [];
                    foreach ($question['choices'] as $choice) {
                        if ($choice['is_correct']) {
                            $answer['correct_choices'][] = $choice;
                        }
                    }
                }
            }
        }
        
        return [
            'success' => true,
            'exam' => $exam,
            'attempt' => $attempt,
            'answers' => $answers,
            'can_show_question_details' => $canShowQuestionDetails
        ];
    }
    
    // Get student's exam history
    public function getExamHistory() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        $attempts = $this->attemptModel->getByUserId($user_id);
        
        return ['success' => true, 'attempts' => $attempts];
    }
    
    // Check exam time remaining
    public function getTimeRemaining() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $attempt_id = (int)$_GET['attempt_id'];
        $attempt = $this->attemptModel->getById($attempt_id);
        
        if (!$attempt || $attempt['user_id'] != $_SESSION['user']) {
            return ['success' => false, 'message' => 'Invalid attempt'];
        }
        
        if ($attempt['status'] !== 'in_progress') {
            return ['success' => false, 'message' => 'Exam is not in progress'];
        }
        
        $exam = $this->examModel->getById($attempt['exam_id']);
        $started_at = new DateTime($attempt['started_at']);
        $now = new DateTime();
        $elapsed_seconds = $now->getTimestamp() - $started_at->getTimestamp();
        $total_seconds = $exam['duration'] * 60;
        $remaining_seconds = $total_seconds - $elapsed_seconds;
        
        if ($remaining_seconds <= 0) {
            // Auto-submit exam
            $this->attemptModel->autoSubmit($attempt_id);
            return ['success' => true, 'time_remaining' => 0, 'auto_submitted' => true];
        }
        
        return ['success' => true, 'time_remaining' => $remaining_seconds, 'auto_submitted' => false];
    }
    
    // Get exam instructions
    public function getExamInstructions() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'student') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $exam_id = (int)$_GET['exam_id'];
        $user_id = $_SESSION['user'];
        
        // Check if exam is accessible to student
        if (!$this->examModel->isAccessibleToStudent($exam_id, $user_id)) {
            return ['success' => false, 'message' => 'You are not authorized to take this exam'];
        }
        
        $exam = $this->examModel->getById($exam_id);
        
        if (!$exam) {
            return ['success' => false, 'message' => 'Exam not found'];
        }
        
        // Check if exam is within time window
        $now = new DateTime();
        $start_time = new DateTime($exam['start_time']);
        $end_time = new DateTime($exam['end_time']);
        
        if ($now < $start_time) {
            return ['success' => false, 'message' => 'Exam has not started yet', 'start_time' => $exam['start_time']];
        }
        
        if ($now > $end_time) {
            return ['success' => false, 'message' => 'Exam has ended', 'end_time' => $exam['end_time']];
        }
        
        // Check if user can retake exam
        if (!$this->attemptModel->canRetake($exam_id, $user_id)) {
            return ['success' => false, 'message' => 'You have already attempted this exam'];
        }
        
        return ['success' => true, 'exam' => $exam];
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new StudentExamController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'get_assigned_exams':
            $result = $controller->getAssignedExams();
            break;
        case 'start_exam':
            $result = $controller->startExam();
            break;
        case 'get_exam_questions':
            $result = $controller->getExamQuestions();
            break;
        case 'save_answer':
            $result = $controller->saveAnswer();
            break;
        case 'submit_exam':
            $result = $controller->submitExam();
            break;
        case 'get_exam_result':
            $result = $controller->getExamResult();
            break;
        case 'get_exam_history':
            $result = $controller->getExamHistory();
            break;
        case 'get_time_remaining':
            $result = $controller->getTimeRemaining();
            break;
        case 'get_exam_instructions':
            $result = $controller->getExamInstructions();
            break;
        default:
            $result = ['success' => false, 'message' => 'Invalid action'];
    }
    
    if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
?>
