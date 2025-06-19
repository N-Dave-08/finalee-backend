<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

$conn = get_db_connection();
$stmt = $conn->prepare('UPDATE appointments SET status = ? WHERE id = ?');
$stmt->bind_param('si', $status, $id);
$success = $stmt->execute();
$stmt->close();

// If marking as Completed, also update the related consultation's status
if ($status === 'Completed') {
    $stmt2 = $conn->prepare('SELECT consultation_id FROM appointments WHERE id = ?');
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $stmt2->bind_result($consultation_id);
    $stmt2->fetch();
    $stmt2->close();

    if ($consultation_id) {
        $completed = 'Completed';
        $stmt3 = $conn->prepare('UPDATE consultations SET status = ? WHERE id = ?');
        $stmt3->bind_param('si', $completed, $consultation_id);
        $stmt3->execute();
        $stmt3->close();
    }
}

$conn->close();

echo json_encode(['success' => $success]); 