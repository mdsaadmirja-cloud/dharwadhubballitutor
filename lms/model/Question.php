<?php
// lms/model/Question.php
require_once "../../DB Operations/dbconnection.php";

class Question {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Create a new question
    public function create($data) {
        $exam_id = (int)$data['exam_id'];
        $question_text = $this->db->real_escape_string($data['question_text']);
        $question_type = $this->db->real_escape_string($data['question_type']);
        $marks = (int)$data['marks'];
        $negative_marks = (float)$data['negative_marks'];
        $explanation = $this->db->real_escape_string($data['explanation']);
        $difficulty = $this->db->real_escape_string($data['difficulty']);
        $topic = $this->db->real_escape_string($data['topic']);
        
        $sql = "INSERT INTO questions (exam_id, question_text, question_type, marks, negative_marks, 
                explanation, difficulty, topic) 
                VALUES ($exam_id, '$question_text', '$question_type', $marks, $negative_marks, 
                '$explanation', '$difficulty', '$topic')";
        
        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Get question by ID
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM questions WHERE id = $id";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get questions for an exam
    public function getByExamId($exam_id, $randomize = false) {
        $exam_id = (int)$exam_id;
        $sql = "SELECT * FROM questions WHERE exam_id = $exam_id";
        
        error_log($sql);
        
        if ($randomize) {
            $sql .= " ORDER BY RAND()";
        } else {
            $sql .= " ORDER BY id ASC";
        }
        
        $result = $this->db->query($sql);
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
        return $questions;
    }
    
    // Get question with choices
    public function getWithChoices($id, $randomize_choices = false) {
        $id = (int)$id;
        $question = $this->getById($id);
        
        if (!$question) {
            return null;
        }
        
        $sql = "SELECT * FROM question_choices WHERE question_id = $id";
        
        if ($randomize_choices) {
            $sql .= " ORDER BY RAND()";
        } else {
            $sql .= " ORDER BY order_index ASC, id ASC";
        }
        
        $result = $this->db->query($sql);
        $choices = [];
        while ($row = $result->fetch_assoc()) {
            
            $choices[] = $row;
        }
        
        $question['choices'] = $choices;
        return $question;
    }
    
    // Get questions with choices for an exam
    public function getExamQuestionsWithChoices($exam_id, $randomize_questions = false, $randomize_choices = false) {
        $questions = $this->getByExamId($exam_id, $randomize_questions);
        
        foreach ($questions as &$question) {
            $sql = "SELECT * FROM question_choices WHERE question_id = {$question['id']}";
            error_log($sql);
            if ($randomize_choices) {
                $sql .= " ORDER BY RAND()";
            } else {
                $sql .= " ORDER BY order_index ASC, id ASC";
            }
            
            $result = $this->db->query($sql);
            $choices = [];
            while ($row = $result->fetch_assoc()) {
                $choices[] = $row;
            }
            
            $question['choices'] = $choices;
        }
        
        return $questions;
    }
    
    // Update question
    public function update($id, $data) {
        $id = (int)$id;
        $question_text = $this->db->real_escape_string($data['question_text']);
        $question_type = $this->db->real_escape_string($data['question_type']);
        $marks = (int)$data['marks'];
        $negative_marks = (float)$data['negative_marks'];
        $explanation = $this->db->real_escape_string($data['explanation']);
        $difficulty = $this->db->real_escape_string($data['difficulty']);
        $topic = $this->db->real_escape_string($data['topic']);
        
        $sql = "UPDATE questions SET 
                question_text = '$question_text', 
                question_type = '$question_type', 
                marks = $marks, 
                negative_marks = $negative_marks, 
                explanation = '$explanation', 
                difficulty = '$difficulty', 
                topic = '$topic',
                updated_at = CURRENT_TIMESTAMP
                WHERE id = $id";
        
        return $this->db->query($sql);
    }
    
    // Delete question
    public function delete($id) {
        $id = (int)$id;
        $sql = "DELETE FROM questions WHERE id = $id";
        return $this->db->query($sql);
    }
    
    // Add choice to question
    public function addChoice($question_id, $choice_text, $is_correct = false, $order_index = 0) {
        $question_id = (int)$question_id;
        $choice_text = $this->db->real_escape_string($choice_text);
        $is_correct = $is_correct ? 1 : 0;
        $order_index = (int)$order_index;
        
        $sql = "INSERT INTO question_choices (question_id, choice_text, is_correct, order_index) 
                VALUES ($question_id, '$choice_text', $is_correct, $order_index)";
        error_log($sql);
        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Update choice
    public function updateChoice($choice_id, $choice_text, $is_correct = false, $order_index = 0) {
        $choice_id = (int)$choice_id;
        $choice_text = $this->db->real_escape_string($choice_text);
        $is_correct = $is_correct ? 1 : 0;
        $order_index = (int)$order_index;
        
        $sql = "UPDATE question_choices SET 
                choice_text = '$choice_text', 
                is_correct = $is_correct, 
                order_index = $order_index
                WHERE id = $choice_id";
        
        return $this->db->query($sql);
    }
    
    // Delete choice
    public function deleteChoice($choice_id) {
        $choice_id = (int)$choice_id;
        $sql = "DELETE FROM question_choices WHERE id = $choice_id";
        return $this->db->query($sql);
    }
    
    // Get choices for a question
    public function getChoices($question_id) {
        $question_id = (int)$question_id;
        $sql = "SELECT * FROM question_choices WHERE question_id = $question_id ORDER BY order_index ASC, id ASC";
        $result = $this->db->query($sql);
        $choices = [];
        while ($row = $result->fetch_assoc()) {
            $choices[] = $row;
        }
        return $choices;
    }
    function cleanText($text) {
    // Remove control characters and trim whitespace
    error_log($text);
    return preg_replace('/[^\P{C}\n]+/u', '', trim($text));
}
    // Bulk create questions from array
    public function bulkCreate($exam_id, $questions_data) {
        $this->db->autocommit(false);
        $success_count = 0;
        $errors = [];
        
        try {
            foreach ($questions_data as $index => $question_data) {
                $question_id = $this->create($question_data);
                
                if ($question_id) {
                    // Add choices if provided
                    if (isset($question_data['choices']) && is_array($question_data['choices'])) {
                        foreach ($question_data['choices'] as $choice_data) {
                            $this->addChoice(
                                $question_id,
                                $this->cleanText($choice_data['text']),
                                $choice_data['is_correct'],
                                $choice_data['order'] ?? 0
                            );
                        }
                    }
                    $success_count++;
                } else {
                    $errors[] = "Failed to create question at index $index";
                }
            }
            
            $this->db->commit();
            return ['success_count' => $success_count, 'errors' => $errors];
            
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success_count' => 0, 'errors' => ['Database error: ' . $e->getMessage()]];
        } finally {
            $this->db->autocommit(true);
        }
    }
    
    // Get question statistics
    public function getQuestionStats($question_id) {
        $question_id = (int)$question_id;
        
        $sql = "SELECT 
                    COUNT(sa.id) as total_attempts,
                    COUNT(CASE WHEN sa.is_correct = 1 THEN 1 END) as correct_attempts,
                    AVG(sa.marks_obtained) as average_marks
                FROM student_answers sa 
                WHERE sa.question_id = $question_id";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
}
?>
