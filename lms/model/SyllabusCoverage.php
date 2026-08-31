<?php
// lms/model/SyllabusCoverage.php
require_once "../../DB Operations/dbconnection.php";

class SyllabusCoverage {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Create syllabus
    public function createSyllabus($data) {
        $title = $this->db->real_escape_string($data['title']);
        $description = isset($data['description']) ? $this->db->real_escape_string($data['description']) : null;
        $course_id = isset($data['course_id']) ? (int)$data['course_id'] : null;
        $total_topics = isset($data['total_topics']) ? (int)$data['total_topics'] : 0;
        $total_duration_hours = isset($data['total_duration_hours']) ? (int)$data['total_duration_hours'] : 0;
        $created_by = (int)$data['created_by'];
        
        $sql = "INSERT INTO syllabus (
                    title, description, course_id, total_topics, 
                    total_duration_hours, created_by
                ) VALUES (
                    '$title', " . 
                    ($description ? "'$description'" : 'NULL') . ", " .
                    ($course_id ? $course_id : 'NULL') . ", 
                    $total_topics, $total_duration_hours, $created_by
                )";
        
        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Add topic to syllabus
    public function addTopic($data) {
        $syllabus_id = (int)$data['syllabus_id'];
        $topic_name = $this->db->real_escape_string($data['topic_name']);
        $topic_description = isset($data['topic_description']) ? $this->db->real_escape_string($data['topic_description']) : null;
        $estimated_duration_hours = isset($data['estimated_duration_hours']) ? (int)$data['estimated_duration_hours'] : 1;
        $order_index = isset($data['order_index']) ? (int)$data['order_index'] : 0;
        $is_optional = isset($data['is_optional']) ? (int)$data['is_optional'] : 0;
        $prerequisites = isset($data['prerequisites']) ? $this->db->real_escape_string($data['prerequisites']) : null;
        
        $sql = "INSERT INTO syllabus_topics (
                    syllabus_id, topic_name, topic_description, 
                    estimated_duration_hours, order_index, is_optional, prerequisites
                ) VALUES (
                    $syllabus_id, '$topic_name', " . 
                    ($topic_description ? "'$topic_description'" : 'NULL') . ", 
                    $estimated_duration_hours, $order_index, $is_optional, " .
                    ($prerequisites ? "'$prerequisites'" : 'NULL') . "
                )";
        
        if ($this->db->query($sql)) {
            $this->updateSyllabusStats($syllabus_id);
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Get syllabus by ID
    public function getSyllabusById($id) {
        $id = (int)$id;
        $sql = "SELECT s.*, u.name as created_by_name
                FROM syllabus s
                LEFT JOIN users u ON s.created_by = u.id
                WHERE s.id = $id";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get all syllabi
    public function getAllSyllabi($created_by = null) {
        $sql = "SELECT s.*, u.name as created_by_name
                FROM syllabus s
                LEFT JOIN users u ON s.created_by = u.id
                WHERE 1=1";
        
        if ($created_by) {
            $created_by = (int)$created_by;
            $sql .= " AND s.created_by = $created_by";
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        $result = $this->db->query($sql);
        $syllabi = [];
        while ($row = $result->fetch_assoc()) {
            $syllabi[] = $row;
        }
        return $syllabi;
    }
    
    // Get topics for a syllabus
    public function getSyllabusTopics($syllabus_id) {
        $syllabus_id = (int)$syllabus_id;
        $sql = "SELECT * FROM syllabus_topics 
                WHERE syllabus_id = $syllabus_id 
                ORDER BY order_index ASC, id ASC";
        
        $result = $this->db->query($sql);
        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        return $topics;
    }
    
    // Mark topic as covered for a batch
    public function markTopicCovered($batch_id, $topic_id, $session_id = null, $coverage_data = []) {
        $batch_id = (int)$batch_id;
        $topic_id = (int)$topic_id;
        $session_id = $session_id ? (int)$session_id : null;
        $coverage_percentage = isset($coverage_data['coverage_percentage']) ? (float)$coverage_data['coverage_percentage'] : 100.00;
        $instructor_notes = isset($coverage_data['instructor_notes']) ? $this->db->real_escape_string($coverage_data['instructor_notes']) : null;
        $student_feedback = isset($coverage_data['student_feedback']) ? $this->db->real_escape_string($coverage_data['student_feedback']) : null;
        $homework_completion_rate = isset($coverage_data['homework_completion_rate']) ? (float)$coverage_data['homework_completion_rate'] : 0.00;
        $quiz_average_score = isset($coverage_data['quiz_average_score']) ? (float)$coverage_data['quiz_average_score'] : 0.00;
        $status = isset($coverage_data['status']) ? $this->db->real_escape_string($coverage_data['status']) : 'completed';
        
        $sql = "INSERT INTO syllabus_coverage (
                    batch_id, topic_id, covered_date, session_id, 
                    coverage_percentage, instructor_notes, student_feedback,
                    homework_completion_rate, quiz_average_score, status
                ) VALUES (
                    $batch_id, $topic_id, CURDATE(), " . 
                    ($session_id ? $session_id : 'NULL') . ", 
                    $coverage_percentage, " .
                    ($instructor_notes ? "'$instructor_notes'" : 'NULL') . ", " .
                    ($student_feedback ? "'$student_feedback'" : 'NULL') . ", 
                    $homework_completion_rate, $quiz_average_score, '$status'
                ) ON DUPLICATE KEY UPDATE 
                covered_date = CURDATE(),
                session_id = " . ($session_id ? $session_id : 'session_id') . ",
                coverage_percentage = $coverage_percentage,
                instructor_notes = " . ($instructor_notes ? "'$instructor_notes'" : 'instructor_notes') . ",
                student_feedback = " . ($student_feedback ? "'$student_feedback'" : 'student_feedback') . ",
                homework_completion_rate = $homework_completion_rate,
                quiz_average_score = $quiz_average_score,
                status = '$status'";
        
        if ($this->db->query($sql)) {
            $this->updateBatchProgress($batch_id);
            return true;
        }
        return false;
    }
    
    // Get syllabus coverage for a batch
    public function getBatchSyllabusCoverage($batch_id) {
        $batch_id = (int)$batch_id;
        
        $sql = "SELECT sc.*, st.topic_name, st.topic_description, 
                       st.estimated_duration_hours, st.order_index, st.is_optional,
                       cs.session_date, cs.session_title, u.name as instructor_name
                FROM syllabus_coverage sc
                JOIN syllabus_topics st ON sc.topic_id = st.id
                LEFT JOIN class_sessions cs ON sc.session_id = cs.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE sc.batch_id = $batch_id
                ORDER BY st.order_index ASC";
        
        $result = $this->db->query($sql);
        $coverage = [];
        while ($row = $result->fetch_assoc()) {
            $coverage[] = $row;
        }
        return $coverage;
    }
    
    // Get syllabus progress summary for a batch
    public function getBatchSyllabusProgress($batch_id) {
        $batch_id = (int)$batch_id;
        
        // Get batch syllabus
        $batch = $this->db->query("SELECT syllabus_id FROM student_groups WHERE id = $batch_id")->fetch_assoc();
        if (!$batch || !$batch['syllabus_id']) {
            return null;
        }
        
        $syllabus_id = $batch['syllabus_id'];
        
        // Get all topics for the syllabus
        $topics_result = $this->db->query("SELECT COUNT(*) as total_topics FROM syllabus_topics WHERE syllabus_id = $syllabus_id");
        $topics_data = $topics_result->fetch_assoc();
        
        // Get covered topics
        $covered_result = $this->db->query("SELECT 
            COUNT(*) as covered_topics,
            AVG(coverage_percentage) as avg_coverage_percentage,
            AVG(homework_completion_rate) as avg_homework_completion,
            AVG(quiz_average_score) as avg_quiz_score
            FROM syllabus_coverage sc
            JOIN syllabus_topics st ON sc.topic_id = st.id
            WHERE sc.batch_id = $batch_id AND st.syllabus_id = $syllabus_id");
        
        $covered_data = $covered_result->fetch_assoc();
        
        // Get topics by status
        $status_result = $this->db->query("SELECT 
            sc.status,
            COUNT(*) as count
            FROM syllabus_coverage sc
            JOIN syllabus_topics st ON sc.topic_id = st.id
            WHERE sc.batch_id = $batch_id AND st.syllabus_id = $syllabus_id
            GROUP BY sc.status");
        
        $status_data = [];
        while ($row = $status_result->fetch_assoc()) {
            $status_data[$row['status']] = $row['count'];
        }
        
        return [
            'total_topics' => $topics_data['total_topics'],
            'covered_topics' => $covered_data['covered_topics'] ?: 0,
            'remaining_topics' => $topics_data['total_topics'] - ($covered_data['covered_topics'] ?: 0),
            'completion_percentage' => $topics_data['total_topics'] > 0 ? 
                (($covered_data['covered_topics'] ?: 0) / $topics_data['total_topics']) * 100 : 0,
            'avg_coverage_percentage' => $covered_data['avg_coverage_percentage'] ?: 0,
            'avg_homework_completion' => $covered_data['avg_homework_completion'] ?: 0,
            'avg_quiz_score' => $covered_data['avg_quiz_score'] ?: 0,
            'status_breakdown' => $status_data
        ];
    }
    
    // Get upcoming topics for a batch
    public function getUpcomingTopics($batch_id, $limit = 5) {
        $batch_id = (int)$batch_id;
        $limit = (int)$limit;
        
        // Get batch syllabus
        $batch = $this->db->query("SELECT syllabus_id FROM student_groups WHERE id = $batch_id")->fetch_assoc();
        if (!$batch || !$batch['syllabus_id']) {
            return [];
        }
        
        $syllabus_id = $batch['syllabus_id'];
        
        $sql = "SELECT st.*, sc.status, sc.covered_date
                FROM syllabus_topics st
                LEFT JOIN syllabus_coverage sc ON st.id = sc.topic_id AND sc.batch_id = $batch_id
                WHERE st.syllabus_id = $syllabus_id
                AND (sc.status IS NULL OR sc.status = 'not_started')
                ORDER BY st.order_index ASC
                LIMIT $limit";
        
        $result = $this->db->query($sql);
        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        return $topics;
    }
    
    // Get completed topics for a batch
    public function getCompletedTopics($batch_id, $limit = 10) {
        $batch_id = (int)$batch_id;
        $limit = (int)$limit;
        
        $sql = "SELECT sc.*, st.topic_name, st.topic_description, 
                       cs.session_date, cs.session_title, u.name as instructor_name
                FROM syllabus_coverage sc
                JOIN syllabus_topics st ON sc.topic_id = st.id
                LEFT JOIN class_sessions cs ON sc.session_id = cs.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE sc.batch_id = $batch_id 
                AND sc.status = 'completed'
                ORDER BY sc.covered_date DESC
                LIMIT $limit";
        
        $result = $this->db->query($sql);
        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        return $topics;
    }
    
    // Update syllabus statistics
    private function updateSyllabusStats($syllabus_id) {
        $syllabus_id = (int)$syllabus_id;
        
        // Count topics and calculate total duration
        $stats_result = $this->db->query("SELECT 
            COUNT(*) as total_topics,
            SUM(estimated_duration_hours) as total_duration
            FROM syllabus_topics WHERE syllabus_id = $syllabus_id");
        
        $stats_data = $stats_result->fetch_assoc();
        
        // Update syllabus
        $sql = "UPDATE syllabus SET 
                total_topics = {$stats_data['total_topics']},
                total_duration_hours = {$stats_data['total_duration']}
                WHERE id = $syllabus_id";
        
        $this->db->query($sql);
    }
    
    // Update batch progress
    private function updateBatchProgress($batch_id) {
        $batch_id = (int)$batch_id;
        
        $progress = $this->getBatchSyllabusProgress($batch_id);
        if ($progress) {
            $sql = "UPDATE batch_progress SET 
                    total_topics_covered = {$progress['covered_topics']},
                    total_topics_remaining = {$progress['remaining_topics']},
                    syllabus_completion_percentage = {$progress['completion_percentage']}
                    WHERE batch_id = $batch_id";
            
            $this->db->query($sql);
        }
    }
    
    // Get student-specific syllabus progress
    public function getStudentSyllabusProgress($student_id, $batch_id) {
        $student_id = (int)$student_id;
        $batch_id = (int)$batch_id;
        
        // Get batch syllabus
        $batch = $this->db->query("SELECT syllabus_id FROM student_groups WHERE id = $batch_id")->fetch_assoc();
        if (!$batch || !$batch['syllabus_id']) {
            return null;
        }
        
        $syllabus_id = $batch['syllabus_id'];
        
        // Get student's completion status for each topic
        $sql = "SELECT st.*, sc.status, sc.covered_date, sc.coverage_percentage,
                       sc.homework_completion_rate, sc.quiz_average_score
                FROM syllabus_topics st
                LEFT JOIN syllabus_coverage sc ON st.id = sc.topic_id AND sc.batch_id = $batch_id
                WHERE st.syllabus_id = $syllabus_id
                ORDER BY st.order_index ASC";
        
        $result = $this->db->query($sql);
        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        
        // Calculate student-specific metrics
        $total_topics = count($topics);
        $completed_topics = count(array_filter($topics, function($topic) {
            return $topic['status'] === 'completed';
        }));
        
        return [
            'topics' => $topics,
            'total_topics' => $total_topics,
            'completed_topics' => $completed_topics,
            'completion_percentage' => $total_topics > 0 ? ($completed_topics / $total_topics) * 100 : 0
        ];
    }
    
    // Delete syllabus
    public function deleteSyllabus($id) {
        $id = (int)$id;
        
        // Delete related records
        $this->db->query("DELETE FROM syllabus_coverage WHERE topic_id IN (SELECT id FROM syllabus_topics WHERE syllabus_id = $id)");
        $this->db->query("DELETE FROM syllabus_topics WHERE syllabus_id = $id");
        
        // Delete syllabus
        $sql = "DELETE FROM syllabus WHERE id = $id";
        return $this->db->query($sql);
    }
    
    // Delete topic
    public function deleteTopic($id) {
        $id = (int)$id;
        
        // Get syllabus_id for stats update
        $topic = $this->db->query("SELECT syllabus_id FROM syllabus_topics WHERE id = $id")->fetch_assoc();
        
        // Delete related records
        $this->db->query("DELETE FROM syllabus_coverage WHERE topic_id = $id");
        
        // Delete topic
        $sql = "DELETE FROM syllabus_topics WHERE id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $topic) {
            $this->updateSyllabusStats($topic['syllabus_id']);
        }
        
        return $result;
    }
}
?>
