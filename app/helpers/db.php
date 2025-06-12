<?php
require_once dirname(__DIR__, 2) . '/config/config.php';

function get_db_connection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }
    return $conn;
} 