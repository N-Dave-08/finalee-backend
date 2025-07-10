<?php
require_once __DIR__ . '/../../../app/controllers/MedicineController.php';
header('Content-Type: application/json');

$controller = new MedicineController();
$medicines = $controller->index();
echo json_encode($medicines); 