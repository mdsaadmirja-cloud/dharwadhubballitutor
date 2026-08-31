<?php
// --- NO PHP CODE WAS CHANGED HERE ---
// lms/views/edit_lesson.php
session_start();
// Corrected check for line 4
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/../controller/LessonController.php';
require_once __DIR__ . '/../model/Lesson.php';
require_once "../../Admin/DB Operations/CoursesOps.php";

$lesson = Lesson::find($_GET['id']);
if (!$lesson) {
    echo '<div class="error">Lesson not found.</div>';
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    LessonController::editLesson($lesson['id'], $_POST['title'], $_POST['description'], $_POST['video_url']);
    header('Location: admin_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Lesson</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/img/favicon.png">
    <style>
        :root {
            --primary-color: #6a5af9;
            --primary-hover: #5a4af7;
            --secondary-color: #7b86a2;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --body-bg: #eef1f5;
            --card-bg: rgba(255, 255, 255, 0.7);
            --card-border-color: rgba(255, 255, 255, 0.5);
            --border-radius-lg: 1rem;
            --border-radius-md: 0.5rem;
            --shadow-medium: 0 8px 30px rgba(0,0,0,0.1);
            --font-family: 'Be Vietnam Pro', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            background-image:
                radial-gradient(circle at 10% 20%, rgb(222, 222, 255) 0%, rgba(222, 222, 255, 0) 50%),
                radial-gradient(circle at 80% 90%, rgb(250, 215, 225) 0%, rgba(250, 215, 225, 0) 40%);
            font-family: var(--font-family);
            color: var(--dark-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .edit-card {
            width: 100%;
            max-width: 700px;
            background: var(--card-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--card-border-color);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, #6a5af9, #d66bff);
            padding: 1.5rem 2rem;
            color: #fff;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: var(--border-radius-md);
            border: 1px solid #ced4da;
            padding: 0.85rem 1rem;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 90, 249, 0.2);
            background-color: #fff;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), #8e44ad);
            border: none;
            font-weight: 600;
            padding: 0.85rem 1.5rem;
            border-radius: var(--border-radius-md);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(106, 90, 249, 0.3);
            color: #fff;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(106, 90, 249, 0.4);
            color: #fff;
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            font-weight: 600;
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>

<div class="edit-card animate__animated animate__fadeInUp">
    <div class="card-header">
        <h2><i class="fas fa-pencil-alt me-3"></i>Edit Lesson</h2>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-4">
                                <label for="coursesopted" class="form-label">Course</label>
                                <select class="form-select" id="coursesopted" name="coursesopted" required>
                                    <option value="" disabled selected>Select a course</option>
                                    <?php
                                    $courselist = DBcourse::selectall();
                                    foreach ($courselist as $course) {
                                        if($course->get_id() == $lesson['courseId']) {
                                        echo "<option selected value='" . $course->get_id() . "'>" . htmlspecialchars($course->get_cname()) . "</option>";
                                        }else{
                                             echo "<option value='" . $course->get_id() . "'>" . htmlspecialchars($course->get_cname()) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
            </div>
            <div class="mb-4">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" class="form-control" name="title" value="<?php echo htmlspecialchars($lesson['title']); ?>" placeholder="e.g., Introduction to CSS Flexbox" required>
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-control" name="description" rows="4" placeholder="A brief summary of what this lesson covers..."><?php echo htmlspecialchars($lesson['description']); ?></textarea>
            </div>
            
            <div class="mb-4">
                <label for="video_url" class="form-label">Video URL</label>
                <input type="url" id="video_url" class="form-control" name="video_url" value="<?php echo htmlspecialchars($lesson['video_url']); ?>" placeholder="https://example.com/video">
            </div>
            
            

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Lesson</button>
            </div>

            
        </form>
        <div class="text-center">
            <a href="admin_dashboard.php" class="back-link"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>