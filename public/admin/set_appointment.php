<?php
require_once '../../app/helpers/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultation_id = intval($_POST['consultation_id']);
    $preferred_date = $_POST['preferred_date'];
    $time_slot = $_POST['time_slot'];

    $conn = get_db_connection();
    // Fetch consultation details
    $stmt = $conn->prepare("SELECT * FROM consultations WHERE id = ?");
    $stmt->bind_param("i", $consultation_id);
    $stmt->execute();
    $consultation = $stmt->get_result()->fetch_assoc();

    if ($consultation) {
        // Insert into appointments
        $stmt2 = $conn->prepare("INSERT INTO appointments (consultation_id, user_id, full_name, complaint, preferred_date, time_slot, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'Scheduled', NOW())");
        $stmt2->bind_param(
            "iissss",
            $consultation_id,
            $consultation['user_id'],
            $consultation['full_name'],
            $consultation['complaint'],
            $preferred_date,
            $time_slot
        );
        $stmt2->execute();

        // Update consultation status
        $stmt3 = $conn->prepare("UPDATE consultations SET appointment_status = 'Appointed', status = 'Scheduled' WHERE id = ?");
        $stmt3->bind_param("i", $consultation_id);
        $stmt3->execute();

        echo json_encode(['success' => true, 'message' => 'Appointment set successfully.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Consultation not found.']);
        exit;
    }
}
echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
?> 