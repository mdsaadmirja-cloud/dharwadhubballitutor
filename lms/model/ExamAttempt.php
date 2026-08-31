<?php
// lms/model/ExamAttempt.php
require_once "../../DB Operations/dbconnection.php";

class ExamAttempt {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Start a new exam attempt
    public function start($exam_id, $user_id, $ip_address = null, $user_agent = null) {
        $exam_id = (int)$exam_id;
        $user_id = (int)$user_id;
        $ip_address = $ip_address ? $this->db->real_escape_string($ip_address) : $_SERVER['REMOTE_ADDR'];
        $user_agent = $user_agent ? $this->db->real_escape_string($user_agent) : $_SERVER['HTTP_USER_AGENT'];
        $session_id = session_id();
        
        // Check if attempt already exists
        $existing = $this->getByExamAndUser($exam_id, $user_id);
        if ($existing) {
            return $existing['id'];
        }
        
        $sql = "INSERT INTO exam_attempts (exam_id, user_id, ip_address, user_agent, session_id) 
                VALUES ($exam_id, $user_id, '$ip_address', '$user_agent', '$session_id')";
        
        if ($this->db->query($sql)) {
            $attempt_id = $this->db->insert_id;
            
            // Create exam session
            $this->createSession($attempt_id);
            
            return $attempt_id;
        }
        return false;
    }
    
    // Get attempt by ID
    public function getById($id) {
        $id = (int)$id;
        error_log($id.':this is in the function');
        $sql = "SELECT ea.*, e.title as exam_title, e.duration, e.total_marks, e.pass_percentage,
                       u.name as student_name, u.email as student_email
                FROM exam_attempts ea 
                JOIN exams e ON ea.exam_id = e.id 
                JOIN users u ON ea.user_id = u.id 
                WHERE ea.id = $id";
        error_log($sql);
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get attempt by exam and user
    public function getByExamAndUser($exam_id, $user_id) {
        $exam_id = (int)$exam_id;
        $user_id = (int)$user_id;
        
        $sql = "SELECT * FROM exam_attempts WHERE exam_id = $exam_id AND user_id = $user_id";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Submit exam attempt
    public function submit($attempt_id, $answers = []) {
        $attempt_id = (int)$attempt_id;
        $attempt = $this->getById($attempt_id);
        
        if (!$attempt) {
            return false;
        }
        
        $this->db->autocommit(false);
        
        try {
            // Calculate time taken
            $started_at = new DateTime($attempt['started_at']);
            $now = new DateTime();
            $time_taken = $now->getTimestamp() - $started_at->getTimestamp();
            
            // Process answers and calculate marks
            $total_marks = 0;
            $obtained_marks = 0;
            error_log("This is attempt id".$attempt_id);
            
            error_log(print_r($answers, true));
            foreach ($answers as $question_id => $selected_choices) {
                $question_id = array_pop($selected_choices); // removes the last element
                $selected_choices_json = json_encode($selected_choices);
                
                // Get question details
                $question_sql = "SELECT * FROM questions WHERE id = $question_id";
                $question_result = $this->db->query($question_sql);
                $question = $question_result->fetch_assoc();
                
                error_log($question_sql);
                
                if ($question) {
                    $total_marks += $question['marks'];
                    
                    // Check if answer is correct
                    $correct_choices_sql = "SELECT id FROM question_choices WHERE question_id = $question_id AND is_correct = 1";
                    $correct_result = $this->db->query($correct_choices_sql);
                    $correct_choices = [];
                    
                    error_log($correct_choices_sql);
                    while ($row = $correct_result->fetch_assoc()) {
                        $correct_choices[] = $row['id'];
                    }
                    
                    $is_correct = false;
                    $marks_obtained = 0;
                    
                    // Convert selected choices to integers for proper comparison
                    $selected_choices = array_map('intval', $selected_choices);
                    
                    if ($question['question_type'] === 'single_choice') {
                        // Single choice - exact match
                        if (count($selected_choices) === 1 && in_array($selected_choices[0], $correct_choices)) {
                            $is_correct = true;
                            $marks_obtained = $question['marks'];
                        } else if ($question['negative_marking'] > 0) {
                            $marks_obtained = -$question['negative_marks'];
                        }
                    } else {
                        // Multiple choice - all correct choices must be selected
                        sort($selected_choices);
                        sort($correct_choices);
                        error_log("These are the sorted arrays");
                        
                        if ($selected_choices == $correct_choices) {
                            $is_correct = true;
                            $marks_obtained = $question['marks'];
                        } else if ($question['negative_marking'] > 0) {
                            $marks_obtained = -$question['negative_marks'];
                        }
                    }
                    
                    $obtained_marks += $marks_obtained;
                    
                    // Save answer
                    $answer_sql = "INSERT INTO student_answers (attempt_id, question_id, selected_choices, is_correct, marks_obtained) 
                                   VALUES ($attempt_id, $question_id, '$selected_choices_json', " . ($is_correct ? 1 : 0) . ", $marks_obtained)
                                   ON DUPLICATE KEY UPDATE 
                                   selected_choices = '$selected_choices_json', 
                                   is_correct = " . ($is_correct ? 1 : 0) . ", 
                                   marks_obtained = $marks_obtained";
                    
                    error_log($answer_sql);
                    
                    $this->db->query($answer_sql);
                }
            }
            
            // Calculate percentage
            $percentage = $total_marks > 0 ? ($obtained_marks / $total_marks) * 100 : 0;
            
            // Update attempt
            $update_sql = "UPDATE exam_attempts SET 
                           submitted_at = NOW(), 
                           time_taken = $time_taken, 
                           total_marks = $total_marks, 
                           obtained_marks = $obtained_marks, 
                           percentage = $percentage, 
                           status = 'submitted'
                           WHERE id = $attempt_id";
            
            $this->db->query($update_sql);
            
            // Deactivate session
            $this->deactivateSession($attempt_id);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        } finally {
            $this->db->autocommit(true);
        }
    }
    
    // Auto-submit exam (when time expires)
    public function autoSubmit($attempt_id) {
        $attempt_id = (int)$attempt_id;
        $attempt = $this->getById($attempt_id);
        
        if (!$attempt || $attempt['status'] !== 'in_progress') {
            return false;
        }
        
        // Get all saved answers
        $answers_sql = "SELECT question_id, selected_choices FROM student_answers WHERE attempt_id = $attempt_id";
        $answers_result = $this->db->query($answers_sql);
        $answers = [];
        error_log($answers_sql);
        while ($row = $answers_result->fetch_assoc()) {
            $answers[$row['question_id']] = json_decode($row['selected_choices'], true);
        }
        
        // Submit with existing answers
        $this->submit($attempt_id, $answers);
        
        // Update status to auto_submitted
        $sql = "UPDATE exam_attempts SET status = 'auto_submitted' WHERE id = $attempt_id";
        $this->db->query($sql);
        
        return true;
    }
    
    // Save answer for a question
    public function saveAnswer($attempt_id, $question_id, $selected_choices) {
        $attempt_id = (int)$attempt_id;
        $question_id = (int)$question_id;
        $selected_choices_json = json_encode(array_slice($selected_choices, 0, -1));
         
        error_log(print_r($selected_choices, true));
          error_log(print_r($selected_choices_json, true));
        $sql = "INSERT INTO student_answers (attempt_id, question_id, selected_choices) 
                VALUES ($attempt_id, $question_id, '$selected_choices_json')
                ON DUPLICATE KEY UPDATE selected_choices = '$selected_choices_json'";
        
        error_log($sql);
        return $this->db->query($sql);
    }
    
    // Get answers for an attempt
    public function getAnswers($attempt_id) {
        $attempt_id = (int)$attempt_id;
        $sql = "SELECT sa.*, q.question_text, q.question_type, q.marks, q.explanation
                FROM student_answers sa 
                JOIN questions q ON sa.question_id = q.id 
                WHERE sa.attempt_id = $attempt_id";
        error_log($sql);
        $result = $this->db->query($sql);
        $answers = [];
        while ($row = $result->fetch_assoc()) {
            $row['selected_choices'] = json_decode($row['selected_choices'], true);
            $answers[] = $row;
        }
        return $answers;
    }
    
    // Create exam session
    private function createSession($attempt_id) {
        $attempt_id = (int)$attempt_id;
        $session_token = bin2hex(random_bytes(32));
        
        $sql = "INSERT INTO exam_sessions (attempt_id, session_token) VALUES ($attempt_id, '$session_token')";
        return $this->db->query($sql);
    }
    
    // Deactivate exam session
    private function deactivateSession($attempt_id) {
        $attempt_id = (int)$attempt_id;
        $sql = "UPDATE exam_sessions SET is_active = 0 WHERE attempt_id = $attempt_id";
        return $this->db->query($sql);
    }
    
    // Check if session is valid
    public function isSessionValid($attempt_id) {
        $attempt_id = (int)$attempt_id;
        $sql = "SELECT COUNT(*) as count FROM exam_sessions WHERE attempt_id = $attempt_id AND is_active = 1";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
    
    // Get attempts for an exam
    public function getByExamId($exam_id) {
        $exam_id = (int)$exam_id;
        $sql = "SELECT ea.*, u.name as student_name, u.email as student_email
                FROM exam_attempts ea 
                JOIN users u ON ea.user_id = u.id 
                WHERE ea.exam_id = $exam_id 
                ORDER BY ea.started_at DESC";
        
        $result = $this->db->query($sql);
        $attempts = [];
        while ($row = $result->fetch_assoc()) {
            $attempts[] = $row;
        }
        return $attempts;
    }
    
    // Get attempts by user
    public function getByUserId($user_id) {
        $user_id = (int)$user_id;
        $sql = "SELECT ea.*, e.title as exam_title, e.code as exam_code
                FROM exam_attempts ea 
                JOIN exams e ON ea.exam_id = e.id 
                WHERE ea.user_id = $user_id 
                ORDER BY ea.started_at DESC";
        
        $result = $this->db->query($sql);
        $attempts = [];
        while ($row = $result->fetch_assoc()) {
            $attempts[] = $row;
        }
        return $attempts;
    }
    
    // Check if user can retake exam
    public function canRetake($exam_id, $user_id) {
        $exam_id = (int)$exam_id;
        $user_id = (int)$user_id;
        
        // Check if exam allows re-exam
        $exam_sql = "SELECT allow_re_exam FROM exams WHERE id = $exam_id";
        $exam_result = $this->db->query($exam_sql);
        $exam = $exam_result->fetch_assoc();
        
        if (!$exam || !$exam['allow_re_exam']) {
            return false;
        }
        
        // Check if user has already attempted
        $attempt_sql = "SELECT COUNT(*) as count FROM exam_attempts WHERE exam_id = $exam_id AND user_id = $user_id";
        $attempt_result = $this->db->query($attempt_sql);
        $attempt = $attempt_result->fetch_assoc();
        
        return $attempt['count'] == 0;
    }
}
?>
