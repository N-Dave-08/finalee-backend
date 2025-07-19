<?php
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
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
        // Check for duplicate appointment (global)
        $stmt_check = $conn->prepare("SELECT id FROM appointments WHERE preferred_date = ? AND time_slot = ? LIMIT 1");
        $stmt_check->bind_param("ss", $preferred_date, $time_slot);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'An appointment already exists for this date and time.']);
            exit;
        }
        $stmt_check->close();

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

        // Fetch user's phone number
        $stmt_phone = $conn->prepare("SELECT contact_num FROM user WHERE id = ? LIMIT 1");
        $stmt_phone->bind_param("i", $consultation['user_id']);
        $stmt_phone->execute();
        $result_phone = $stmt_phone->get_result();
        $user_phone = null;
        if ($row = $result_phone->fetch_assoc()) {
            $user_phone = $row['contact_num'];
        }
        $stmt_phone->close();

        // Send SMS notification using Vonage
        if (!empty($user_phone)) {
            require_once '../../vendor/autoload.php';
            require_once '../../config/config.php';

            $basic  = new Basic(VONAGE_API_KEY, VONAGE_API_SECRET);
            $client = new Client($basic);

            $formatted_time_slot = str_replace(['–', '?'], '-', $time_slot);
            $message_body = "Your Appointment is set for $preferred_date at $formatted_time_slot\n\nComplaint: {$consultation['complaint']}\n\nLocation: Barangay Medicion 2-D Health Center, Imus. Cavite\n\nPlease arrive 10 minutes early";
            try {
                $response = $client->sms()->send(
                    new \Vonage\SMS\Message\SMS($user_phone, "MEDICION", $message_body)
                );
                $smsResponse = $response->current();
                if ($smsResponse->getStatus() != 0) {
                    $errorMsg = $smsResponse->getStatus() . ': ' . $smsResponse->getErrorText();
                    error_log('Vonage SMS error: ' . $errorMsg);
                    echo json_encode(['success' => false, 'message' => 'SMS sending failed: ' . $errorMsg]);
                    exit;
                }
            } catch (Exception $e) {
                error_log('Vonage SMS error: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'SMS sending failed: ' . $e->getMessage()]);
                exit;
            }
        }

        // Update consultation status
        $stmt3 = $conn->prepare("UPDATE consultations SET appointment_status = 'Appointed', status = 'Scheduled' WHERE id = ?");
        $stmt3->bind_param("i", $consultation_id);
        $stmt3->execute();

        // Mark all other pending consultations for this slot as 'Slot Unavailable'
        $stmt4 = $conn->prepare("UPDATE consultations SET status = 'Slot Unavailable' WHERE preferred_date = ? AND time_slot = ? AND status = 'Pending' AND id != ?");
        $stmt4->bind_param("ssi", $preferred_date, $time_slot, $consultation_id);
        $stmt4->execute();
        $stmt4->close();

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

<link rel="stylesheet" href="../assets/css/appointmentss.css" />
<!-- <script src="../assets/js/appointmentss.js"></script> -->
<script src="../assets/js/common.js"></script> 