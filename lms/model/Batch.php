<?php
// lms/model/Batch.php
require_once "../../DB Operations/dbconnection.php";

class Batch {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Create a new batch
    public function create($data) {
        $name = $this->db->real_escape_string($data['name']);
        $description = $this->db->real_escape_string($data['description']);
        $batch_start_date = $this->db->real_escape_string($data['batch_start_date']);
        $batch_end_date = $this->db->real_escape_string($data['batch_end_date']);
        $total_duration_days = (int)$data['total_duration_days'];
        $class_schedule = $this->db->real_escape_string($data['class_schedule']);
        $syllabus_id = isset($data['syllabus_id']) ? (int)$data['syllabus_id'] : null;
        $instructor_id = isset($data['instructor_id']) ? (int)$data['instructor_id'] : null;
        $max_students = isset($data['max_students']) ? (int)$data['max_students'] : 30;
        $fees = isset($data['fees']) ? (float)$data['fees'] : 0.00;
        $created_by = (int)$data['created_by'];
        
        $sql = "INSERT INTO student_groups (
                    name, description, batch_start_date, batch_end_date, 
                    total_duration_days, class_schedule, syllabus_id, 
                    instructor_id, max_students, fees, created_by
                ) VALUES (
                    '$name', '$description', '$batch_start_date', '$batch_end_date',
                    $total_duration_days, '$class_schedule', " . 
                    ($syllabus_id ? $syllabus_id : 'NULL') . ", " .
                    ($instructor_id ? $instructor_id : 'NULL') . ", 
                    $max_students, $fees, $created_by
                )";
        
        if ($this->db->query($sql)) {
            $batch_id = $this->db->insert_id;
            $this->initializeBatchProgress($batch_id);
            return $batch_id;
        }
        return false;
    }
    
    // Initialize batch progress tracking
    private function initializeBatchProgress($batch_id) {
        $sql = "INSERT INTO batch_progress (batch_id) VALUES ($batch_id)";
        $this->db->query($sql);
    }
    
    // Get batch by ID with full details
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT sg.*, u.name as created_by_name, 
                       instructor.name as instructor_name,
                       s.title as syllabus_title,
                       bp.total_sessions_scheduled,
                       bp.total_sessions_completed,
                       bp.syllabus_completion_percentage,
                       bp.average_attendance_rate,
                       bp.overall_batch_performance
                FROM student_groups sg 
                LEFT JOIN users u ON sg.created_by = u.id 
                LEFT JOIN users instructor ON sg.instructor_id = instructor.id
                LEFT JOIN syllabus s ON sg.syllabus_id = s.id
                LEFT JOIN batch_progress bp ON sg.id = bp.batch_id
                WHERE sg.id = $id";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get all batches with progress information
    public function getAll($created_by = null) {
        $sql = "SELECT sg.*, u.name as created_by_name,
                       instructor.name as instructor_name,
                       s.title as syllabus_title,
                       COUNT(gm.user_id) as current_students,
                       bp.total_sessions_scheduled,
                       bp.total_sessions_completed,
                       bp.syllabus_completion_percentage,
                       bp.average_attendance_rate,
                       bp.overall_batch_performance,
                       bp.last_session_date,
                       bp.next_session_date
                FROM student_groups sg 
                LEFT JOIN users u ON sg.created_by = u.id 
                LEFT JOIN users instructor ON sg.instructor_id = instructor.id
                LEFT JOIN syllabus s ON sg.syllabus_id = s.id
                LEFT JOIN group_members gm ON sg.id = gm.group_id 
                LEFT JOIN batch_progress bp ON sg.id = bp.batch_id
                WHERE 1=1";
        
        if ($created_by) {
            $created_by = (int)$created_by;
            $sql .= " AND sg.created_by = $created_by";
        }
        
        $sql .= " GROUP BY sg.id ORDER BY sg.batch_start_date DESC, sg.created_at DESC";
        
        $result = $this->db->query($sql);
        $batches = [];
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row;
        }
        return $batches;
    }
    
    // Update batch information
    public function update($id, $data) {
        $id = (int)$id;
        $name = $this->db->real_escape_string($data['name']);
        $description = $this->db->real_escape_string($data['description']);
        $batch_start_date = $this->db->real_escape_string($data['batch_start_date']);
        $batch_end_date = $this->db->real_escape_string($data['batch_end_date']);
        $total_duration_days = (int)$data['total_duration_days'];
        $class_schedule = $this->db->real_escape_string($data['class_schedule']);
        $syllabus_id = isset($data['syllabus_id']) ? (int)$data['syllabus_id'] : null;
        $instructor_id = isset($data['instructor_id']) ? (int)$data['instructor_id'] : null;
        $max_students = isset($data['max_students']) ? (int)$data['max_students'] : 30;
        $fees = isset($data['fees']) ? (float)$data['fees'] : 0.00;
        $batch_status = $this->db->real_escape_string($data['batch_status']);
        
        $sql = "UPDATE student_groups SET 
                name = '$name', 
                description = '$description',
                batch_start_date = '$batch_start_date',
                batch_end_date = '$batch_end_date',
                total_duration_days = $total_duration_days,
                class_schedule = '$class_schedule',
                syllabus_id = " . ($syllabus_id ? $syllabus_id : 'NULL') . ",
                instructor_id = " . ($instructor_id ? $instructor_id : 'NULL') . ",
                max_students = $max_students,
                fees = $fees,
                batch_status = '$batch_status'
                WHERE id = $id";
        error_log($sql);
        return $this->db->query($sql);
    }
    
    // Delete batch
    public function delete($id) {
        $id = (int)$id;
        
        // Delete related records first
        $this->db->query("DELETE FROM batch_progress WHERE batch_id = $id");
        $this->db->query("DELETE FROM student_batch_progress WHERE batch_id = $id");
        $this->db->query("DELETE FROM syllabus_coverage WHERE batch_id = $id");
        $this->db->query("DELETE FROM student_attendance WHERE session_id IN (SELECT id FROM class_sessions WHERE batch_id = $id)");
        $this->db->query("DELETE FROM class_sessions WHERE batch_id = $id");
        $this->db->query("DELETE FROM group_members WHERE group_id = $id");
        
        // Delete the batch
        $sql = "DELETE FROM student_groups WHERE id = $id";
        return $this->db->query($sql);
    }
    
    // Get batch statistics
    public function getBatchStatistics($batch_id) {
        $batch_id = (int)$batch_id;
        
        $sql = "SELECT 
                    COUNT(DISTINCT gm.user_id) as total_students,
                    COUNT(DISTINCT cs.id) as total_sessions,
                    COUNT(DISTINCT CASE WHEN cs.status = 'completed' THEN cs.id END) as completed_sessions,
                    COUNT(DISTINCT sc.topic_id) as topics_covered,
                    AVG(sbp.attendance_rate) as avg_attendance_rate,
                    AVG(sbp.average_score) as avg_batch_score
                FROM student_groups sg
                LEFT JOIN group_members gm ON sg.id = gm.group_id
                LEFT JOIN class_sessions cs ON sg.id = cs.batch_id
                LEFT JOIN syllabus_coverage sc ON sg.id = sc.batch_id AND sc.status = 'completed'
                LEFT JOIN student_batch_progress sbp ON sg.id = sbp.batch_id
                WHERE sg.id = $batch_id";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get upcoming sessions for a batch
    public function getUpcomingSessions($batch_id, $limit = 5) {
        $batch_id = (int)$batch_id;
        $limit = (int)$limit;
        
        $sql = "SELECT cs.*, st.topic_name, u.name as instructor_name
                FROM class_sessions cs
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE cs.batch_id = $batch_id 
                AND cs.session_date >= CURDATE()
                AND cs.status IN ('scheduled', 'postponed')
                ORDER BY cs.session_date ASC, cs.session_time ASC
                LIMIT $limit";
        
        $result = $this->db->query($sql);
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return $sessions;
    }
    
    // Get recent sessions for a batch
    public function getRecentSessions($batch_id, $limit = 5) {
        $batch_id = (int)$batch_id;
        $limit = (int)$limit;
        
        $sql = "SELECT cs.*, st.topic_name, u.name as instructor_name
                FROM class_sessions cs
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE cs.batch_id = $batch_id 
                AND cs.session_date <= CURDATE()
                ORDER BY cs.session_date DESC, cs.session_time DESC
                LIMIT $limit";
        
        $result = $this->db->query($sql);
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return $sessions;
    }
    
    // Update batch status based on dates
    public function updateBatchStatus($batch_id) {
        $batch_id = (int)$batch_id;
        
        $sql = "UPDATE student_groups SET 
                batch_status = CASE 
                    WHEN batch_start_date > CURDATE() THEN 'upcoming'
                    WHEN batch_end_date < CURDATE() THEN 'completed'
                    ELSE 'active'
                END
                WHERE id = $batch_id";
        
        return $this->db->query($sql);
    }
    
    // Get batches by status
    public function getBatchesByStatus($status, $created_by = null) {
        $status = $this->db->real_escape_string($status);
        $sql = "SELECT sg.*, u.name as created_by_name,
                       instructor.name as instructor_name,
                        s.title as syllabus_title,
                       COUNT(gm.user_id) as current_students,
                        bp.total_sessions_scheduled,
                       bp.total_sessions_completed,
                       bp.syllabus_completion_percentage,
                       bp.average_attendance_rate,
                       bp.overall_batch_performance,
                       bp.last_session_date,
                       bp.next_session_date
                FROM student_groups sg 
                LEFT JOIN users u ON sg.created_by = u.id 
                LEFT JOIN users instructor ON sg.instructor_id = instructor.id
                 LEFT JOIN syllabus s ON sg.syllabus_id = s.id
                LEFT JOIN group_members gm ON sg.id = gm.group_id 
                 LEFT JOIN batch_progress bp ON sg.id = bp.batch_id
                WHERE sg.batch_status = '$status'";
        
        if ($created_by) {
            $created_by = (int)$created_by;
            $sql .= " AND sg.created_by = $created_by";
        }
        
        $sql .= " GROUP BY sg.id ORDER BY sg.batch_start_date ASC";
        
        $result = $this->db->query($sql);
        $batches = [];
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row;
        }
        return $batches;
    }
    
    // Get batch calendar events
    public function getBatchCalendarEvents($batch_id, $start_date = null, $end_date = null) {
        $batch_id = (int)$batch_id;
        $start_date = $start_date ? $this->db->real_escape_string($start_date) : date('Y-m-01');
        $end_date = $end_date ? $this->db->real_escape_string($end_date) : date('Y-m-t');
        
        $sql = "SELECT cs.*, st.topic_name, u.name as instructor_name
                FROM class_sessions cs
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE cs.batch_id = $batch_id 
                AND cs.session_date BETWEEN '$start_date' AND '$end_date'
                ORDER BY cs.session_date ASC, cs.session_time ASC";
        
        $result = $this->db->query($sql);
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        return $events;
    }
    
    // Search batches by name or description
    public function searchBatches($searchTerm) {
        $searchTerm = $this->db->real_escape_string($searchTerm);
        $sql = "SELECT sg.*, u.name as created_by_name,
                       instructor.name as instructor_name,
                       s.title as syllabus_title,
                       COUNT(gm.user_id) as current_students
                FROM student_groups sg 
                LEFT JOIN users u ON sg.created_by = u.id 
                LEFT JOIN users instructor ON sg.instructor_id = instructor.id
                LEFT JOIN syllabus s ON sg.syllabus_id = s.id
                LEFT JOIN group_members gm ON sg.id = gm.group_id
                WHERE sg.name LIKE '%$searchTerm%' OR sg.description LIKE '%$searchTerm%'
                GROUP BY sg.id 
                ORDER BY sg.name ASC";

        $result = $this->db->query($sql);
        $batches = [];
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row;
        }
        return $batches;
    }
}
?>
