<?php
require_once '../app/models/UserModel.php';

function render_form($token, $error = '') {
    echo '<!DOCTYPE html><html><head><title>Reset Password</title></head><body>';
    if ($error) {
        echo '<div style="color:red;">' . htmlspecialchars($error) . '</div>';
    }
    echo '<form method="POST">';
    echo '<input type="hidden" name="token" value="' . htmlspecialchars($token) . '">';
    echo '<label>New Password: <input type="password" name="password" required></label><br><br>';
    echo '<button type="submit">Reset Password</button>';
    echo '</form>';
    echo '</body></html>';
}

$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    if (!$token) {
        echo 'Invalid or missing token.';
        exit;
    }
    $user = $userModel->findUserByEmail(''); // placeholder
    // Find user by token
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
    if (!$token || !$password) {
        render_form($token, 'Missing token or password.');
        exit;
    }
    // Find user by token
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $stmt = $conn->prepare('SELECT * FROM user WHERE reset_token = ? AND reset_token_expiry > NOW()');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if ($user) {
        // Update password and clear token
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare('UPDATE user SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?');
        $stmt2->bind_param('si', $hashed, $user['id']);
        $stmt2->execute();
        $stmt2->close();
        echo '<div style="color:green;">Password has been reset. You may now <a href="index.html">login</a>.</div>';
    } else {
        render_form($token, 'Invalid or expired token.');
    }
    $conn->close();
    exit;
}

echo 'Invalid request.'; 