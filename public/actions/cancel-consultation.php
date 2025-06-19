<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_login();
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}
$consultation_id = (int)$data['id'];
$user_id = $_SESSION['user']['id'];

$conn = get_db_connection();
// Check if consultation exists and belongs to user and is pending
$sql = "SELECT id FROM consultations WHERE id = ? AND user_id = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $consultation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Consultation not found or cannot be canceled.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();
// Update status to Canceled
$sql = "UPDATE consultations SET status = 'Canceled' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $consultation_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
}
$stmt->close();
$conn->close(); 