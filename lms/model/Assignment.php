<?php
require_once "../../DB Operations/dbconnection.php";
class Assignment {
    private $id;
    private $title;
    private $description;
    private $course_id;
    private $user_id;

    public function __construct($title, $description, $course_id, $user_id) {
        $this->title = $title;
        $this->description = $description;
        $this->course_id = $course_id;
        $this->user_id = $user_id;
    }

    // Getters and setters for each property
    public function getId() {
        return $this->id;
    }

    public function create(): int {
        // Code to insert the assignment into the database
        // Return the ID of the newly created assignment
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $sql = "INSERT INTO assignments (title, description, course_id, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $this->title, $this->description,  $this->course_id, $this->user_id);
        $stmt->execute();
        $this->id = $stmt->insert_id;
        return $this->id;
    }

    public static function all() {
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $result = $db->query("SELECT * FROM assignments join courses on assignments.course_id=courses.id");
        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }
        return $assignments;
    }

    public static function find($id) {
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $id = (int)$id;
        $result = $db->query("SELECT * FROM assignments WHERE id = $id");
        return $result->fetch_assoc();
    }

    public static function getByCourseId($course_id) {
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $course_id = (int)$course_id;
        $result = $db->query("SELECT * FROM assignments WHERE course_id = $course_id");
        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }
        return $assignments;
    }
    public static function edit($id, $title, $description) {
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $id = (int)$id;
        $title = $db->real_escape_string($title);
        $description = $db->real_escape_string($description);
        $db->query("UPDATE assignments SET title='$title', description='$description' WHERE id=$id");
        return $db->affected_rows > 0;
    }

    public static function delete($id) {
        $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $id = (int)$id;
        $db->query("DELETE FROM assignments WHERE id = $id");
    }
}