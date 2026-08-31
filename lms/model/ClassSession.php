<?php
// lms/model/ClassSession.php
require_once "../../DB Operations/dbconnection.php";

class ClassSession {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Create a new class session
    public function create($data) {
        $batch_id = (int)$data['batch_id'];
        $session_date = $this->db->real_escape_string($data['session_date']);
        $session_time = isset($data['session_time']) ? $this->db->real_escape_string($data['session_time']) : null;
        $duration_minutes = isset($data['duration_minutes']) ? (int)$data['duration_minutes'] : 60;
        $topic_id = isset($data['topic_id']) ? (int)$data['topic_id'] : null;
        $lesson_id = isset($data['lesson_id']) ? (int)$data['lesson_id'] : null;
        $instructor_id = (int)$data['instructor_id'];
        $session_type = $this->db->real_escape_string($data['session_type']);
        $session_title = $this->db->real_escape_string($data['session_title']);
        $session_description = isset($data['session_description']) ? $this->db->real_escape_string($data['session_description']) : null;
        $homework_assigned = isset($data['homework_assigned']) ? $this->db->real_escape_string($data['homework_assigned']) : null;
        $notes = isset($data['notes']) ? $this->db->real_escape_string($data['notes']) : null;
        
        $sql = "INSERT INTO class_sessions (
                    batch_id, session_date, session_time, duration_minutes,
                    topic_id, lesson_id, instructor_id, session_type,
                    session_title, session_description, homework_assigned, notes
                ) VALUES (
                    $batch_id, '$session_date', " . 
                    ($session_time ? "'$session_time'" : 'NULL') . ", 
                    $duration_minutes, " .
                    ($topic_id ? $topic_id : 'NULL') . ", " .
                    ($lesson_id ? $lesson_id : 'NULL') . ", 
                    $instructor_id, '$session_type',
                    '$session_title', " .
                    ($session_description ? "'$session_description'" : 'NULL') . ", " .
                    ($homework_assigned ? "'$homework_assigned'" : 'NULL') . ", " .
                    ($notes ? "'$notes'" : 'NULL') . "
                )";
        
        if ($this->db->query($sql)) {
            $session_id = $this->db->insert_id;
            $this->updateBatchProgress($batch_id);
            return $session_id;
        }
        return false;
    }
    
    // Get session by ID with full details
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT cs.*, sg.name as batch_name, st.topic_name, 
                       l.title as lesson_title, u.name as instructor_name
                FROM class_sessions cs
                LEFT JOIN student_groups sg ON cs.batch_id = sg.id
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN lessons l ON cs.lesson_id = l.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE cs.id = $id";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get sessions for a batch
    public function getSessionsByBatch($batch_id, $start_date = null, $end_date = null, $status = null) {
        $batch_id = (int)$batch_id;
        $sql = "SELECT cs.*, st.topic_name, l.title as lesson_title, u.name as instructor_name
                FROM class_sessions cs
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN lessons l ON cs.lesson_id = l.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE cs.batch_id = $batch_id";
        
        if ($start_date) {
            $start_date = $this->db->real_escape_string($start_date);
            $sql .= " AND cs.session_date >= '$start_date'";
        }
        
        if ($end_date) {
            $end_date = $this->db->real_escape_string($end_date);
            $sql .= " AND cs.session_date <= '$end_date'";
        }
        
        if ($status) {
            $status = $this->db->real_escape_string($status);
            $sql .= " AND cs.status = '$status'";
        }
        
        $sql .= " ORDER BY cs.session_date DESC, cs.session_time DESC";
        
        $result = $this->db->query($sql);
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return $sessions;
    }
    
    // Get all sessions with optional filters
    public function getAllSessions($filters = []) {
        $sql = "SELECT cs.*, sg.name as batch_name, st.topic_name, 
                       l.title as lesson_title, u.name as instructor_name
                FROM class_sessions cs
                LEFT JOIN student_groups sg ON cs.batch_id = sg.id
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN lessons l ON cs.lesson_id = l.id
                LEFT JOIN users u ON cs.instructor_id = u.id
                WHERE 1=1";

        if (!empty($filters['instructor_id'])) {
            $instructor_id = (int)$filters['instructor_id'];
            $sql .= " AND cs.instructor_id = $instructor_id";
        }

        if (!empty($filters['start_date'])) {
            $start_date = $this->db->real_escape_string($filters['start_date']);
            $sql .= " AND cs.session_date >= '$start_date'";
        }

        if (!empty($filters['end_date'])) {
            $end_date = $this->db->real_escape_string($filters['end_date']);
            $sql .= " AND cs.session_date <= '$end_date'";
        }

        if (!empty($filters['status'])) {
            $status = $this->db->real_escape_string($filters['status']);
            $sql .= " AND cs.status = '$status'";
        }

        $sql .= " ORDER BY cs.session_date DESC, cs.session_time DESC";

        $result = $this->db->query($sql);
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return $sessions;
    }
    
    // Update session
    public function update($id, $data) {
        $id = (int)$id;
        $session_date = $this->db->real_escape_string($data['session_date']);
        $session_time = isset($data['session_time']) ? $this->db->real_escape_string($data['session_time']) : null;
        $duration_minutes = isset($data['duration_minutes']) ? (int)$data['duration_minutes'] : 60;
        $topic_id = isset($data['topic_id']) ? (int)$data['topic_id'] : null;
        $lesson_id = isset($data['lesson_id']) ? (int)$data['lesson_id'] : null;
        $instructor_id = (int)$data['instructor_id'];
        $session_type = $this->db->real_escape_string($data['session_type']);
        $session_title = $this->db->real_escape_string($data['session_title']);
        $session_description = isset($data['session_description']) ? $this->db->real_escape_string($data['session_description']) : null;
        $homework_assigned = isset($data['homework_assigned']) ? $this->db->real_escape_string($data['homework_assigned']) : null;
        $notes = isset($data['notes']) ? $this->db->real_escape_string($data['notes']) : null;
        $status = isset($data['status']) ? $this->db->real_escape_string($data['status']) : 'scheduled';
        
        $sql = "UPDATE class_sessions SET 
                session_date = '$session_date',
                session_time = " . ($session_time ? "'$session_time'" : 'NULL') . ",
                duration_minutes = $duration_minutes,
                topic_id = " . ($topic_id ? $topic_id : 'NULL') . ",
                lesson_id = " . ($lesson_id ? $lesson_id : 'NULL') . ",
                instructor_id = $instructor_id,
                session_type = '$session_type',
                session_title = '$session_title',
                session_description = " . ($session_description ? "'$session_description'" : 'NULL') . ",
                homework_assigned = " . ($homework_assigned ? "'$homework_assigned'" : 'NULL') . ",
                notes = " . ($notes ? "'$notes'" : 'NULL') . ",
                status = '$status'
                WHERE id = $id";
        
        return $this->db->query($sql);
    }
    
    // Delete session
    public function delete($id) {
        $id = (int)$id;
        
        // Delete related attendance records
        $this->db->query("DELETE FROM student_attendance WHERE session_id = $id");
        
        // Delete the session
        $sql = "DELETE FROM class_sessions WHERE id = $id";
        return $this->db->query($sql);
    }
    
    // Mark session as completed
    public function markCompleted($id, $notes = null) {
        $id = (int)$id;
        $notes = $notes ? $this->db->real_escape_string($notes) : null;
        
        $sql = "UPDATE class_sessions SET 
                status = 'completed',
                notes = " . ($notes ? "'$notes'" : "NULL") . "
                WHERE id = $id";
        
        if ($this->db->query($sql)) {
            // Get batch_id to update progress
            $session = $this->getById($id);
            if ($session) {
                $this->updateBatchProgress($session['batch_id']);
            }
            return true;
        }
        return false;
    }
    
    // Take attendance for a session
    public function takeAttendance($session_id, $attendance_data) {
        $session_id = (int)$session_id;
        
        // Delete existing attendance records for this session
        $this->db->query("DELETE FROM student_attendance WHERE session_id = $session_id");
        
        $success = true;
        foreach ($attendance_data as $student_id => $attendance_info) {
            $student_id = (int)$student_id;
            $status = $this->db->real_escape_string($attendance_info['status']);
            $attendance_time = isset($attendance_info['attendance_time']) ? $this->db->real_escape_string($attendance_info['attendance_time']) : null;
            $notes = isset($attendance_info['notes']) ? $this->db->real_escape_string($attendance_info['notes']) : null;
            $marked_by = isset($attendance_info['marked_by']) ? (int)$attendance_info['marked_by'] : null;
            
            $sql = "INSERT INTO student_attendance (
                        session_id, student_id, attendance_status, 
                        attendance_time, notes, marked_by
                    ) VALUES (
                        $session_id, $student_id, '$status',
                        " . ($attendance_time ? "'$attendance_time'" : 'NULL') . ", " .
                        ($notes ? "'$notes'" : 'NULL') . ", " .
                        ($marked_by ? $marked_by : 'NULL') . "
                    )";
            
            if (!$this->db->query($sql)) {
                $success = false;
            }
        }
        
        if ($success) {
            // Mark session as attendance taken
            $this->db->query("UPDATE class_sessions SET attendance_taken = 1 WHERE id = $session_id");
            
            // Update student batch progress
            $this->updateStudentBatchProgress($session_id);
        }
        
        return $success;
    }
    
    // Get attendance for a session
    public function getSessionAttendance($session_id) {
        $session_id = (int)$session_id;
        
        $sql = "SELECT sa.*, u.name as student_name, u.email as student_email,
                       marker.name as marked_by_name
                FROM student_attendance sa
                JOIN users u ON sa.student_id = u.id
                LEFT JOIN users marker ON sa.marked_by = marker.id
                WHERE sa.session_id = $session_id
                ORDER BY u.name ASC";
        
        $result = $this->db->query($sql);
        $attendance = [];
        while ($row = $result->fetch_assoc()) {
            $attendance[] = $row;
        }
        return $attendance;
    }
    
    // Get student attendance summary
    public function getStudentAttendanceSummary($student_id, $batch_id = null) {
        $student_id = (int)$student_id;
        
        $sql = "SELECT sa.*, cs.session_date, cs.session_title, sg.name as batch_name
                FROM student_attendance sa
                JOIN class_sessions cs ON sa.session_id = cs.id
                JOIN student_groups sg ON cs.batch_id = sg.id
                WHERE sa.student_id = $student_id";
        
        if ($batch_id) {
            $batch_id = (int)$batch_id;
            $sql .= " AND cs.batch_id = $batch_id";
        }
        
        $sql .= " ORDER BY cs.session_date DESC";
        
        $result = $this->db->query($sql);
        $attendance = [];
        while ($row = $result->fetch_assoc()) {
            $attendance[] = $row;
        }
        return $attendance;
    }
    
    // Update batch progress after session changes
    private function updateBatchProgress($batch_id) {
        $batch_id = (int)$batch_id;
        
        // Count sessions
        $sessions_result = $this->db->query("SELECT 
            COUNT(*) as total_scheduled,
            COUNT(CASE WHEN status = 'completed' THEN 1 END) as total_completed
            FROM class_sessions WHERE batch_id = $batch_id");
        
        $sessions_data = $sessions_result->fetch_assoc();
        
        // Calculate average attendance rate
        $attendance_result = $this->db->query("SELECT 
            AVG(CASE WHEN attendance_status = 'present' THEN 100 ELSE 0 END) as avg_attendance
            FROM student_attendance sa
            JOIN class_sessions cs ON sa.session_id = cs.id
            WHERE cs.batch_id = $batch_id");
        
        $attendance_data = $attendance_result->fetch_assoc();
        
        // Update batch progress
        $sql = "UPDATE batch_progress SET 
                total_sessions_scheduled = {$sessions_data['total_scheduled']},
                total_sessions_completed = {$sessions_data['total_completed']},
                average_attendance_rate = " . ($attendance_data['avg_attendance'] ?: 0) . "
                WHERE batch_id = $batch_id";
        
        $this->db->query($sql);
    }
    
    // Update student batch progress
    private function updateStudentBatchProgress($session_id) {
        $session_id = (int)$session_id;
        
        // Get session details
        $session = $this->getById($session_id);
        if (!$session) return;
        
        $batch_id = $session['batch_id'];
        
        // Get all students in the batch
        $students_result = $this->db->query("SELECT user_id FROM group_members WHERE group_id = $batch_id");
        
        while ($student_row = $students_result->fetch_assoc()) {
            $student_id = $student_row['user_id'];
            
            // Calculate attendance rate for this student
            $attendance_result = $this->db->query("SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN attendance_status = 'present' THEN 1 END) as present_sessions
                FROM student_attendance sa
                JOIN class_sessions cs ON sa.session_id = cs.id
                WHERE cs.batch_id = $batch_id AND sa.student_id = $student_id");
            
            $attendance_data = $attendance_result->fetch_assoc();
            $attendance_rate = $attendance_data['total_sessions'] > 0 ? 
                ($attendance_data['present_sessions'] / $attendance_data['total_sessions']) * 100 : 0;
            
            // Update or insert student batch progress
            $sql = "INSERT INTO student_batch_progress (student_id, batch_id, attendance_rate, last_attendance_date)
                    VALUES ($student_id, $batch_id, $attendance_rate, CURDATE())
                    ON DUPLICATE KEY UPDATE 
                    attendance_rate = $attendance_rate,
                    last_attendance_date = CURDATE()";
            
            $this->db->query($sql);
        }
    }
    
    // Get sessions by instructor
    public function getSessionsByInstructor($instructor_id, $start_date = null, $end_date = null) {
        $instructor_id = (int)$instructor_id;
        
        $sql = "SELECT cs.*, sg.name as batch_name, st.topic_name, l.title as lesson_title
                FROM class_sessions cs
                LEFT JOIN student_groups sg ON cs.batch_id = sg.id
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN lessons l ON cs.lesson_id = l.id
                WHERE cs.instructor_id = $instructor_id";
        
        if ($start_date) {
            $start_date = $this->db->real_escape_string($start_date);
            $sql .= " AND cs.session_date >= '$start_date'";
        }
        
        if ($end_date) {
            $end_date = $this->db->real_escape_string($end_date);
            $sql .= " AND cs.session_date <= '$end_date'";
        }
        
        $sql .= " ORDER BY cs.session_date ASC, cs.session_time ASC";
        
        $result = $this->db->query($sql);
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return $sessions;
    }
    
    // Get today's sessions for instructor
    public function getTodaysSessions($instructor_id) {
        $instructor_id = (int)$instructor_id;
        
        $sql = "SELECT cs.*, sg.name as batch_name, st.topic_name, l.title as lesson_title
                FROM class_sessions cs
                LEFT JOIN student_groups sg ON cs.batch_id = sg.id
                LEFT JOIN syllabus_topics st ON cs.topic_id = st.id
                LEFT JOIN lessons l ON cs.lesson_id = l.id
                WHERE cs.instructor_id = $instructor_id 
                AND cs.session_date = CURDATE()
                ORDER BY cs.session_time ASC";
        
        $result = $this->db->query($sql);
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return $sessions;
    }
}
?>
