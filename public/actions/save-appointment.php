<?php
header('Content-Type: application/json');

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in output
ini_set('log_errors', 1);

try {
    require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
    require_role('admin');
    require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

    $conn = get_db_connection();

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$required = ['fullName', 'complaint', 'date', 'slot'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required.']);
        exit;
    }
}

$fullName = trim($data['fullName']);
$complaint = trim($data['complaint']);
$priority = isset($data['priority']) ? trim($data['priority']) : 'None';
$date = $data['date'];
$slot = $data['slot'];
$remarks = isset($data['remarks']) ? trim($data['remarks']) : '';
$status = 'Scheduled'; // Admin created appointments are immediately scheduled
$created_at = date('Y-m-d H:i:s');

// Full Name: at least two words, only letters and spaces
if (!preg_match('/^[A-Za-z ]{3,}$/', $fullName) || count(explode(' ', $fullName)) < 2) {
    echo json_encode(['success' => false, 'message' => 'Full name must be at least two words and letters only.']);
    exit;
}

// Complaint: at least 3 characters
if (strlen($complaint) < 3) {
    echo json_encode(['success' => false, 'message' => 'Complaint must be at least 3 characters.']);
    exit;
}

// Date: today or future
if (strtotime($date) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'Date must be today or in the future.']);
    exit;
}

// Check if date is a weekend
$selectedDateTime = new DateTime($date);
$dayOfWeek = $selectedDateTime->format('w');
if ($dayOfWeek == 0 || $dayOfWeek == 6) {
    echo json_encode(['success' => false, 'message' => 'Appointments are only available Monday to Friday.']);
    exit;
}

// Check for duplicate appointment (global, block if not cancelled)
$stmt_check = $conn->prepare("SELECT id FROM appointments WHERE preferred_date = ? AND time_slot = ? AND status != 'Cancelled' LIMIT 1");
$stmt_check->bind_param('ss', $date, $slot);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'An appointment already exists for this date and time slot.']);
    exit;
}
$stmt_check->close();

// Check for duplicate consultation in the same slot
$stmt_consult_check = $conn->prepare("SELECT id FROM consultations WHERE preferred_date = ? AND time_slot = ? AND status = 'Pending' LIMIT 1");
$stmt_consult_check->bind_param('ss', $date, $slot);
$stmt_consult_check->execute();
$result_consult_check = $stmt_consult_check->get_result();
if ($result_consult_check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'A consultation request already exists for this date and time slot.']);
    exit;
}
$stmt_consult_check->close();

// Try to get user_id from consultations table based on full name (optional)
$user_id = null;
$stmt_user = $conn->prepare("SELECT user_id FROM consultations WHERE full_name = ? ORDER BY created_at DESC LIMIT 1");
$stmt_user->bind_param('s', $fullName);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($row = $result_user->fetch_assoc()) {
    $user_id = $row['user_id'];
}
$stmt_user->close();

// Prepare and execute insert (matching existing table structure)
if ($user_id) {
    // Include user_id if found
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, full_name, complaint, preferred_date, time_slot, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('isssss', $user_id, $fullName, $complaint, $date, $slot, $status);
} else {
    // Insert without user_id if not found
    $stmt = $conn->prepare("INSERT INTO appointments (full_name, complaint, preferred_date, time_slot, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('sssss', $fullName, $complaint, $date, $slot, $status);
}

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Appointment created successfully.']);
} else {
    $error_msg = 'Database error: ' . $stmt->error . ' | MySQL Error: ' . $conn->error;
    error_log('MySQL Error in save-appointment.php: ' . $error_msg);
    echo json_encode(['success' => false, 'message' => $error_msg]);
}

$stmt->close();
$conn->close();

} catch (Exception $e) {
    error_log('Appointment creation error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while creating the appointment: ' . $e->getMessage()]);
}
?> 