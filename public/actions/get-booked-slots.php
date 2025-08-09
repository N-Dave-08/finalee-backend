<?php
header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';
session_name('finalee_session');
session_start();

$date = isset($_GET['date']) ? $_GET['date'] : '';
if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing date.']);
    exit;
}

$user_id = $_SESSION['user']['id'] ?? null;
$conn = get_db_connection();
$booked = [];
$pending_counts = [];

// Fetch from consultations (count pending requests per slot)
$stmt = $conn->prepare("SELECT time_slot, COUNT(*) as cnt FROM consultations WHERE preferred_date = ? AND status = 'Pending' GROUP BY time_slot");
$stmt->bind_param('s', $date);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $pending_counts[$row['time_slot']] = (int)$row['cnt'];
}
$stmt->close();

// Fetch from consultations (treat as booked if status is Pending, Scheduled, or Completed)
$stmt2 = $conn->prepare("SELECT time_slot FROM consultations WHERE preferred_date = ? AND status IN ('Pending', 'Scheduled', 'Completed')");
$stmt2->bind_param('s', $date);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
    // Normalize to en dash with single spaces for robust matching
    $normalized = preg_replace('/\s*-\s*/u', ' – ', $row['time_slot']);
    $booked[] = $normalized;
}
$stmt2->close();

// Fetch from appointments table (treat as booked if status is not Cancelled)
$stmt_appointments = $conn->prepare("SELECT time_slot FROM appointments WHERE preferred_date = ? AND status != 'Cancelled'");
$stmt_appointments->bind_param('s', $date);
$stmt_appointments->execute();
$res_appointments = $stmt_appointments->get_result();
while ($row = $res_appointments->fetch_assoc()) {
    // Normalize to en dash with single spaces for robust matching
    $normalized = preg_replace('/\s*-\s*/u', ' – ', $row['time_slot']);
    $booked[] = $normalized;
}
$stmt_appointments->close();

// Fetch slots that current user already has pending consultations for (if user is logged in)
if ($user_id) {
    $stmt3 = $conn->prepare("SELECT time_slot FROM consultations WHERE user_id = ? AND preferred_date = ? AND status = 'Pending'");
    $stmt3->bind_param('is', $user_id, $date);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    while ($row = $res3->fetch_assoc()) {
        $normalized = preg_replace('/\s*-\s*/u', ' – ', $row['time_slot']);
        $booked[] = $normalized;
    }
    $stmt3->close();
}

$conn->close();

// Remove duplicates
$booked = array_values(array_unique($booked));
echo json_encode(['success' => true, 'booked' => $booked, 'pending_counts' => $pending_counts]); 