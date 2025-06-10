<?php
// public/login.php (MVC entry point for login)
session_start();
require_once __DIR__ . '/../app/controllers/AuthController.php';

header('Content-Type: application/json');

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Username and password required']);
    exit();
}

$auth = new AuthController();
$response = $auth->login($username, $password);
if ($response['success']) {
    // Store user info in session
    $_SESSION['user'] = [
        'id' => $response['id'],
        'username' => $response['username'],
        'role' => $response['role']
    ];
}
echo json_encode($response); 