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

// Fetch from appointments
$stmt2 = $conn->prepare("SELECT time_slot FROM appointments WHERE preferred_date = ? AND status != 'Cancelled'");
$stmt2->bind_param('s', $date);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
    $booked[] = $row['time_slot'];
}
$stmt2->close();

// Fetch slots that current user already has pending consultations for
if ($user_id) {
    $stmt3 = $conn->prepare("SELECT time_slot FROM consultations WHERE user_id = ? AND preferred_date = ? AND status = 'Pending'");
    $stmt3->bind_param('is', $user_id, $date);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    while ($row = $res3->fetch_assoc()) {
        $booked[] = $row['time_slot'];
    }
    $stmt3->close();
}

$conn->close();

// Remove duplicates
$booked = array_values(array_unique($booked));
echo json_encode(['success' => true, 'booked' => $booked, 'pending_counts' => $pending_counts]); 