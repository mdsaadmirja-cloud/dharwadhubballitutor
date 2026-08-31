<?php
// lms/views/logout.php
require_once __DIR__ . '/../controller/AuthController.php';
AuthController::logout();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/lms/css/lms.css">
</head>
<body>
<div class="container">
<!-- Logout is handled by PHP, so no visible content needed -->
</div>
</body>
</html> 