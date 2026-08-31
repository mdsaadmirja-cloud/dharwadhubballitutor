<?php
// Controller/TaskController.php
// Endpoints called by View/Task Management System/task-management.php
session_start();
require_once __DIR__ . '/../model/TaskModel.php';

header('Content-Type: application/json');
ob_start();

function respond($data) {
    ob_clean();
    echo json_encode($data);
    exit;
}

$adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null; // adjust to your actual admin session key

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    switch ($action) {

        case 'list':
            $tasks = TaskModel::listTasks(
                trim($_GET['search'] ?? ''),
                trim($_GET['status'] ?? ''),
                trim($_GET['priority'] ?? '')
            );
            respond(['success' => true, 'data' => $tasks]);
            break;

        case 'lookups':
            respond(array_merge(['success' => true], TaskModel::getLookups()));
            break;

        case 'add':
        case 'edit':
            $data = [
                'title'                    => trim($_POST['title'] ?? ''),
                'description'              => trim($_POST['description'] ?? ''),
                'priority'                 => $_POST['priority'] ?? 'Medium',
                'category_id'              => $_POST['category_id'] ?? '',
                'start_date'               => $_POST['start_date'] ?: null,
                'end_date'                 => $_POST['end_date'] ?: null,
                'reminder_before_minutes'  => (int)($_POST['reminder_before_minutes'] ?? 30),
                'send_reminder_email'      => isset($_POST['send_reminder_email']) ? 1 : 0,
            ];
            $employeeIds = $_POST['employee_ids'] ?? [];

            if ($data['title'] === '' || $data['description'] === '' || empty($employeeIds)) {
                respond(['success' => false, 'message' => 'Title, description and at least one employee are required']);
            }

            if ($action === 'add') {
                $id = TaskModel::createTask($data, $employeeIds, $adminId);
            } else {
                $id = (int)($_POST['id'] ?? 0);
                if (!$id) respond(['success' => false, 'message' => 'Missing task id']);
                TaskModel::updateTask($id, $data, $employeeIds);
            }
            respond(['success' => true, 'id' => $id]);
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) respond(['success' => false, 'message' => 'Missing id']);
            TaskModel::deleteTask($id);
            respond(['success' => true]);
            break;

        case 'view_review':
            $id = (int)($_GET['id'] ?? 0);
            $assignmentId = isset($_GET['assignment_id']) ? (int)$_GET['assignment_id'] : null;
            if (!$id) respond(['success' => false, 'message' => 'Missing id']);

            $data = TaskModel::getReviewData($id, $assignmentId);
            if (!$data) respond(['success' => false, 'message' => 'Task or assignment not found']);
            respond(array_merge(['success' => true], $data));
            break;

        case 'approve_task':
            $taskId = (int)($_POST['task_id'] ?? 0);
            $assignmentId = isset($_POST['assignment_id']) ? (int)$_POST['assignment_id'] : null;
            if (!$taskId) respond(['success' => false, 'message' => 'Missing task_id']);

            $ok = TaskModel::approveTask($taskId, $assignmentId, $adminId);
            respond(['success' => $ok, 'message' => $ok ? null : 'Nothing to approve']);
            break;

        case 'send_review':
            $taskId = (int)($_POST['task_id'] ?? 0);
            $assignmentId = isset($_POST['assignment_id']) ? (int)$_POST['assignment_id'] : null;
            $review = trim($_POST['review'] ?? '');
            if (!$taskId || $review === '') respond(['success' => false, 'message' => 'Missing task_id or review text']);

            $ok = TaskModel::sendReview($taskId, $assignmentId, $review, $adminId);
            respond(['success' => $ok, 'message' => $ok ? null : 'Nothing to send review for']);
            break;

        default:
            respond(['success' => false, 'message' => 'Unknown action']);
    }
} catch (\Throwable $e) {
    error_log('TaskController error: ' . $e->getMessage());
    respond([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
