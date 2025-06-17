<?php
session_name('finalee_session');
session_start();
require_once '../../app/models/UserModel.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$userId = $_SESSION['user']['id'];
$current = isset($_POST['current_password']) ? $_POST['current_password'] : '';
$new = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if (!$current || !$new || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if ($new !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
    exit;
}
if (strlen($new) < 6) {
    echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
    exit;
}

$userModel = new UserModel();
$user = $userModel->findUserById($userId);
if (!$user || !password_verify($current, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

$hashed = password_hash($new, PASSWORD_DEFAULT);
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$stmt = $conn->prepare('UPDATE user SET password = ? WHERE id = ?');
$stmt->bind_param('si', $hashed, $userId);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
} 