<?php
// lms/views/lesson_view.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../model/Lesson.php';
require_once __DIR__ . '/../controller/LessonController.php';
$lesson = Lesson::find($_GET['id']);
if (!$lesson) {
    echo '<div class="error">Lesson not found.</div>';
    exit();
}
$completed = LessonController::getCompletedLessons($_SESSION['user']['id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    LessonController::markCompleted($_SESSION['user']['id'], $lesson['id']);
    header('Location: lesson_view.php?id=' . $lesson['id']);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($lesson['title']); ?> - Lesson</title>
    <link rel="stylesheet" href="/lms/css/lms.css">
</head>
<body>
<div class="container">
    <h2><?php echo htmlspecialchars($lesson['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($lesson['description'])); ?></p>
    <?php if (preg_match('/\.(mp4|webm|ogg)$/i', $lesson['video_url'])): ?>
        <video width="100%" height="360" controls>
            <source src="<?php echo htmlspecialchars($lesson['video_url']); ?>">
            Your browser does not support the video tag.
        </video>
    <?php else: ?>
        <a href="<?php echo htmlspecialchars($lesson['video_url']); ?>" target="_blank">Watch Video</a>
    <?php endif; ?>
    <br><br>
    <?php if (in_array($lesson['id'], $completed)): ?>
        <span class="success">(Completed)</span>
    <?php else: ?>
        <form method="post"><button type="submit">Mark as Completed</button></form>
    <?php endif; ?>
    <br><a href="student_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html> 