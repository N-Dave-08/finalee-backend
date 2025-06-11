<?php

header('Content-Type: application/json');
require_once __DIR__ . '/../../app/models/UserModel.php';
require_once __DIR__ . '/../../app/helpers/auth.php';
require_role('admin');

$userModel = new UserModel();
$conn = (new ReflectionClass($userModel))->getProperty('conn');
$conn->setAccessible(true);
$db = $conn->getValue($userModel);

$stmt = $db->prepare('SELECT id, first_name, last_name, gender, email, contact_num FROM user WHERE role = "user"');
$stmt->execute();
$result = $stmt->get_result();
$patients = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($patients); 