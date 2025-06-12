<?php
header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$conn = get_db_connection();

// Get POST data
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Validate required fields
$required = ['fullname', 'complaint', 'details', 'date', 'slot'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required.']);
        exit;
    }
}

$fullname = trim($data['fullname']);
$complaint = trim($data['complaint']);
$details = trim($data['details']);
$priority = isset($data['priority']) ? trim($data['priority']) : 'None';
$date = $data['date'];
$slot = $data['slot'];
$status = 'Pending';
$created_at = date('Y-m-d H:i:s');

// Prepare and execute insert
$stmt = $conn->prepare("INSERT INTO consultations (full_name, complaint, details, priority, preferred_date, time_slot, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param('ssssssss', $fullname, $complaint, $details, $priority, $date, $slot, $status, $created_at);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}
$stmt->close();
$conn->close(); 