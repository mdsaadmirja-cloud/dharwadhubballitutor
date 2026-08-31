<?php
// lms/controller/StudentGroupController.php
session_start();
require_once '../model/StudentGroup.php';
require_once '../model/User.php';

class StudentGroupController {
    private $groupModel;
    private $userModel;
    
    public function __construct() {
        $this->groupModel = new StudentGroup();
        $this->userModel = new User();
    }
    
    // Create new group
    public function createGroup() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'created_by' => $_SESSION['user_id']
        ];
        
        $group_id = $this->groupModel->create($data);
        
        if ($group_id) {
            // Add selected students to the group
            if (isset($_POST['student_ids']) && is_array($_POST['student_ids'])) {
                $this->groupModel->bulkAddMembers($group_id, $_POST['student_ids']);
            }
            
            return ['success' => true, 'message' => 'Group created successfully', 'group_id' => $group_id];
        } else {
            return ['success' => false, 'message' => 'Failed to create group'];
        }
    }
    
    // Update group
    public function updateGroup() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_POST['group_id'];
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description']
        ];
        
        if ($this->groupModel->update($group_id, $data)) {
            return ['success' => true, 'message' => 'Group updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update group'];
        }
    }
    
    // Delete group
    public function deleteGroup() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_POST['group_id'];
        
        if ($this->groupModel->delete($group_id)) {
            return ['success' => true, 'message' => 'Group deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete group'];
        }
    }
    
    // Get group details
    public function getGroup() {
        $group_id = (int)$_GET['group_id'];
        $group = $this->groupModel->getById($group_id);
        
        if ($group) {
            return ['success' => true, 'group' => $group];
        } else {
            return ['success' => false, 'message' => 'Group not found'];
        }
    }
    
    // Get all groups
    public function getAllGroups() {
        $created_by = isset($_SESSION['user']) && $_SESSION['role'] === 'admin' ? 
                      $_SESSION['user_id'] : null;
        
        $groups = $this->groupModel->getAll($created_by);
        return ['success' => true, 'groups' => $groups];
    }
    
    // Get group members
    public function getGroupMembers() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_GET['group_id'];
        $members = $this->groupModel->getMembers($group_id);
        
        return ['success' => true, 'members' => $members];
    }
    
    // Get available students for group
    public function getAvailableStudents() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_GET['group_id'];
        $students = $this->groupModel->getAvailableStudents($group_id);
        
        return ['success' => true, 'students' => $students];
    }
    
    // Add member to group
    public function addMember() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_POST['group_id'];
        $user_id = (int)$_POST['user_id'];
        
        if ($this->groupModel->addMember($group_id, $user_id)) {
            return ['success' => true, 'message' => 'Member added successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to add member'];
        }
    }
    
    // Remove member from group
    public function removeMember() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_POST['group_id'];
        $user_id = (int)$_POST['user_id'];
        
        if ($this->groupModel->removeMember($group_id, $user_id)) {
            return ['success' => true, 'message' => 'Member removed successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to remove member'];
        }
    }
    
    // Bulk add members to group
    public function bulkAddMembers() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_POST['group_id'];
        $user_ids = $_POST['user_ids'];
        
        if ($this->groupModel->bulkAddMembers($group_id, $user_ids)) {
            return ['success' => true, 'message' => 'Members added successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to add members'];
        }
    }
    
    // Get groups for user
    public function getGroupsForUser() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        $groups = $this->groupModel->getGroupsForUser($user_id);
        
        return ['success' => true, 'groups' => $groups];
    }
    
    // Assign exam to group
    public function assignExamToGroup() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $exam_id = (int)$_POST['exam_id'];
        $group_id = (int)$_POST['group_id'];
        $assigned_by = $_SESSION['user'];
        
        if ($this->groupModel->assignExam($exam_id, $group_id, $assigned_by)) {
            return ['success' => true, 'message' => 'Exam assigned to group successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to assign exam to group'];
        }
    }
    
    // Get assigned exams for group
    public function getAssignedExamsForGroup() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $group_id = (int)$_GET['group_id'];
        $exams = $this->groupModel->getAssignedExams($group_id);
        
        return ['success' => true, 'exams' => $exams];
    }
    
    // Remove exam assignment from group
    public function removeExamAssignment() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $exam_id = (int)$_POST['exam_id'];
        $group_id = (int)$_POST['group_id'];
        
        if ($this->groupModel->removeExamAssignment($exam_id, $group_id)) {
            return ['success' => true, 'message' => 'Exam assignment removed successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to remove exam assignment'];
        }
    }
    
    // Get groups assigned to exam
    public function getGroupsForExam() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $exam_id = (int)$_GET['exam_id'];
        $groups = $this->groupModel->getGroupsForExam($exam_id);
        
        return ['success' => true, 'groups' => $groups];
    }
}

// Handle AJAX requests

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $controller = new StudentGroupController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    error_log($action);
    switch ($action) {
        case 'create':
            $result = $controller->createGroup();
            break;
        case 'update':
            $result = $controller->updateGroup();
            break;
        case 'delete':
            $result = $controller->deleteGroup();
            break;
        case 'get':
            $result = $controller->getGroup();
            break;
        case 'get_all':
            $result = $controller->getAllGroups();
            break;
        case 'get_members':
            $result = $controller->getGroupMembers();
            break;
        case 'get_available_students':
            $result = $controller->getAvailableStudents();
            break;
        case 'add_member':
            $result = $controller->addMember();
            break;
        case 'remove_member':
            $result = $controller->removeMember();
            break;
        case 'bulk_add_members':
            $result = $controller->bulkAddMembers();
            break;
        case 'get_groups_for_user':
            $result = $controller->getGroupsForUser();
            break;
        case 'assign_exam':
            $result = $controller->assignExamToGroup();
            break;
        case 'get_assigned_exams':
            $result = $controller->getAssignedExamsForGroup();
            break;
        case 'remove_exam_assignment':
            $result = $controller->removeExamAssignment();
            break;
        case 'get_groups_for_exam':
            $result = $controller->getGroupsForExam();
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
