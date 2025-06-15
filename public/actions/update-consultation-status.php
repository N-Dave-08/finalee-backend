<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

$conn = get_db_connection();
$stmt = $conn->prepare('UPDATE consultations SET status = ? WHERE id = ?');
$stmt->bind_param('si', $status, $id);
$success = $stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['success' => $success]); 