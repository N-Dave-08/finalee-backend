<?php
require_once __DIR__ . '/../../../app/controllers/MedicineController.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$data = [
    'name' => $_POST['name'] ?? '',
    'category' => $_POST['category'] ?? '',
    'dosage' => $_POST['dosage'] ?? '',
    'quantity' => $_POST['quantity'] ?? 0,
    'expiry_date' => $_POST['expiry_date'] ?? '',
];

$controller = new MedicineController();
$success = $controller->store($data);
echo json_encode(['success' => $success]); 