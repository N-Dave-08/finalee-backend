<?php
require_once '../app/models/UserModel.php';

function render_form($token, $error = '', $success = '') {
    echo '<!DOCTYPE html><html lang="en"><head>';
    echo '<meta charset="UTF-8" />';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    echo '<title>Reset Password</title>';
    echo '<link rel="stylesheet" href="assets/css/reset-password.css">';
    echo '<link rel="icon" href="assets/images/newimus.png" sizes="32x32" type="image/png">';
    echo '</head><body>';
    echo '<div class="reset-card">';
    echo '<img src="assets/images/newimus.png" alt="Logo" />';
    echo '<h2>Reset Your Password</h2>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="token" value="' . htmlspecialchars($token) . '">';
    echo '<label for="new_password">New Password</label>';
    echo '<input type="password" id="new_password" name="password" required placeholder="Enter new password" />';
    echo '<label for="confirm_password">Confirm Password</label>';
    echo '<input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password" />';
    echo '<button type="submit">Reset Password</button>';
    echo '<div class="msg">';
    if ($error) echo '<div class="error">' . htmlspecialchars($error) . '</div>';
    if ($success) echo '<div class="success">' . htmlspecialchars($success) . '</div>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</body></html>';
}

$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    if (!$token) {
        echo 'Invalid or missing token.';
        exit;
    }
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $stmt = $conn->prepare('SELECT * FROM user WHERE reset_token = ? AND reset_token_expiry > NOW()');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    if ($user) {
        render_form($token);
    } else {
        echo 'Invalid or expired token.';
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    if (!$token || !$password || !$confirm_password) {
        render_form($token, 'All fields are required.');
        exit;
    }
    if ($password !== $confirm_password) {
        render_form($token, 'Passwords do not match.');
        exit;
    }
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $stmt = $conn->prepare('SELECT * FROM user WHERE reset_token = ? AND reset_token_expiry > NOW()');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if ($user) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare('UPDATE user SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?');
        $stmt2->bind_param('si', $hashed, $user['id']);
        $stmt2->execute();
        $stmt2->close();
        render_form('', '', 'Password has been reset. You may now <a href="index.html">login</a>.');
    } else {
        render_form($token, 'Invalid or expired token.');
    }
    $conn->close();
    exit;
}

echo 'Invalid request.'; 