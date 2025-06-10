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
    public function __destruct() {
        $this->conn->close();
    }
} 