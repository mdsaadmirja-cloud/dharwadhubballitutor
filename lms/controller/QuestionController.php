<?php
// lms/controller/QuestionController.php
session_start();
require_once '../model/Question.php';
require_once '../model/Exam.php';

class QuestionController {
    private $questionModel;
    private $examModel;
    
    public function __construct() {
        $this->questionModel = new Question();
        $this->examModel = new Exam();
    }
    
    // Create new question
    public function createQuestion() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $data = [
            'exam_id' => $_POST['exam_id'],
            'question_text' => $_POST['question_text'],
            'question_type' => $_POST['question_type'],
            'marks' => $_POST['marks'],
            'negative_marks' => $_POST['negative_marks'],
            'explanation' => $_POST['explanation'],
            'difficulty' => $_POST['difficulty'],
            'topic' => $_POST['topic']
        ];
        
        $question_id = $this->questionModel->create($data);
        
        if ($question_id) {
            // Add choices if provided
            error_log($_POST['choices']);
           $choices = json_decode($_POST['choices'], true);
            if (isset($_POST['choices']) && is_array( $choices)) {
                  error_log("inside");
                foreach ( $choices as $index => $choice) {
                    
                    error_log($choice['text']);
                    if (!empty($choice['text'])) {
                        $this->questionModel->addChoice(
                            $question_id,
                            $choice['text'],
                            $choice['is_correct'],
                            $index
                        );
                    }
                }
            }
            
            return ['success' => true, 'message' => 'Question created successfully', 'question_id' => $question_id];
        } else {
            return ['success' => false, 'message' => 'Failed to create question'];
        }
    }
    
    // Update question
    public function updateQuestion() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $question_id = (int)$_POST['question_id'];
        $data = [
            'question_text' => $_POST['question_text'],
            'question_type' => $_POST['question_type'],
            'marks' => $_POST['marks'],
            'negative_marks' => $_POST['negative_marks'],
            'explanation' => $_POST['explanation'],
            'difficulty' => $_POST['difficulty'],
            'topic' => $_POST['topic']
        ];
        
        if ($this->questionModel->update($question_id, $data)) {
            // Update choices if provided
             $choices = json_decode($_POST['choices'], true);
            if (isset($_POST['choices']) && is_array($choices)) {
                // Delete existing choices
                $existing_choices = $this->questionModel->getChoices($question_id);
                foreach ($existing_choices as $choice) {
                    $this->questionModel->deleteChoice($choice['id']);
                }
                
                // Add new choices
                foreach ($choices as $index => $choice) {
                    if (!empty($choice['text'])) {
                        $this->questionModel->addChoice(
                            $question_id,
                            $choice['text'],
                            $choice['is_correct'],
                            $index
                        );
                    }
                }
            }
            
            return ['success' => true, 'message' => 'Question updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update question'];
        }
    }
    
    // Delete question
    public function deleteQuestion() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $question_id = (int)$_POST['question_id'];
        
        if ($this->questionModel->delete($question_id)) {
            return ['success' => true, 'message' => 'Question deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete question'];
        }
    }
    
    // Get question details
    public function getQuestion() {
        $question_id = (int)$_GET['question_id'];
        $question = $this->questionModel->getWithChoices($question_id);
        
        if ($question) {
            return ['success' => true, 'question' => $question];
        } else {
            return ['success' => false, 'message' => 'Question not found'];
        }
    }
    
    // Get questions for exam
    public function getExamQuestions() {
        $exam_id = (int)$_GET['exam_id'];
        $randomize = isset($_GET['randomize']) && $_GET['randomize'] === 'true';
        $randomize_choices = isset($_GET['randomize_choices']) && $_GET['randomize_choices'] === 'true';
        
        $questions = $this->questionModel->getExamQuestionsWithChoices($exam_id, $randomize, $randomize_choices);
        
        return ['success' => true, 'questions' => $questions];
    }
    
    // Bulk upload questions from Excel/CSV
    public function bulkUploadQuestions() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'No file uploaded or upload error'];
        }
        
        $exam_id = (int)$_POST['exam_id'];
        $file = $_FILES['file'];
        
        // Validate file type
        $allowed_types = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if (!in_array($file['type'], $allowed_types)) {
            return ['success' => false, 'message' => 'Invalid file type. Please upload CSV or Excel file'];
        }
        
        // Process file
        $questions_data = $this->processUploadedFile($file['tmp_name'], $file['type']);
        
        if (empty($questions_data)) {
            return ['success' => false, 'message' => 'No valid questions found in file'];
        }
        
        // Validate questions data
        $validation_result = $this->validateQuestionsData($questions_data);
        if (!$validation_result['valid']) {
            return ['success' => false, 'message' => 'Validation failed', 'errors' => $validation_result['errors']];
        }
        
        // Add exam_id to each question
        foreach ($questions_data as &$question) {
            $question['exam_id'] = $exam_id;
        }
        
        // Bulk create questions
        $result = $this->questionModel->bulkCreate($exam_id, $questions_data);
        
        if ($result['success_count'] > 0) {
            return [
                'success' => true, 
                'message' => "Successfully uploaded {$result['success_count']} questions",
                'errors' => $result['errors']
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to upload any questions', 'errors' => $result['errors']];
        }
    }
    
    // Process uploaded file
    private function processUploadedFile($file_path, $file_type) {
        $questions_data = [];
        
        if (strpos($file_type, 'csv') !== false) {
            $questions_data = $this->processCSVFile($file_path);
        } else {
            // For Excel files, we would need PHPSpreadsheet library
            // For now, return empty array
            return [];
        }
        
        return $questions_data;
    }
    
    // Process CSV file
    private function processCSVFile($file_path) {
        $questions_data = [];
        $handle = fopen($file_path, 'r');

        $bom = fread($handle, 3);

        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        
        if ($handle === false) {
            return [];
        }
        
        // Read header row
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [];
        }
        
        // Expected headers
        $expected_headers = [
            'question_text', 'question_type', 'marks', 'negative_marks', 
            'explanation', 'difficulty', 'topic', 'choice1', 'choice2', 
            'choice3', 'choice4', 'choice5', 'correct_answers'
        ];
        
        // Map headers to indices
        $header_map = [];
        foreach ($expected_headers as $expected) {
            $index = array_search($expected, $headers);
            if ($index !== false) {
                $header_map[$expected] = $index;
            }
        }
        
        // Read data rows
        while (($row = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
            if (count($row) < count($expected_headers)) {
                continue; // Skip incomplete rows
            }
            
            $question_data = [
                'question_text' => $row[$header_map['question_text']] ?? '',
                'question_type' => $row[$header_map['question_type']] ?? 'single_choice',
                'marks' => (int)($row[$header_map['marks']] ?? 1),
                'negative_marks' => (float)($row[$header_map['negative_marks']] ?? 0),
                'explanation' => $row[$header_map['explanation']] ?? '',
                'difficulty' => $row[$header_map['difficulty']] ?? 'medium',
                'topic' => $row[$header_map['topic']] ?? '',
                'choices' => []
            ];
            
            // Process choices
            for ($i = 1; $i <= 5; $i++) {
                $choice_key = "choice$i";
                if (isset($header_map[$choice_key]) && !empty($row[$header_map[$choice_key]])) {
                    $question_data['choices'][] = [
                        'text' => $row[$header_map[$choice_key]],
                        'is_correct' => false,
                        'order' => $i - 1
                    ];
                }
            }
            
            // Process correct answers
            if (isset($header_map['correct_answers']) && !empty($row[$header_map['correct_answers']])) {
                $correct_answers = explode(',', $row[$header_map['correct_answers']]);
                foreach ($correct_answers as $correct_index) {
                    $correct_index = (int)trim($correct_index) - 1; // Convert to 0-based index
                    if (isset($question_data['choices'][$correct_index])) {
                        $question_data['choices'][$correct_index]['is_correct'] = true;
                    }
                }
            }
            
            $questions_data[] = $question_data;
        }
        
        fclose($handle);
        return $questions_data;
    }
    
    // Validate questions data
    private function validateQuestionsData($questions_data) {
        $errors = [];
        
        foreach ($questions_data as $index => $question) {
            $row_errors = [];
            
            // Validate required fields
            if (empty($question['question_text'])) {
                $row_errors[] = 'Question text is required';
            }
            
            if (!in_array($question['question_type'], ['single_choice', 'multiple_choice'])) {
                $row_errors[] = 'Invalid question type';
            }
            
            if (empty($question['choices']) || count($question['choices']) < 2) {
                $row_errors[] = 'At least 2 choices are required';
            }
            
            // Validate correct answers
            $correct_count = 0;
            foreach ($question['choices'] as $choice) {
                if ($choice['is_correct']) {
                    $correct_count++;
                }
            }
            
            if ($correct_count === 0) {
                $row_errors[] = 'At least one correct answer is required';
            }
            
            if ($question['question_type'] === 'single_choice' && $correct_count > 1) {
                $row_errors[] = 'Single choice questions can have only one correct answer';
            }
            
            if (!empty($row_errors)) {
                $errors["Row " . ($index + 1)] = $row_errors;
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    // Get question statistics
    public function getQuestionStatistics() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $question_id = (int)$_GET['question_id'];
        $stats = $this->questionModel->getQuestionStats($question_id);
        
        if ($stats) {
            return ['success' => true, 'statistics' => $stats];
        } else {
            return ['success' => false, 'message' => 'Failed to get question statistics'];
        }
    }
    
    // Download question template
    public function downloadTemplate() {
        $filename = "question_upload_template.csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'question_text', 'question_type', 'marks', 'negative_marks', 
            'explanation', 'difficulty', 'topic', 'choice1', 'choice2', 
            'choice3', 'choice4', 'choice5', 'correct_answers'
        ]);
        
        // Sample data
        fputcsv($output, [
            'What is the capital of India?',
            'single_choice',
            '1',
            '0.25',
            'New Delhi is the capital of India.',
            'easy',
            'Geography',
            'Mumbai',
            'New Delhi',
            'Kolkata',
            'Chennai',
            '',
            '2'
        ]);
        
        fputcsv($output, [
            'Which of the following are programming languages?',
            'multiple_choice',
            '2',
            '0.5',
            'Java, Python, and C++ are all programming languages.',
            'medium',
            'Programming',
            'Java',
            'Python',
            'HTML',
            'C++',
            'CSS',
            '1,2,4'
        ]);
        
        fclose($output);
        exit;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new QuestionController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = $controller->createQuestion();
            break;
        case 'update':
            $result = $controller->updateQuestion();
            break;
        case 'delete':
            $result = $controller->deleteQuestion();
            break;
        case 'get':
            $result = $controller->getQuestion();
            break;
        case 'get_exam_questions':
            $result = $controller->getExamQuestions();
            break;
        case 'bulk_upload':
            $result = $controller->bulkUploadQuestions();
            break;
        case 'get_statistics':
            $result = $controller->getQuestionStatistics();
            break;
        case 'download_template':
            $result = $controller->downloadTemplate();
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
