<?php
// lms/model/LessonCompletion.php
require_once "../../DB Operations/dbconnection.php";

class LessonCompletion {
    public static function markCompleted($user_id, $lesson_id) {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $user_id = (int)$user_id;
        $lesson_id = (int)$lesson_id;
        $db->query("INSERT INTO lesson_completions (user_id, lesson_id) VALUES ($user_id, $lesson_id)");
        return $db->insert_id;
    }

    public static function getCompletedLessons($user_id) {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $user_id = (int)$user_id;
        $result = $db->query("SELECT lesson_id FROM lesson_completions WHERE user_id = $user_id");
        $completed = [];
        while ($row = $result->fetch_assoc()) {
            $completed[] = $row['lesson_id'];
        }
        return $completed;
    }
} 