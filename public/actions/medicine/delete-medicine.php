<?php
require_once __DIR__ . '/../../../app/controllers/MedicineController.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

$controller = new MedicineController();
$success = $controller->destroy($id);
echo json_encode(['success' => $success]); 