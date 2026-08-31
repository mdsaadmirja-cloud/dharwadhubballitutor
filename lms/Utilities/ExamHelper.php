<?php
// lms/Utilities/ExamHelper.php
require_once "../../DB Operations/dbconnection.php";

class ExamHelper {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Generate unique exam code
    public static function generateExamCode($title) {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $title), 0, 3));
        $suffix = date('Ymd');
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        return $prefix . $suffix . $random;
    }
    
    // Validate exam timing
    public static function validateExamTiming($start_time, $end_time, $duration) {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $duration_minutes = (int)$duration;
        
        $time_diff = $end->getTimestamp() - $start->getTimestamp();
        $time_diff_minutes = $time_diff / 60;
        
        if ($time_diff_minutes < $duration_minutes) {
            return ['valid' => false, 'message' => 'Exam duration cannot be longer than the time window between start and end time'];
        }
        
        if ($start >= $end) {
            return ['valid' => false, 'message' => 'Start time must be before end time'];
        }
        
        return ['valid' => true, 'message' => 'Valid timing'];
    }
    
    // Calculate exam statistics
    public function calculateExamStatistics($exam_id) {
        $exam_id = (int)$exam_id;
        
        $sql = "SELECT 
                    COUNT(DISTINCT ea.id) as total_attempts,
                    COUNT(DISTINCT CASE WHEN ea.status = 'submitted' THEN ea.id END) as completed_attempts,
                    COUNT(DISTINCT CASE WHEN ea.status = 'auto_submitted' THEN ea.id END) as auto_submitted_attempts,
                    AVG(CASE WHEN ea.status IN ('submitted', 'auto_submitted') THEN ea.percentage END) as average_percentage,
                    MAX(CASE WHEN ea.status IN ('submitted', 'auto_submitted') THEN ea.percentage END) as highest_percentage,
                    MIN(CASE WHEN ea.status IN ('submitted', 'auto_submitted') THEN ea.percentage END) as lowest_percentage,
                    COUNT(DISTINCT CASE WHEN ea.status IN ('submitted', 'auto_submitted') AND ea.percentage >= e.pass_percentage THEN ea.id END) as passed_attempts,
                    AVG(CASE WHEN ea.status IN ('submitted', 'auto_submitted') THEN ea.time_taken END) as average_time_taken
                FROM exam_attempts ea 
                JOIN exams e ON ea.exam_id = e.id 
                WHERE ea.exam_id = $exam_id";
        
        $result = $this->db->query($sql);
        $stats = $result->fetch_assoc();
        
        if ($stats['total_attempts'] > 0) {
            $stats['pass_rate'] = ($stats['passed_attempts'] / $stats['completed_attempts']) * 100;
            $stats['completion_rate'] = ($stats['completed_attempts'] / $stats['total_attempts']) * 100;
        } else {
            $stats['pass_rate'] = 0;
            $stats['completion_rate'] = 0;
        }
        
        return $stats;
    }
    
    // Get question difficulty distribution
    public function getQuestionDifficultyDistribution($exam_id) {
        $exam_id = (int)$exam_id;
        
        $sql = "SELECT 
                    difficulty,
                    COUNT(*) as count,
                    AVG(marks) as avg_marks
                FROM questions 
                WHERE exam_id = $exam_id 
                GROUP BY difficulty";
        
        $result = $this->db->query($sql);
        $distribution = [];
        
        while ($row = $result->fetch_assoc()) {
            $distribution[$row['difficulty']] = $row;
        }
        
        return $distribution;
    }
    
    // Get topic-wise performance
    public function getTopicWisePerformance($exam_id) {
        $exam_id = (int)$exam_id;
        
        $sql = "SELECT 
                    q.topic,
                    COUNT(DISTINCT sa.id) as total_attempts,
                    COUNT(DISTINCT CASE WHEN sa.is_correct = 1 THEN sa.id END) as correct_attempts,
                    AVG(sa.marks_obtained) as avg_marks_obtained,
                    AVG(q.marks) as avg_total_marks
                FROM questions q
                LEFT JOIN student_answers sa ON q.id = sa.question_id
                WHERE q.exam_id = $exam_id AND q.topic IS NOT NULL AND q.topic != ''
                GROUP BY q.topic
                ORDER BY correct_attempts DESC";
        
        $result = $this->db->query($sql);
        $performance = [];
        
        while ($row = $result->fetch_assoc()) {
            if ($row['total_attempts'] > 0) {
                $row['accuracy'] = ($row['correct_attempts'] / $row['total_attempts']) * 100;
            } else {
                $row['accuracy'] = 0;
            }
            $performance[] = $row;
        }
        
        return $performance;
    }
    
    // Send exam notifications
    public function sendExamNotification($exam_id, $type, $user_ids = []) {
        $exam_id = (int)$exam_id;
        
        // Get exam details
        $exam_sql = "SELECT * FROM exams WHERE id = $exam_id";
        $exam_result = $this->db->query($exam_sql);
        $exam = $exam_result->fetch_assoc();
        
        if (!$exam) {
            return false;
        }
        
        $title = '';
        $message = '';
        
        switch ($type) {
            case 'exam_assigned':
                $title = 'New Exam Assigned';
                $message = "A new exam '{$exam['title']}' has been assigned to you. Start time: " . date('M d, Y H:i', strtotime($exam['start_time']));
                break;
            case 'exam_reminder':
                $title = 'Exam Reminder';
                $message = "Reminder: Exam '{$exam['title']}' starts in 1 hour at " . date('M d, Y H:i', strtotime($exam['start_time']));
                break;
            case 'result_available':
                $title = 'Exam Results Available';
                $message = "Results for exam '{$exam['title']}' are now available for viewing.";
                break;
        }
        
        // If no specific user IDs provided, get all assigned students
        if (empty($user_ids)) {
            $users_sql = "SELECT DISTINCT u.id 
                         FROM users u 
                         JOIN group_members gm ON u.id = gm.user_id 
                         JOIN exam_assignments ea ON gm.group_id = ea.group_id 
                         WHERE ea.exam_id = $exam_id AND u.role = 'student'";
            
            $users_result = $this->db->query($users_sql);
            $user_ids = [];
            
            while ($row = $users_result->fetch_assoc()) {
                $user_ids[] = $row['id'];
            }
        }
        
        // Insert notifications
        $this->db->autocommit(false);
        
        try {
            foreach ($user_ids as $user_id) {
                $user_id = (int)$user_id;
                $insert_sql = "INSERT INTO notifications (user_id, title, message, type) 
                              VALUES ($user_id, '$title', '$message', '$type')";
                $this->db->query($insert_sql);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        } finally {
            $this->db->autocommit(true);
        }
    }
    
    // Clean up expired exam sessions
    public function cleanupExpiredSessions() {
        $sql = "UPDATE exam_sessions 
                SET is_active = 0 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                AND is_active = 1";
        
        return $this->db->query($sql);
    }
    
    // Get exam analytics for dashboard
    public function getExamAnalytics($admin_id = null) {
        $where_clause = $admin_id ? "WHERE e.created_by = " . (int)$admin_id : "";
        
        $sql = "SELECT 
                    COUNT(DISTINCT e.id) as total_exams,
                    COUNT(DISTINCT CASE WHEN e.status = 'published' THEN e.id END) as published_exams,
                    COUNT(DISTINCT ea.id) as total_attempts,
                    COUNT(DISTINCT CASE WHEN ea.status = 'submitted' THEN ea.id END) as completed_attempts,
                    AVG(CASE WHEN ea.status = 'submitted' THEN ea.percentage END) as avg_performance
                FROM exams e
                LEFT JOIN exam_attempts ea ON e.id = ea.exam_id
                $where_clause";
        
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
    
    // Validate CSV upload format
    public static function validateCSVFormat($file_path) {
        $handle = fopen($file_path, 'r');
        if (!$handle) {
            return ['valid' => false, 'message' => 'Cannot open file'];
        }
        
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return ['valid' => false, 'message' => 'Empty file'];
        }
        
        $required_headers = ['question_text', 'question_type', 'marks', 'choice1', 'choice2', 'correct_answers'];
        $missing_headers = [];
        
        foreach ($required_headers as $required) {
            if (!in_array($required, $headers)) {
                $missing_headers[] = $required;
            }
        }
        
        fclose($handle);
        
        if (!empty($missing_headers)) {
            return ['valid' => false, 'message' => 'Missing required headers: ' . implode(', ', $missing_headers)];
        }
        
        return ['valid' => true, 'message' => 'Valid format'];
    }
    
    // Format time duration
    public static function formatDuration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return sprintf('%d:%02d', $minutes, $seconds);
        }
    }
    
    // Check if exam is currently active
    public function isExamActive($exam_id) {
        $exam_id = (int)$exam_id;
        
        $sql = "SELECT COUNT(*) as count 
                FROM exams 
                WHERE id = $exam_id 
                AND status = 'published' 
                AND start_time <= NOW() 
                AND end_time >= NOW()";
        
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    // Get exam access statistics
    public function getExamAccessStats($exam_id) {
        $exam_id = (int)$exam_id;
        
        $sql = "SELECT 
                    COUNT(DISTINCT gm.user_id) as total_assigned_students,
                    COUNT(DISTINCT ea.user_id) as students_attempted,
                    COUNT(DISTINCT CASE WHEN ea.status = 'submitted' THEN ea.user_id END) as students_completed
                FROM exam_assignments ea_assign
                JOIN group_members gm ON ea_assign.group_id = gm.group_id
                LEFT JOIN exam_attempts ea ON gm.user_id = ea.user_id AND ea.exam_id = $exam_id
                WHERE ea_assign.exam_id = $exam_id";
        
        $result = $this->db->query($sql);
        $stats = $result->fetch_assoc();
        
        if ($stats['total_assigned_students'] > 0) {
            $stats['attempt_rate'] = ($stats['students_attempted'] / $stats['total_assigned_students']) * 100;
            $stats['completion_rate'] = ($stats['students_completed'] / $stats['total_assigned_students']) * 100;
        } else {
            $stats['attempt_rate'] = 0;
            $stats['completion_rate'] = 0;
        }
        
        return $stats;
    }
}
?>
