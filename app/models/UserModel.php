<?php
class UserModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'brgy_system_db');
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
    public function __destruct() {
        $this->conn->close();
    }
} 