<?php

require_once __DIR__ . '/../../app/controllers/AuthController.php';

header('Content-Type: application/json');

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : null;
$role = isset($_POST['role']) ? $_POST['role'] : 'user';

$first_name = isset($_POST['firstName']) ? $_POST['firstName'] : '';
$middle_name = isset($_POST['middleName']) ? $_POST['middleName'] : '';
$last_name = isset($_POST['lastName']) ? $_POST['lastName'] : '';
$contact_num = isset($_POST['contactNumber']) ? $_POST['contactNumber'] : '';

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Username and password required']);
    exit();
}

$auth = new AuthController();
$response = $auth->register($username, $password, $email, $role, $first_name, $middle_name, $last_name, $contact_num);
echo json_encode($response); 