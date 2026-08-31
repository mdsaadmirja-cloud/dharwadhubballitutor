<?php
// lms/model/StudentGroup.php
require_once "../../DB Operations/dbconnection.php";

class StudentGroup {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Create a new student group
    public function create($data) {
        $name = $this->db->real_escape_string($data['name']);
        $description = $this->db->real_escape_string($data['description']);
        $created_by = (int)$data['created_by'];
        
        $sql = "INSERT INTO student_groups (name, description, created_by) 
                VALUES ('$name', '$description', $created_by)";
        
        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Get group by ID
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT sg.*, u.name as created_by_name 
                FROM student_groups sg 
                LEFT JOIN users u ON sg.created_by = u.id 
                WHERE sg.id = $id";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
    
    // Get all groups
    public function getAll($created_by = null) {
        $sql = "SELECT sg.*, u.name as created_by_name, 
                       COUNT(gm.user_id) as member_count
                FROM student_groups sg 
                LEFT JOIN users u ON sg.created_by = u.id 
                LEFT JOIN group_members gm ON sg.id = gm.group_id 
                WHERE 1=1";
        
        if ($created_by) {
            $created_by = (int)$created_by;
            $sql .= " AND sg.created_by = $created_by";
        }
        
        $sql .= " GROUP BY sg.id ORDER BY sg.created_at DESC";
        
        $result = $this->db->query($sql);
        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
        return $groups;
    }
    
    // Update group
    public function update($id, $data) {
        $id = (int)$id;
        $name = $this->db->real_escape_string($data['name']);
        $description = $this->db->real_escape_string($data['description']);
        
        $sql = "UPDATE student_groups SET 
                name = '$name', 
                description = '$description'
                WHERE id = $id";
        
        return $this->db->query($sql);
    }
    
    // Delete group
    public function delete($id) {
        $id = (int)$id;
        $sql = "DELETE FROM student_groups WHERE id = $id";
        return $this->db->query($sql);
    }
    
    // Add member to group
    public function addMember($group_id, $user_id) {
        $group_id = (int)$group_id;
        $user_id = (int)$user_id;
        
        $sql = "INSERT IGNORE INTO group_members (group_id, user_id) VALUES ($group_id, $user_id)";
        return $this->db->query($sql);
    }
    
    // Remove member from group
    public function removeMember($group_id, $user_id) {
        $group_id = (int)$group_id;
        $user_id = (int)$user_id;
        
        $sql = "DELETE FROM group_members WHERE group_id = $group_id AND user_id = $user_id";
        return $this->db->query($sql);
    }
    
    // Get group members
    public function getMembers($group_id) {
        $group_id = (int)$group_id;
        $sql = "SELECT u.*, gm.added_at 
                FROM group_members gm 
                JOIN users u ON gm.user_id = u.id 
                WHERE gm.group_id = $group_id 
                ORDER BY u.name ASC";
        
        $result = $this->db->query($sql);
        $members = [];
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
        return $members;
    }
    
    // Get groups for a user
    public function getGroupsForUser($user_id) {
        $user_id = (int)$user_id;
        $sql = "SELECT sg.*, gm.added_at 
                FROM group_members gm 
                JOIN student_groups sg ON gm.group_id = sg.id 
                WHERE gm.user_id = $user_id 
                ORDER BY sg.name ASC";
        
        $result = $this->db->query($sql);
        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
        return $groups;
    }
    
    // Bulk add members to group
    public function bulkAddMembers($group_id, $user_ids) {
        $group_id = (int)$group_id;
        $this->db->autocommit(false);
        
        try {
            foreach ($user_ids as $user_id) {
                $user_id = (int)$user_id;
                $sql = "INSERT IGNORE INTO group_members (group_id, user_id) VALUES ($group_id, $user_id)";
                $this->db->query($sql);
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
    
    // Get available students (not in group)
    public function getAvailableStudents($group_id) {
        $group_id = (int)$group_id;
        $sql = "SELECT u.* FROM users u 
                WHERE u.role = 'student' 
                AND u.id NOT IN (
                    SELECT gm.user_id FROM group_members gm WHERE gm.group_id = $group_id
                )
                ORDER BY u.name ASC";
        
        $result = $this->db->query($sql);
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    }
    
    // Assign exam to group
    public function assignExam($exam_id, $group_id, $assigned_by) {
        $exam_id = (int)$exam_id;
        $group_id = (int)$group_id;
        $assigned_by = (int)$assigned_by;
        
        $sql = "INSERT IGNORE INTO exam_assignments (exam_id, group_id, assigned_by) 
                VALUES ($exam_id, $group_id, $assigned_by)";
        
        return $this->db->query($sql);
    }
    
    // Get assigned exams for group
    public function getAssignedExams($group_id) {
        $group_id = (int)$group_id;
        $sql = "SELECT e.*, ea.assigned_at, u.name as assigned_by_name
                FROM exam_assignments ea 
                JOIN exams e ON ea.exam_id = e.id 
                LEFT JOIN users u ON ea.assigned_by = u.id 
                WHERE ea.group_id = $group_id 
                ORDER BY e.start_time ASC";
        
        $result = $this->db->query($sql);
        $exams = [];
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
        return $exams;
    }
    
    // Remove exam assignment from group
    public function removeExamAssignment($exam_id, $group_id) {
        $exam_id = (int)$exam_id;
        $group_id = (int)$group_id;
        
        $sql = "DELETE FROM exam_assignments WHERE exam_id = $exam_id AND group_id = $group_id";
        return $this->db->query($sql);
    }
    
    // Get groups assigned to exam
    public function getGroupsForExam($exam_id) {
        $exam_id = (int)$exam_id;
        $sql = "SELECT sg.*, ea.assigned_at, u.name as assigned_by_name
                FROM exam_assignments ea 
                JOIN student_groups sg ON ea.group_id = sg.id 
                LEFT JOIN users u ON ea.assigned_by = u.id 
                WHERE ea.exam_id = $exam_id 
                ORDER BY sg.name ASC";
        
        $result = $this->db->query($sql);
        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
        return $groups;
    }

    public function syncMembers($group_id, $member_ids) {
        $group_id = (int)$group_id;

        // Start transaction
        $this->db->begin_transaction();

        // First, remove all existing members from the group
        $this->db->query("DELETE FROM group_members WHERE group_id = $group_id");

        // Then, add the new list of members
        if (!empty($member_ids)) {
            $stmt = $this->db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
            foreach ($member_ids as $member_id) {
                $member_id = (int)$member_id;
                $stmt->bind_param("ii", $group_id, $member_id);
                if (!$stmt->execute()) {
                    // If any insert fails, rollback and return false
                    $this->db->rollback();
                    return false;
                }
            }
            $stmt->close();
        }

        // If all successful, commit the transaction
        $this->db->commit();
        return true;
    }
}
?>
