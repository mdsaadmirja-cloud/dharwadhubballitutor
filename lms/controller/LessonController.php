<?php
// lms/controller/LessonController.php
require_once '../model/Lesson.php';
require_once '../model/LessonCompletion.php';
require_once '../Utilities/EmailHelper.php';
require_once '../model/User.php';

class LessonController {
    public static function addLesson($title, $description, $video_url, $created_by, $courseId = 0, $category = '') {
        $file_url = null;
        /*if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('video_', true) . '.' . $ext;
            $target = __DIR__ . '/../uploads/' . $filename;
            if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target)) {
                $file_url = '/lms/uploads/' . $filename;
            }
        }*/
        $final_video_url = $file_url ?: $video_url;
        error_log("coursesopted: " . $courseId, 0);
        $lesson_id = Lesson::create($title, $description, $final_video_url, $created_by, $courseId,  $category);
        // Email notification to all students
        /*$students = User::getAllStudents();
        foreach ($students as $student) {
            if (filter_var($student['email'], FILTER_VALIDATE_EMAIL)) {
                $subject = 'New Lesson Added: ' . $title;
                $msg = '<p>Dear ' . htmlspecialchars($student['name']) . ',</p>';
                $msg .= '<p>A new lesson has been added: <strong>' . htmlspecialchars($title) . '</strong></p>';
                $msg .= '<p>Description: ' . nl2br(htmlspecialchars($description)) . '</p>';
                $msg .= '<p><a href="https://' . $_SERVER['HTTP_HOST'] . '/lms/views/lesson_view.php?id=' . $lesson_id . '">View Lesson</a></p>';
                EmailHelper::send($student['email'], $subject, $msg);
            }
        }*/
        return $lesson_id;
    }

    public static function getAllLessons() {
        return Lesson::all();
    }
    public static function getLessonByUserEmailId($id) {
        return Lesson::getLessonByUserEmailId($id);
    }
    public static function getLessonByCourseId($id) {
        return Lesson::find($id);
    }

    public static function markCompleted($user_id, $lesson_id) {
        return LessonCompletion::markCompleted($user_id, $lesson_id);
    }

    public static function getCompletedLessons($user_id) {
        return LessonCompletion::getCompletedLessons($user_id);
    }

    public static function editLesson($id, $title, $description, $video_url) {
        return Lesson::edit($id, $title, $description, $video_url);
    }

    public static function deleteLesson($id) {
        error_log("Deleting lesson with ID: $id", 0);
        return Lesson::delete($id);
    }
} 