<?php
// Controller/mytaskcontroller.php
// Endpoints called by View/Task Management System/my-tasks.php
session_start();
require_once __DIR__ . '/../model/TaskModel.php';

header('Content-Type: application/json');
ob_start();

function respond($data) {
    ob_clean();
    echo json_encode($data);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
$employeeId = null;


// Find the trainer using the logged-in user's user_id.
if ($userId) {
    $conn = db();

    $stmt = $conn->prepare("
        SELECT id
        FROM trainers
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $trainer = $result->fetch_assoc();

    if ($trainer) {
        $employeeId = (int)$trainer['id'];
    }

    $conn = db();

    $stmt = $conn->prepare("
        SELECT id
        FROM trainers
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $trainer = $result->fetch_assoc();

    if ($trainer) {
        $employeeId = (int)$trainer['id'];
    }
}

try {
    if (!$employeeId) {
        respond(['success' => false, 'message' => 'Not logged in']);
    }

    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    switch ($action) {

        case 'my_list':
            respond(['success' => true, 'data' => TaskModel::getMyTasks($employeeId)]);
            break;

        case 'my_get':
            $assignmentId = (int)($_GET['assignment_id'] ?? 0);
            if (!$assignmentId) respond(['success' => false, 'message' => 'Missing assignment_id']);

            $data = TaskModel::getMyTaskDetail($assignmentId, $employeeId);
            if (!$data) respond(['success' => false, 'message' => 'Task not found']);
            respond(['success' => true, 'data' => $data]);
            break;

        case 'update_progress':
            $assignmentId = (int)($_POST['assignment_id'] ?? 0);
            if (!$assignmentId) respond(['success' => false, 'message' => 'Missing assignment_id']);

            $progress = (int)($_POST['progress_percent'] ?? 0);
            $summary  = trim($_POST['work_summary'] ?? '');
            if ($summary === '') respond(['success' => false, 'message' => "Today's work summary is required"]);

            $data = [
                'progress_percent' => $progress,
                'work_summary'     => $summary,
                'issues'           => trim($_POST['issues'] ?? ''),
                'hours_worked'     => $_POST['hours_worked'] !== '' ? (float)$_POST['hours_worked'] : null,
            ];

            $ok = TaskModel::updateProgress($assignmentId, $employeeId, $data, $_FILES['attachment'] ?? null);
            respond(['success' => $ok, 'message' => $ok ? null : 'Assignment not found']);
            break;

        default:
            respond(['success' => false, 'message' => 'Unknown action']);
    }
} catch (\Throwable $e) {
    error_log('mytaskcontroller error: ' . $e->getMessage());
    respond(['success' => false, 'message' => 'Server error']);
}
