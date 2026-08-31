<?php
require_once '../model/Assignment.php';

class AssignmentController {
    public static function addAssignment($title, $description, $course_id, $user_id) {
        // Code to add assignment
        $assignment = new Assignment($title, $description, $course_id, $user_id);
        $assignment->create();
        return $assignment->getId();
    }

    public static function getAllAssignments() {
        // Code to get all assignments
        return Assignment::all();
    }
    public static function getAssignmentById($id) {
        // Code to get assignment by ID
        return Assignment::find($id);
    }
    public static function getAssignmentsByCourseId($course_id) {
        // Code to get assignments by course ID
        return Assignment::getByCourseId($course_id);
    }
}