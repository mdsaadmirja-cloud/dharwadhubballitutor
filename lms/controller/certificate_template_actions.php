<?php
// lms/controller/certificate_template_actions.php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once "../model/CertificateTemplate.php";

$templateModel = new CertificateTemplate();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

/*
|--------------------------------------------------------------------------
| Get Logged-in Admin ID
|--------------------------------------------------------------------------
*/
$created_by = 0;

if (isset($_SESSION['user_id'])) {
    $created_by = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['login_user_id'])) {
    $created_by = (int)$_SESSION['login_user_id'];
} elseif (isset($_SESSION['id'])) {
    $created_by = (int)$_SESSION['id'];
}


/*
|--------------------------------------------------------------------------
| Certificate Template Upload Settings
|--------------------------------------------------------------------------
| Uploaded files are stored inside:
| lms/uploads/certificate_templates/
|
| The upload accepts normal certificate/template file formats.
| Server-executable file extensions are blocked for security.
|--------------------------------------------------------------------------
*/

function uploadCertificateTemplate($file)
{
    if (!isset($file) || !is_array($file)) {
        return [
            'success' => false,
            'message' => 'No template file uploaded.'
        ];
    }

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {

        $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);

        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'Uploaded file is larger than the server limit.',
            UPLOAD_ERR_FORM_SIZE  => 'Uploaded file is larger than the allowed form limit.',
            UPLOAD_ERR_PARTIAL    => 'The template file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'Please select a template file.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server temporary upload folder is missing.',
            UPLOAD_ERR_CANT_WRITE => 'Server could not write the uploaded file.',
            UPLOAD_ERR_EXTENSION  => 'File upload was stopped by a server extension.'
        ];

        return [
            'success' => false,
            'message' => $messages[$error] ?? 'Failed to upload template file.'
        ];
    }

    /*
    | Maximum application-level size: 50 MB.
    | PHP server upload limits must also allow this size.
    */
    $maxSize = 50 * 1024 * 1024;

    if (($file['size'] ?? 0) <= 0) {
        return [
            'success' => false,
            'message' => 'The selected template file is empty.'
        ];
    }

    if (($file['size'] ?? 0) > $maxSize) {
        return [
            'success' => false,
            'message' => 'Template file size must not exceed 50 MB.'
        ];
    }

    $originalName = $file['name'] ?? '';
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if ($extension === '') {
        return [
            'success' => false,
            'message' => 'The uploaded file must have a file extension.'
        ];
    }

    /*
    | Never allow executable/server-side files inside the upload directory.
    */
    $blockedExtensions = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8',
        'phtml', 'pht', 'phar',
        'cgi', 'pl', 'py', 'pyc',
        'sh', 'bash',
        'exe', 'com', 'bat', 'cmd', 'msi', 'dll'
    ];

    if (in_array($extension, $blockedExtensions, true)) {
        return [
            'success' => false,
            'message' => 'This file format is not allowed for security reasons.'
        ];
    }

    $uploadDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads'
        . DIRECTORY_SEPARATOR . 'certificate_templates';

    if (!is_dir($uploadDirectory)) {
        if (!mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
            return [
                'success' => false,
                'message' => 'Unable to create certificate template upload directory.'
            ];
        }
    }

    if (!is_writable($uploadDirectory)) {
        return [
            'success' => false,
            'message' => 'Certificate template upload directory is not writable.'
        ];
    }

    /*
    | Keep the original extension but generate a unique safe filename.
    */
    $safeExtension = preg_replace('/[^a-zA-Z0-9]/', '', $extension);

    if ($safeExtension === '') {
        return [
            'success' => false,
            'message' => 'Invalid template file extension.'
        ];
    }

    $newFileName = 'certificate_template_' . date('YmdHis') . '_'
        . bin2hex(random_bytes(6)) . '.' . $safeExtension;

    $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $newFileName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => false,
            'message' => 'Failed to save the uploaded certificate template.'
        ];
    }

    /*
    | This is the value stored in the database.
    | Example:
    | uploads/certificate_templates/certificate_template_20260819160000_ab12cd34ef56.png
    */
    $relativePath = 'uploads/certificate_templates/' . $newFileName;

    return [
        'success' => true,
        'path' => $relativePath,
        'filename' => $newFileName,
        'original_name' => $originalName
    ];
}


/*
|--------------------------------------------------------------------------
| Get Uploaded Template File
|--------------------------------------------------------------------------
*/
function getUploadedTemplateFile()
{
    if (
        isset($_FILES['template_file']) &&
        is_array($_FILES['template_file']) &&
        ($_FILES['template_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE
    ) {
        return $_FILES['template_file'];
    }

    /*
    | Also support template_file[] style if the form is changed later.
    */
    if (
        isset($_FILES['template_file']['name']) &&
        is_array($_FILES['template_file']['name'])
    ) {
        return null;
    }

    return null;
}


/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/
if ($action === 'create') {

    $name = trim($_POST['name'] ?? '');
    $template_file = trim($_POST['template_file'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if ($name === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Template name is required.'
        ]);
        exit;
    }

    /*
    | If a real file was selected, upload it.
    | This supports:
    | <input type="file" name="template_file">
    */
    $uploadedFile = getUploadedTemplateFile();

    if ($uploadedFile !== null) {

        $uploadResult = uploadCertificateTemplate($uploadedFile);

        if (!$uploadResult['success']) {
            echo json_encode([
                'success' => false,
                'message' => $uploadResult['message']
            ]);
            exit;
        }

        $template_file = $uploadResult['path'];
    }

    if ($template_file === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Template file is required.'
        ]);
        exit;
    }

    if ($created_by <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Admin session not found.'
        ]);
        exit;
    }

    $data = [
        'name' => $name,
        'template_file' => $template_file,
        'description' => $description,
        'status' => $status,
        'created_by' => $created_by
    ];

    $templateId = $templateModel->create($data);

    if ($templateId) {
        echo json_encode([
            'success' => true,
            'message' => 'Certificate template created successfully.',
            'id' => $templateId,
            'template_file' => $template_file
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create certificate template.'
        ]);
    }

    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/
if ($action === 'update') {

    $id = (int)($_POST['id'] ?? 0);

    $name = trim($_POST['name'] ?? '');
    $template_file = trim($_POST['template_file'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid template ID.'
        ]);
        exit;
    }

    if ($name === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Template name is required.'
        ]);
        exit;
    }

    /*
    | If a new file is uploaded during update, save the new file.
    | Otherwise keep the existing database value.
    */
    $uploadedFile = getUploadedTemplateFile();

    if ($uploadedFile !== null) {

        $uploadResult = uploadCertificateTemplate($uploadedFile);

        if (!$uploadResult['success']) {
            echo json_encode([
                'success' => false,
                'message' => $uploadResult['message']
            ]);
            exit;
        }

        $template_file = $uploadResult['path'];
    } else {

        /*
        | Do not erase the existing template when the admin edits
        | the name/description/status without selecting a new file.
        */
        if ($template_file === '') {

            $existingTemplate = $templateModel->getById($id);

            if ($existingTemplate && !empty($existingTemplate['template_file'])) {
                $template_file = $existingTemplate['template_file'];
            }
        }
    }

    if ($template_file === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Template file is required.'
        ]);
        exit;
    }

    $data = [
        'name' => $name,
        'template_file' => $template_file,
        'description' => $description,
        'status' => $status
    ];

    if ($templateModel->update($id, $data)) {

        echo json_encode([
            'success' => true,
            'message' => 'Certificate template updated successfully.',
            'template_file' => $template_file
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Failed to update certificate template.'
        ]);
    }

    exit;
}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/
if ($action === 'delete') {

    $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid template ID.'
        ]);
        exit;
    }

    if ($templateModel->delete($id)) {

        echo json_encode([
            'success' => true,
            'message' => 'Certificate template deleted successfully.'
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete certificate template.'
        ]);
    }

    exit;
}


/*
|--------------------------------------------------------------------------
| GET SINGLE TEMPLATE
|--------------------------------------------------------------------------
*/
if ($action === 'get') {

    $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid template ID.'
        ]);
        exit;
    }

    $template = $templateModel->getById($id);

    if ($template) {

        echo json_encode([
            'success' => true,
            'data' => $template
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Certificate template not found.'
        ]);
    }

    exit;
}


/*
|--------------------------------------------------------------------------
| GET ALL TEMPLATES
|--------------------------------------------------------------------------
*/
if ($action === 'list') {

    $templates = $templateModel->getAll();

    echo json_encode([
        'success' => true,
        'data' => $templates
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| INVALID ACTION
|--------------------------------------------------------------------------
*/
echo json_encode([
    'success' => false,
    'message' => 'Invalid action.'
]);

exit;
