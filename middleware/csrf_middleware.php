<?php

class CsrfMiddleware
{
    public function __invoke($request)
    {
        // Start session only if it is not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generate CSRF token only once
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Validate CSRF token for POST, PUT and DELETE requests
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {

            $token = $_POST['csrf_token'] ?? '';

            if (
                empty($token) ||
                !hash_equals($_SESSION['csrf_token'], $token)
            ) {
                http_response_code(403);
                exit('CSRF token validation failed.');
            }
        }
    }
}

?>