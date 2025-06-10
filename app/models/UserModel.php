<?php
require_once __DIR__ . '/../../config/config.php';

class UserModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die('Database connection failed');
        }
    }
    public function findUser($username) {
        $stmt = $this->conn->prepare('SELECT id, username, password, role FROM user WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    public function createUser($username, $password, $email = null, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare('INSERT INTO user (username, password, email, role) VALUES (?, ?, ?, ?)');
        if (!$stmt) {
            return ['success' => false, 'message' => $this->conn->error];
        }
        $stmt->bind_param('ssss', $username, $hashedPassword, $email, $role);
        $result = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => $error];
        }
    }
    public function updateProfile($id, $first_name, $last_name, $middle_name, $date_of_birth, $place_of_birth, $gender, $email, $contact_num) {
        $stmt = $this->conn->prepare('UPDATE user SET first_name=?, last_name=?, middle_name=?, date_of_birth=?, place_of_birth=?, gender=?, email=?, contact_num=? WHERE id=?');
        if (!$stmt) {
            return ['success' => false, 'message' => $this->conn->error];
        }
        $stmt->bind_param('ssssssssi', $first_name, $last_name, $middle_name, $date_of_birth, $place_of_birth, $gender, $email, $contact_num, $id);
        $result = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => $error];
        }
    }
    public function findUserById($id) {
        $stmt = $this->conn->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    public function __destruct() {
        $this->conn->close();
    }
} 