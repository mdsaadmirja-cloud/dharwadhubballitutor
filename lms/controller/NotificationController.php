<?php
// lms/controller/NotificationController.php
session_start();
require_once "../../DB Operations/dbconnection.php";

class NotificationController {
    private $db;
    
    public function __construct() {
        $dbInstance = ConnectDb::getInstance();
        $this->db = $dbInstance->getConnection();
    }
    
    // Get notifications for user
    public function getNotifications() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        $limit = (int)($_GET['limit'] ?? 10);
        $offset = (int)($_GET['offset'] ?? 0);
        
        $sql = "SELECT * FROM notifications 
                WHERE user_id = $user_id 
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $result = $this->db->query($sql);
        $notifications = [];
        
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        
        return ['success' => true, 'notifications' => $notifications];
    }
    
    // Mark notification as read
    public function markAsRead() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        $notification_id = (int)$_POST['notification_id'];
        
        $sql = "UPDATE notifications 
                SET is_read = 1 
                WHERE id = $notification_id AND user_id = $user_id";
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => 'Notification marked as read'];
        } else {
            return ['success' => false, 'message' => 'Failed to mark notification as read'];
        }
    }
    
    // Mark all notifications as read
    public function markAllAsRead() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        
        $sql = "UPDATE notifications 
                SET is_read = 1 
                WHERE user_id = $user_id AND is_read = 0";
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => 'All notifications marked as read'];
        } else {
            return ['success' => false, 'message' => 'Failed to mark notifications as read'];
        }
    }
    
    // Delete notification
    public function deleteNotification() {
        if (!isset($_SESSION['user'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user'];
        $notification_id = (int)$_POST['notification_id'];
        
        $sql = "DELETE FROM notifications 
                WHERE id = $notification_id AND user_id = $user_id";
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => 'Notification deleted'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete notification'];
        }
    }
    
    // Get unread notification count
    public function getUnreadCount() {
        if (!isset($_SESSION['user_id'])) {
            return ['success' => false, 'message' => 'Unauthorized access'];
        }
        
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT COUNT(*) as count 
                FROM notifications 
                WHERE user_id = $user_id AND is_read = 0";
        
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        
        return ['success' => true, 'count' => $row['count']];
    }
    
    // Create notification
    public function createNotification($user_id, $title, $message, $type = 'system') {
        $user_id = (int)$user_id;
        $title = $this->db->real_escape_string($title);
        $message = $this->db->real_escape_string($message);
        $type = $this->db->real_escape_string($type);
        
        $sql = "INSERT INTO notifications (user_id, title, message, type) 
                VALUES ($user_id, '$title', '$message', '$type')";
        
        if ($this->db->query($sql)) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Send exam reminder notifications
    public function sendExamReminders() {
        // Get exams starting in the next hour
        $sql = "SELECT e.*, ea.group_id, gm.user_id
                FROM exams e
                JOIN exam_assignments ea ON e.id = ea.exam_id
                JOIN group_members gm ON ea.group_id = gm.group_id
                WHERE e.status = 'published'
                AND e.start_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 HOUR)
                AND e.start_time > NOW()";
        
        $result = $this->db->query($sql);
        $sent_count = 0;
        
        while ($row = $result->fetch_assoc()) {
            $title = 'Exam Reminder';
            $message = "Reminder: Exam '{$row['title']}' starts in less than 1 hour at " . 
                      date('M d, Y H:i', strtotime($row['start_time']));
            
            if ($this->createNotification($row['user_id'], $title, $message, 'exam_reminder')) {
                $sent_count++;
            }
        }
        
        return ['success' => true, 'sent_count' => $sent_count];
    }
    
    // Clean up old notifications
    public function cleanupOldNotifications() {
        $sql = "DELETE FROM notifications 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        $result = $this->db->query($sql);
        return $this->db->affected_rows;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new NotificationController();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'get':
            $result = $controller->getNotifications();
            break;
        case 'mark_read':
            $result = $controller->markAsRead();
            break;
        case 'mark_all_read':
            $result = $controller->markAllAsRead();
            break;
        case 'delete':
            $result = $controller->deleteNotification();
            break;
        case 'get_unread_count':
            $result = $controller->getUnreadCount();
            break;
        case 'send_reminders':
            $result = $controller->sendExamReminders();
            break;
        case 'cleanup':
            $result = $controller->cleanupOldNotifications();
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
