<?php
// lms/model/User.php
require_once "../../DB Operations/dbconnection.php";

class User {
    public static function findOrCreateGoogleUser($userInfo) {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $email = $db->real_escape_string($userInfo->email);
        $google_id = $db->real_escape_string($userInfo->id);
        $name = $db->real_escape_string($userInfo->name);
        $result = $db->query("SELECT * FROM users WHERE google_id='$google_id' OR email='$email'");
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            $db->query("INSERT INTO users (name, email, google_id, role) VALUES ('$name', '$email', '$google_id', 'student')");
            $id = $db->insert_id;
            return ['id' => $id, 'name' => $name, 'email' => $email, 'google_id' => $google_id, 'role' => 'student'];
        }
    }

    public static function findByEmail($email) {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $email = $db->real_escape_string($email);
        $result = $db->query("SELECT * FROM users WHERE email='$email'");
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public static function getAllTeachers() {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $result = $db->query("SELECT * FROM users WHERE role='admin'");
        $teachers = [];
        while ($row = $result->fetch_assoc()) {
            $teachers[] = $row;
        }
        return $teachers;
    }
    public static function getAllStudents() {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $result = $db->query("SELECT * FROM users WHERE role='student'");
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    }
    
    public static function findById($id) {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $id = (int)$id;
        $result = $db->query("SELECT * FROM users WHERE id=$id");
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public static function updateProfile($id, $data) {
       $db = ConnectDb::getInstance();
        $db = $db->getConnection();
        $id = (int)$id;
        $updates = [];
        if (isset($data['name'])) {
            $name = $db->real_escape_string($data['name']);
            $updates[] = "name='" . $name . "'";
        }
        if (isset($data['college'])) {
            $college = $db->real_escape_string($data['college']);
            $updates[] = "college='" . $college . "'";
        }
        if (empty($updates)) {
            return false;
        }
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id=$id";
        $ok = $db->query($sql);
        return $ok;
    }

    public static function hasCollege($id) {
        $user = self::findById($id);
        if (!$user) { return false; }
        return isset($user['college']) && trim($user['college']) !== '';
    }
} 