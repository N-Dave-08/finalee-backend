<?php
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$conn = get_db_connection();

$full_name = $conn->real_escape_string($_POST['fullName']);
$contact_number = $conn->real_escape_string($_POST['contactNumber']);
$document_type = $conn->real_escape_string($_POST['documentType']);
$date_of_birth = $conn->real_escape_string($_POST['birthDate']);

// Generate a unique reference number (#DOC-001)
$ref_query = $conn->query("SELECT COUNT(*) as count FROM medical_documents_requests");
$row = $ref_query->fetch_assoc();
$reference_number = '#DOC-' . str_pad($row['count'] + 1, 3, '0', STR_PAD_LEFT);


$sql = "INSERT INTO medical_documents_requests
    (reference_number, full_name, contact_number, document_type, date_of_birth, status)
    VALUES
    ('$reference_number', '$full_name', '$contact_number', '$document_type', '$date_of_birth', 'Pending')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Form submitted successfully!'); window.location.href='/finalee/public/home.php';</script>";
    exit();
} else {
    echo "<script>alert('There was an error submitting your request.'); window.history.back();</script>";
    exit();
}

$conn->close(); 