<?php
require_once __DIR__ . '/../../config/config.php';

class EmergencyContactModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die('Database connection failed');
        }
    }
    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare('SELECT * FROM emergency_contact WHERE user_id = ? LIMIT 1');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $contact = $result->fetch_assoc();
        $stmt->close();
        return $contact;
    }
    public function upsert($user_id, $first_name, $last_name, $middle_name, $relationship, $contact_num, $email) {
        // Check if exists
        $existing = $this->getByUserId($user_id);
        if ($existing) {
            $stmt = $this->conn->prepare('UPDATE emergency_contact SET first_name=?, last_name=?, middle_name=?, relationship=?, contact_num=?, email=? WHERE user_id=?');
            $stmt->bind_param('ssssssi', $first_name, $last_name, $middle_name, $relationship, $contact_num, $email, $user_id);
        } else {
            $stmt = $this->conn->prepare('INSERT INTO emergency_contact (user_id, first_name, last_name, middle_name, relationship, contact_num, email) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('issssss', $user_id, $first_name, $last_name, $middle_name, $relationship, $contact_num, $email);
        }
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