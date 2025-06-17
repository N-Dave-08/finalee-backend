<?php
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$conn = get_db_connection();

$full_name = $conn->real_escape_string($_POST['fullName']);
$contact_number = $conn->real_escape_string($_POST['contactNumber']);
$document_type = $conn->real_escape_string($_POST['documentType']);
$date_of_birth = $conn->real_escape_string($_POST['birthDate']);

session_name('finalee_session');
session_start();
$user_id = $_SESSION['user']['id'];

$sql = "INSERT INTO medical_documents_requests
    (user_id, full_name, contact_number, document_type, date_of_birth, status)
    VALUES
    ('$user_id', '$full_name', '$contact_number', '$document_type', '$date_of_birth', 'Pending')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    $reference_number = '#DOC-' . str_pad($last_id, 3, '0', STR_PAD_LEFT);
    $conn->query("UPDATE medical_documents_requests SET reference_number = '$reference_number' WHERE id = $last_id");
    echo "<script>alert('Form submitted successfully!'); window.location.href='/finalee/public/home.php';</script>";
    exit();
} else {
    echo "<script>alert('There was an error submitting your request.'); window.history.back();</script>";
    exit();
}

$conn->close(); 