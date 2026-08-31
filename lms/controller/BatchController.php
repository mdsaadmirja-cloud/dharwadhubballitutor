<?php
// lms/controller/BatchController.php
session_start();
require_once '../model/Batch.php';
require_once '../model/ClassSession.php';
require_once '../model/SyllabusCoverage.php';
require_once '../model/StudentGroup.php';
require_once '../model/User.php';

class BatchController {
    private $batchModel;
    private $sessionModel;
    private $syllabusModel;
    private $groupModel;
    private $userModel;
    
    public function __construct() {
        $this->batchModel = new Batch();
        $this->sessionModel = new ClassSession();
        $this->syllabusModel = new SyllabusCoverage();
        $this->groupModel = new StudentGroup();
        $this->userModel = new User();
    }
    
    // Create new batch
    public function createBatch() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'batch_start_date' => $_POST['batch_start_date'],
            'batch_end_date' => $_POST['batch_end_date'],
            'total_duration_days' => $_POST['total_duration_days'],
            'class_schedule' => $_POST['class_schedule'],
            'syllabus_id' => $_POST['syllabus_id'] ?? null,
            'instructor_id' => $_POST['instructor_id'] ?? null,
            'max_students' => $_POST['max_students'] ?? 30,
            'fees' => $_POST['fees'] ?? 0.00,
            'created_by' => $_SESSION['user_id']
        ];
        
        $batch_id = $this->batchModel->create($data);
        
        if ($batch_id) {
            // Add selected students to the batch
            if (isset($_POST['student_ids']) && is_array($_POST['student_ids'])) {
                $this->groupModel->bulkAddMembers($batch_id, $_POST['student_ids']);
            }
            
            return ['success' => true, 'message' => 'Batch created successfully', 'batch_id' => $batch_id];
        } else {
            return ['success' => false, 'message' => 'Failed to create batch'];
        }
    }
    
    // Update batch
    public function updateBatch() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            error_log("Unauthorized access attempt to update batch");
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $batch_id = (int)$_POST['batch_id'];
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'batch_start_date' => $_POST['batch_start_date'],
            'batch_end_date' => $_POST['batch_end_date'],
            'total_duration_days' => $_POST['total_duration_days'],
            'class_schedule' => $_POST['class_schedule'],
            'syllabus_id' => $_POST['syllabus_id'] ?? null,
            'instructor_id' => $_POST['instructor_id'] ?? null,
            'max_students' => $_POST['max_students'] ?? 30,
            'fees' => $_POST['fees'] ?? 0.00,
            'batch_status' => $_POST['batch_status']
        ];
        
        if ($this->batchModel->update($batch_id, $data)) {
            return ['success' => true, 'message' => 'Batch updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update batch'];
        }
    }
    
    // Delete batch
    public function deleteBatch() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $batch_id = (int)$_POST['batch_id'];
        
        if ($this->batchModel->delete($batch_id)) {
            return ['success' => true, 'message' => 'Batch deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete batch'];
        }
    }
    
    // Get batch details
    public function getBatch() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $batch_id = (int)$_GET['batch_id'];
        $batch = $this->batchModel->getById($batch_id);
        
        if ($batch) {
            // Get additional data
            $batch['statistics'] = $this->batchModel->getBatchStatistics($batch_id);
            $batch['upcoming_sessions'] = $this->batchModel->getUpcomingSessions($batch_id);
            $batch['recent_sessions'] = $this->batchModel->getRecentSessions($batch_id);
            $batch['members'] = $this->groupModel->getMembers($batch_id);
            
            return ['success' => true, 'batch' => $batch];
        } else {
            return ['success' => false, 'message' => 'Batch not found'];
        }
    }
    
    // Get all batches
    public function getAllBatches() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        // Show all batches to any logged-in user
        $batches = $this->batchModel->getAll(null);
        
        return ['success' => true, 'batches' => $batches];
    }
    
    // Create class session
    public function createSession() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $data = [
            'batch_id' => $_POST['batch_id'],
            'session_date' => $_POST['session_date'],
            'session_time' => $_POST['session_time'] ?? null,
            'duration_minutes' => $_POST['duration_minutes'] ?? 60,
            'topic_id' => $_POST['topic_id'] ?? null,
            'lesson_id' => $_POST['lesson_id'] ?? null,
            'instructor_id' => $_POST['instructor_id'],
            'session_type' => $_POST['session_type'],
            'session_title' => $_POST['session_title'],
            'session_description' => $_POST['session_description'] ?? null,
            'homework_assigned' => $_POST['homework_assigned'] ?? null,
            'notes' => $_POST['notes'] ?? null
        ];
        
        $session_id = $this->sessionModel->create($data);
        
        if ($session_id) {
            return ['success' => true, 'message' => 'Class session created successfully', 'session_id' => $session_id];
        } else {
            return ['success' => false, 'message' => 'Failed to create class session'];
        }
    }
    
    // Update class session
    public function updateSession() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $session_id = (int)$_POST['session_id'];
        $data = [
            'session_date' => $_POST['session_date'],
            'session_time' => $_POST['session_time'] ?? null,
            'duration_minutes' => $_POST['duration_minutes'] ?? 60,
            'topic_id' => $_POST['topic_id'] ?? null,
            'lesson_id' => $_POST['lesson_id'] ?? null,
            'instructor_id' => $_POST['instructor_id'],
            'session_type' => $_POST['session_type'],
            'session_title' => $_POST['session_title'],
            'session_description' => $_POST['session_description'] ?? null,
            'homework_assigned' => $_POST['homework_assigned'] ?? null,
            'notes' => $_POST['notes'] ?? null,
            'status' => $_POST['status'] ?? 'scheduled'
        ];
        
        if ($this->sessionModel->update($session_id, $data)) {
            return ['success' => true, 'message' => 'Class session updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update class session'];
        }
    }
    
    // Mark session as completed
    public function completeSession() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $session_id = (int)$_POST['session_id'];
        $notes = $_POST['notes'] ?? null;
        
        if ($this->sessionModel->markCompleted($session_id, $notes)) {
            return ['success' => true, 'message' => 'Session marked as completed'];
        } else {
            return ['success' => false, 'message' => 'Failed to complete session'];
        }
    }
    
    // Take attendance
    public function takeAttendance() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $session_id = (int)$_POST['session_id'];
        $attendance_data = $_POST['attendance_data'];
        
        // Add marked_by to each attendance record
        foreach ($attendance_data as $student_id => &$attendance_info) {
            $attendance_info['marked_by'] = $_SESSION['user_id'];
        }
        
        if ($this->sessionModel->takeAttendance($session_id, $attendance_data)) {
            return ['success' => true, 'message' => 'Attendance recorded successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to record attendance'];
        }
    }
    
    // Get session attendance
    public function getSessionAttendance() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $session_id = (int)$_GET['session_id'];
        $attendance = $this->sessionModel->getSessionAttendance($session_id);
        
        return ['success' => true, 'attendance' => $attendance];
    }
    
    // Mark syllabus topic as covered
    public function markTopicCovered() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $batch_id = (int)$_POST['batch_id'];
        $topic_id = (int)$_POST['topic_id'];
        $session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : null;
        
        $coverage_data = [
            'coverage_percentage' => $_POST['coverage_percentage'] ?? 100.00,
            'instructor_notes' => $_POST['instructor_notes'] ?? null,
            'student_feedback' => $_POST['student_feedback'] ?? null,
            'homework_completion_rate' => $_POST['homework_completion_rate'] ?? 0.00,
            'quiz_average_score' => $_POST['quiz_average_score'] ?? 0.00,
            'status' => $_POST['status'] ?? 'completed'
        ];
        
        if ($this->syllabusModel->markTopicCovered($batch_id, $topic_id, $session_id, $coverage_data)) {
            return ['success' => true, 'message' => 'Topic marked as covered'];
        } else {
            return ['success' => false, 'message' => 'Failed to mark topic as covered'];
        }
    }
    
    // Get batch syllabus progress
    public function getBatchSyllabusProgress() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $batch_id = (int)$_GET['batch_id'];
        $progress = $this->syllabusModel->getBatchSyllabusProgress($batch_id);
        
        if ($progress) {
            $coverage = $this->syllabusModel->getBatchSyllabusCoverage($batch_id);
            $upcoming = $this->syllabusModel->getUpcomingTopics($batch_id);
            $completed = $this->syllabusModel->getCompletedTopics($batch_id);
            
            return [
                'success' => true, 
                'progress' => $progress,
                'coverage' => $coverage,
                'upcoming_topics' => $upcoming,
                'completed_topics' => $completed
            ];
        } else {
            return ['success' => false, 'message' => 'No syllabus assigned to this batch'];
        }
    }
    
    // Get student progress
    public function getStudentProgress() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $student_id = (int)$_GET['student_id'];
        $batch_id = (int)$_GET['batch_id'];
        
        // Get student's groups to verify access
        $student_groups = $this->groupModel->getGroupsForUser($student_id);
        $has_access = false;
        
        foreach ($student_groups as $group) {
            if ($group['id'] == $batch_id) {
                $has_access = true;
                break;
            }
        }
        
        if (!$has_access && $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Access denied'];
        }
        
        $progress = $this->syllabusModel->getStudentSyllabusProgress($student_id, $batch_id);
        $attendance = $this->sessionModel->getStudentAttendanceSummary($student_id, $batch_id);
        
        return [
            'success' => true,
            'syllabus_progress' => $progress,
            'attendance_history' => $attendance
        ];
    }
    
    // Create syllabus
    public function createSyllabus() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? null,
            'course_id' => $_POST['course_id'] ?? null,
            'total_topics' => $_POST['total_topics'] ?? 0,
            'total_duration_hours' => $_POST['total_duration_hours'] ?? 0,
            'created_by' => $_SESSION['user_id']
        ];
        
        $syllabus_id = $this->syllabusModel->createSyllabus($data);
        
        if ($syllabus_id) {
            return ['success' => true, 'message' => 'Syllabus created successfully', 'syllabus_id' => $syllabus_id];
        } else {
            return ['success' => false, 'message' => 'Failed to create syllabus'];
        }
    }
    
    // Add topic to syllabus
    public function addTopic() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $data = [
            'syllabus_id' => $_POST['syllabus_id'],
            'topic_name' => $_POST['topic_name'],
            'topic_description' => $_POST['topic_description'] ?? null,
            'estimated_duration_hours' => $_POST['estimated_duration_hours'] ?? 1,
            'order_index' => $_POST['order_index'] ?? 0,
            'is_optional' => $_POST['is_optional'] ?? 0,
            'prerequisites' => $_POST['prerequisites'] ?? null
        ];
        
        $topic_id = $this->syllabusModel->addTopic($data);
        
        if ($topic_id) {
            return ['success' => true, 'message' => 'Topic added successfully', 'topic_id' => $topic_id];
        } else {
            return ['success' => false, 'message' => 'Failed to add topic'];
        }
    }
    
    // Get all syllabi
    public function getAllSyllabi() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $created_by = ($_SESSION['role'] === 'admin') ? $_SESSION['user_id'] : null;
        $syllabi = $this->syllabusModel->getAllSyllabi($created_by);
        
        return ['success' => true, 'syllabi' => $syllabi];
    }
    
    // Get syllabus topics
    public function getSyllabusTopics() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $syllabus_id = (int)$_GET['syllabus_id'];
        $topics = $this->syllabusModel->getSyllabusTopics($syllabus_id);
        
        return ['success' => true, 'topics' => $topics];
    }
    
    // Get batch calendar events
    public function getBatchCalendar() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $batch_id = (int)$_GET['batch_id'];
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        
        $events = $this->batchModel->getBatchCalendarEvents($batch_id, $start_date, $end_date);
        
        return ['success' => true, 'events' => $events];
    }
    
    // Get batches by status
    public function getBatchesByStatus() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $status = $_GET['status'];
        // Show all batches of this status to any logged-in user
        $batches = $this->batchModel->getBatchesByStatus($status, null);
        
        return ['success' => true, 'batches' => $batches];
    }

    public function getStudentManagementData() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $batch_id = (int)$_GET['batch_id'];
        
        // Get all students
        $allStudents = User::getAllStudents();
        
        // Get students currently in the batch
        $batchMembers = $this->groupModel->getMembers($batch_id);
        $memberIds = array_column($batchMembers, 'user_id');

        return [
            'success' => true,
            'allStudents' => $allStudents,
            'memberIds' => $memberIds,
            'members' => $batchMembers
        ];
    }

    public function updateBatchMembers() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $batch_id = (int)$_POST['batch_id'];
        $student_ids = isset($_POST['student_ids']) ? (array)$_POST['student_ids'] : [];

        if ($this->groupModel->syncMembers($batch_id, $student_ids)) {
            return ['success' => true, 'message' => 'Student list updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update student list'];
        }
    }

    // Append-only: add students to batch without removing existing members
    public function appendBatchMembers() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $batch_id = (int)($_POST['batch_id'] ?? 0);
        $student_ids = isset($_POST['student_ids']) ? (array)$_POST['student_ids'] : [];

        if ($batch_id <= 0) {
            return ['success' => false, 'message' => 'Invalid batch'];
        }

        if (empty($student_ids)) {
            return ['success' => true, 'message' => 'No new students to add'];
        }

        if ($this->groupModel->bulkAddMembers($batch_id, $student_ids)) {
            return ['success' => true, 'message' => 'Students appended successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to append students'];
        }
    }

    // Remove one or more students from a batch
    public function removeBatchMembers() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }

        $batch_id = (int)($_POST['batch_id'] ?? 0);
        $student_ids = isset($_POST['student_ids']) ? (array)$_POST['student_ids'] : [];

        if ($batch_id <= 0) {
            return ['success' => false, 'message' => 'Invalid batch'];
        }

        if (empty($student_ids)) {
            return ['success' => true, 'message' => 'No students selected for removal'];
        }

        $ok = true;
        foreach ($student_ids as $sid) {
            $ok = $ok && $this->groupModel->removeMember($batch_id, (int)$sid);
        }

        if ($ok) {
            return ['success' => true, 'message' => 'Selected students removed'];
        }
        return ['success' => false, 'message' => 'Failed to remove one or more students'];
    }

    public function getSessionsForBatch() {
        $batch_id = isset($_GET['batch_id']) ? (int)$_GET['batch_id'] : 0;
        $sessions = $this->sessionModel->getSessionsByBatch($batch_id);
        return [
            'success' => true,
            'sessions' => $sessions
        ];
    }
}

// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');
    
    $controller = new BatchController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create_batch':
            echo json_encode($controller->createBatch());
            break;
        case 'update_batch':
            error_log("Unauthorized access attempt to update batch");
            echo json_encode($controller->updateBatch());
            break;
        case 'delete_batch':
            echo json_encode($controller->deleteBatch());
            break;
        case 'get_batch':
            echo json_encode($controller->getBatch());
            break;
        case 'get_all_batches':
            echo json_encode($controller->getAllBatches());
            break;
        case 'create_session':
            echo json_encode($controller->createSession());
            break;
        case 'update_session':
            echo json_encode($controller->updateSession());
            break;
        case 'complete_session':
            echo json_encode($controller->completeSession());
            break;
        case 'take_attendance':
            echo json_encode($controller->takeAttendance());
            break;
        case 'get_session_attendance':
            echo json_encode($controller->getSessionAttendance());
            break;
        case 'mark_topic_covered':
            echo json_encode($controller->markTopicCovered());
            break;
        case 'get_batch_syllabus_progress':
            echo json_encode($controller->getBatchSyllabusProgress());
            break;
        case 'get_student_progress':
            echo json_encode($controller->getStudentProgress());
            break;
        case 'create_syllabus':
            echo json_encode($controller->createSyllabus());
            break;
        case 'add_topic':
            echo json_encode($controller->addTopic());
            break;
        case 'get_all_syllabi':
            echo json_encode($controller->getAllSyllabi());
            break;
        case 'get_syllabus_topics':
            echo json_encode($controller->getSyllabusTopics());
            break;
        case 'get_batch_calendar':
            echo json_encode($controller->getBatchCalendar());
            break;
        case 'get_batches_by_status':
            echo json_encode($controller->getBatchesByStatus());
            break;
        case 'get_student_management_data':
            echo json_encode($controller->getStudentManagementData());
            break;
        case 'update_batch_members':
            echo json_encode($controller->updateBatchMembers());
            break;
        case 'append_batch_members':
            echo json_encode($controller->appendBatchMembers());
            break;
        case 'remove_batch_members':
            echo json_encode($controller->removeBatchMembers());
            break;
        case 'get_sessions_for_batch':
            echo json_encode($controller->getSessionsForBatch());
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}
?>
