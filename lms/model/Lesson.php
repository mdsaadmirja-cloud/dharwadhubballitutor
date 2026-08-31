<?php
// lms/model/Lesson.php
require_once "../../DB Operations/dbconnection.php";

class Lesson {
    public static function create($title, $description, $video_url, $created_by, $courseId, $category) {
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $title = $db->real_escape_string($title);
        $description = $db->real_escape_string($description);

        $video_url = $db->real_escape_string($video_url);
        error_log("coursesopted: " . $courseId, 0);
        $courseId = (int)$courseId;
        $created_by = (int)$created_by;
        $category = $db->real_escape_string($category);
        $db->query("INSERT INTO lessons (title, description, video_url, courseId, created_by, category) VALUES ('$title', '$description', '$video_url', $courseId, $created_by, '$category')");
        return $db->insert_id;
    }

    public static function all() {
        $db = ConnectDb::getInstance();
         $db = $db->getConnection();
        $result = $db->query("SELECT * FROM lessons ORDER BY created_at DESC");
        $lessons = [];
        while ($row = $result->fetch_assoc()) {
            $lessons[] = $row;
        }
        return $lessons;
    }

    public static function edit($id, $title, $description, $video_url) {
        $db = ConnectDb::getInstance();
         $db = $db->getConnection();
        $id = (int)$id;
        $title = $db->real_escape_string($title);
        $description = $db->real_escape_string($description);
        $video_url = $db->real_escape_string($video_url);
        $db->query("UPDATE lessons SET title='$title', description='$description', video_url='$video_url' WHERE id=$id");
        return $db->affected_rows > 0;
    }

    public static function delete($id) {
        $db = ConnectDb::getInstance();
         $db = $db->getConnection();
        $id = (int)$id;
        error_log("Deleting lesson with ID: $id", 0);
        $db->query("DELETE FROM lessons WHERE id=$id");
        return $db->affected_rows > 0;
    }

    public static function find($id) {
         $db = ConnectDb::getInstance();
         $db = $db->getConnection();
        $id = (int)$id;
        $result = $db->query("SELECT * FROM lessons WHERE id=$id");
        return $result ? $result->fetch_assoc() : null;
    }
    public static function getLessonByUserEmailId($id) {
        $db = ConnectDb::getInstance();
         $db = $db->getConnection();
        $id = $db->real_escape_string($id);
        error_log("SELECT * FROM lessons JOIN admissions ON lessons.courseId = admissions.courseid WHERE admissions.Email = '$id'", 0);
        $result = $db->query("SELECT * FROM lessons JOIN admissions ON lessons.courseId = admissions.courseid WHERE admissions.Email = '$id'");

        $lessons = [];
        while ($row = $result->fetch_assoc()) {
            $lessons[] = $row;
        }
        return $lessons;
    }
} 