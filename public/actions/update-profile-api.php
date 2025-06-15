<?php
session_name('finalee_session');
session_start();
file_put_contents(__DIR__ . '/session_debug.log', date('c') . ' ' . print_r($_SESSION, true), FILE_APPEND);
require_once __DIR__ . '/../../app/controllers/ProfileController.php';
// var_dump($_SESSION);
if (!isset($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}
$user_id = $_SESSION['user']['id'];
$controller = new ProfileController();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $controller->getProfileAndContact($user_id);
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    $userData = $input['user'] ?? [];
    $emergencyData = $input['emergency'] ?? [];
    $result = $controller->updateProfileAndContact($user_id, $userData, $emergencyData);
    echo json_encode($result);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Invalid request method']); 