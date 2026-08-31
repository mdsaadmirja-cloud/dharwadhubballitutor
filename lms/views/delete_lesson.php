<?php
// lms/views/delete_lesson.php
session_start();
// Check if the user is logged in and is an admin
// If not, redirect to login page
error_log("Session user: " . (isset($_SESSION['user']) ? $_SESSION['user'] : 'none'), 0);
error_log("Session role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'none'), 0);
if (!isset($_SESSION['user']) || $_SESSION['role']!== 'admin') {
    header('Location: login.php');
    exit();
}
require_once  '../controller/LessonController.php';
error_log("User " . $_SESSION['user'] . " is attempting to delete a lesson.", 0);
// Check if the lesson ID is provided
error_log("Received lesson ID: " . (isset($_GET['id']) ? $_GET['id'] : 'none'), 0); 
if (isset($_GET['id'])) {
    // Log the deletion attempt
    error_log("Attempting to delete lesson with ID: " . $_GET['id'], 0);
    LessonController::deleteLesson($_GET['id']);
}
header('Location: admin_dashboard.php');
exit(); 