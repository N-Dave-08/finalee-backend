<?php
require_once __DIR__ . '/../../../app/controllers/MedicineController.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}
$controller = new MedicineController();
$medicine = $controller->show($id);
echo json_encode($medicine); 