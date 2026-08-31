<?php
// lms/controller/ProfileController.php
session_start();
require_once '../model/User.php';

class ProfileController {
    public static function getProfile() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        $user = $_SESSION['user'];
        $userId = is_array($user) ? ($user['id'] ?? null) : $user;
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user session']);
            return;
        }
        $profile = User::findById($userId);
        echo json_encode(['success' => true, 'profile' => $profile]);
    }

    public static function updateProfile() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        $user = $_SESSION['user'];
        $userId = is_array($user) ? ($user['id'] ?? null) : $user;
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user session']);
            return;
        }

        $name = $_POST['name'] ?? null;
        $college = $_POST['college'] ?? null;
        $ok = User::updateProfile($userId, ['name' => $name, 'college' => $college]);
        if ($ok) {
            $updated = User::findById($userId);
            // keep session user in sync if stored as array
            if (is_array($_SESSION['user'])) {
                $_SESSION['user'] = $updated;
            }
            echo json_encode(['success' => true, 'message' => 'Profile updated', 'profile' => $updated]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes or update failed']);
        }
    }
}

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    switch ($_GET['action']) {
        case 'get':
            ProfileController::getProfile();
            break;
        case 'update':
            ProfileController::updateProfile();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
}


