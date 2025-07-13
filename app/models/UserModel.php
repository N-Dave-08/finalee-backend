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
    public function createUser($username, $password, $email = null, $role = 'user', $first_name = '', $middle_name = '', $last_name = '', $contact_num = '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare('INSERT INTO user (username, password, email, role, first_name, middle_name, last_name, contact_num) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        if (!$stmt) {
            return ['success' => false, 'message' => $this->conn->error];
        }
        $stmt->bind_param('ssssssss', $username, $hashedPassword, $email, $role, $first_name, $middle_name, $last_name, $contact_num);
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
    public function findUserByEmail($email) {
        $stmt = $this->conn->prepare('SELECT * FROM user WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    public function setResetToken($userId, $token, $expiry) {
        $stmt = $this->conn->prepare('UPDATE user SET reset_token = ?, reset_token_expiry = ? WHERE id = ?');
        if (!$stmt) {
            return ['success' => false, 'message' => $this->conn->error];
        }
        $stmt->bind_param('ssi', $token, $expiry, $userId);
        $result = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => $error];
        }
    }
    public function countUsersByRole($role) {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM user WHERE role = ?');
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }
    public function countUsersToday($role) {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM user WHERE role = ? AND DATE(created_at) = CURDATE()');
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }
    public function countUsersThisWeek($role) {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM user WHERE role = ? AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)');
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }
    public function countUsersThisMonth($role) {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM user WHERE role = ? AND YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())');
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }
    public function __destruct() {
        $this->conn->close();
    }
} 