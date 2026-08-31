<?php

// ================================================================
// Controller/employeeworkuploadcontroller.php
//
// ADMIN:
//   Task Management -> Employee Work Uploads
//
// EMPLOYEE:
//   Work Upload
//   Submission History
//   Needs Revision -> Update & Resubmit
// ================================================================

session_start();

require_once __DIR__ . '/../model/EmployeeWorkUploadModel.php';

header('Content-Type: application/json');

ob_start();


// ================================================================
// JSON RESPONSE
// ================================================================

function respond($data)
{
    ob_clean();

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
    );

    exit;
}


// ================================================================
// SESSION
// ================================================================

$adminId =
    $_SESSION['admin_id']
    ?? null;


// IMPORTANT:
// Keep your existing employee session key.
// Your current working controller uses user_id.
$employeeId =
    $_SESSION['user_id']
    ?? null;


// ================================================================
// PROCESS ACTION
// ================================================================

try {

    $action =
        $_GET['action']
        ?? $_POST['action']
        ?? '';


    switch ($action) {


        // ============================================================
        // ADMIN - LIST
        // ============================================================

        case 'admin_list':

            $data =
                EmployeeWorkUploadModel::adminList(
                    trim($_GET['search'] ?? ''),
                    trim($_GET['review_status'] ?? '')
                );

            respond([
                'success' => true,
                'data' => $data
            ]);

            break;


        // ============================================================
        // ADMIN - PENDING COUNT
        // ============================================================

        case 'admin_pending_count':

            $count =
                EmployeeWorkUploadModel::adminPendingCount();

            respond([
                'success' => true,
                'count' => $count
            ]);

            break;


        // ============================================================
        // ADMIN - GET ONE UPLOAD
        // ============================================================

        case 'admin_get':

            $id =
                (int)($_GET['id'] ?? 0);


            if (!$id) {

                respond([
                    'success' => false,
                    'message' => 'Missing upload id'
                ]);
            }


            $data =
                EmployeeWorkUploadModel::adminGet(
                    $id
                );


            if (!$data) {

                respond([
                    'success' => false,
                    'message' => 'Upload not found'
                ]);
            }


            respond([
                'success' => true,
                'data' => $data
            ]);

            break;


        // ============================================================
        // ADMIN - APPROVE
        // ============================================================

        case 'approve_upload':

            $id =
                (int)($_POST['id'] ?? 0);


            if (!$id) {

                respond([
                    'success' => false,
                    'message' => 'Missing upload id'
                ]);
            }


            $ok =
                EmployeeWorkUploadModel::approveUpload(
                    $id,
                    $adminId
                );


            respond([
                'success' => $ok,
                'message' =>
                    $ok
                    ? 'Work approved successfully'
                    : 'Upload not found'
            ]);

            break;


        // ============================================================
        // ADMIN - DELETE
        // ============================================================

        case 'delete_upload':

            $id =
                (int)($_POST['id'] ?? 0);


            if (!$id) {

                respond([
                    'success' => false,
                    'message' => 'Missing upload id'
                ]);
            }


            EmployeeWorkUploadModel::deleteUpload(
                $id
            );


            respond([
                'success' => true,
                'message' => 'Upload deleted successfully'
            ]);

            break;


        // ============================================================
        // ADMIN - REVIEW
        //
        // Approved
        // Needs Revision
        // Rejected
        // ============================================================

        case 'admin_review':

            $id =
                (int)($_POST['id'] ?? 0);


            $status =
                trim(
                    $_POST['review_status']
                    ?? ''
                );


            $comment =
                trim(
                    $_POST['review_comment']
                    ?? ''
                );


            if (!$id) {

                respond([
                    'success' => false,
                    'message' => 'Missing upload id'
                ]);
            }


            if (
                !in_array(
                    $status,
                    [
                        'Approved',
                        'Needs Revision',
                        'Rejected'
                    ],
                    true
                )
            ) {

                respond([
                    'success' => false,
                    'message' => 'Invalid review status'
                ]);
            }


            $ok =
                EmployeeWorkUploadModel::adminReview(
                    $id,
                    $status,
                    $comment,
                    $adminId
                );


            respond([
                'success' => $ok,
                'message' =>
                    $ok
                    ? 'Review updated successfully'
                    : 'Upload not found'
            ]);

            break;


        // ============================================================
        // EMPLOYEE - MY UPLOADS
        // ============================================================

        case 'my_uploads':

            if (!$employeeId) {

                respond([
                    'success' => false,
                    'message' => 'Not logged in'
                ]);
            }


            $data =
                EmployeeWorkUploadModel::myUploads(
                    (int)$employeeId
                );


            respond([
                'success' => true,
                'data' => $data
            ]);

            break;


        // ============================================================
        // EMPLOYEE - GET REVISION SUBMISSION
        // ============================================================

        case 'employee_get':

            if (!$employeeId) {

                respond([
                    'success' => false,
                    'message' => 'Not logged in'
                ]);
            }


            $id =
                (int)($_GET['id'] ?? 0);


            if (!$id) {

                respond([
                    'success' => false,
                    'message' => 'Missing upload id'
                ]);
            }


            $data =
                EmployeeWorkUploadModel::employeeGet(
                    $id,
                    (int)$employeeId
                );


            if (!$data) {

                respond([
                    'success' => false,
                    'message' =>
                        'Upload not found or access denied'
                ]);
            }


            if (
                ($data['review_status'] ?? '')
                !== 'Needs Revision'
            ) {

                respond([
                    'success' => false,
                    'message' =>
                        'This submission does not require revision'
                ]);
            }


            respond([
                'success' => true,
                'data' => $data
            ]);

            break;


        // ============================================================
        // EMPLOYEE - NEW UPLOAD
        // ============================================================

        case 'upload':

            if (!$employeeId) {

                respond([
                    'success' => false,
                    'message' => 'Not logged in'
                ]);
            }


            $title =
                trim(
                    $_POST['title'] ?? ''
                );


            if ($title === '') {

                respond([
                    'success' => false,
                    'message' => 'Title is required'
                ]);
            }


            $data = [

                'title' =>
                    $title,

                'description' =>
                    trim(
                        $_POST['description']
                        ?? ''
                    ),

                'category_id' =>
                    $_POST['category_id']
                    ?? '',

                'hours_worked' =>
                    (
                        isset($_POST['hours_worked'])
                        && $_POST['hours_worked'] !== ''
                    )
                    ? $_POST['hours_worked']
                    : null,

                'github_link' =>
                    trim(
                        $_POST['github_link']
                        ?? ''
                    ),

                'live_url' =>
                    trim(
                        $_POST['live_url']
                        ?? ''
                    ),

                'drive_link' =>
                    trim(
                        $_POST['drive_link']
                        ?? ''
                    ),

                'next_plan' =>
                    trim(
                        $_POST['next_plan']
                        ?? ''
                    )
            ];


            $id =
                EmployeeWorkUploadModel::createUpload(
                    (int)$employeeId,
                    $data,
                    $_FILES['attachments']
                    ?? []
                );


            respond([
                'success' => true,
                'id' => $id,
                'message' =>
                    'Work submitted successfully'
            ]);

            break;


        // ============================================================
        // EMPLOYEE - UPDATE / RESUBMIT
        // ONLY FOR NEEDS REVISION
        // ============================================================

        case 'update_submission':

            if (!$employeeId) {

                respond([
                    'success' => false,
                    'message' => 'Not logged in'
                ]);
            }


            $id =
                (int)($_POST['id'] ?? 0);


            if (!$id) {

                respond([
                    'success' => false,
                    'message' => 'Missing upload id'
                ]);
            }


            $title =
                trim(
                    $_POST['title'] ?? ''
                );


            if ($title === '') {

                respond([
                    'success' => false,
                    'message' => 'Title is required'
                ]);
            }


            $data = [

                'title' =>
                    $title,

                'description' =>
                    trim(
                        $_POST['description']
                        ?? ''
                    ),

                'category_id' =>
                    $_POST['category_id']
                    ?? '',

                'hours_worked' =>
                    (
                        isset($_POST['hours_worked'])
                        && $_POST['hours_worked'] !== ''
                    )
                    ? $_POST['hours_worked']
                    : null,

                'github_link' =>
                    trim(
                        $_POST['github_link']
                        ?? ''
                    ),

                'live_url' =>
                    trim(
                        $_POST['live_url']
                        ?? ''
                    ),

                'drive_link' =>
                    trim(
                        $_POST['drive_link']
                        ?? ''
                    ),

                'next_plan' =>
                    trim(
                        $_POST['next_plan']
                        ?? ''
                    )
            ];


            $ok =
                EmployeeWorkUploadModel::updateSubmission(
                    $id,
                    (int)$employeeId,
                    $data,
                    $_FILES['attachments']
                    ?? []
                );


            respond([
                'success' => $ok,
                'message' =>
                    $ok
                    ? 'Work updated and resubmitted successfully'
                    : 'Unable to update submission'
            ]);

            break;


        // ============================================================
        // UNKNOWN ACTION
        // ============================================================

        default:

            respond([
                'success' => false,
                'message' => 'Unknown action'
            ]);
    }


} catch (\Throwable $e) {

    error_log(
        'employeeworkuploadcontroller error: '
        . $e->getMessage()
    );


    respond([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}