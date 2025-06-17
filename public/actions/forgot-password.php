<?php
require_once '../../vendor/autoload.php';
require_once '../../app/models/UserModel.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email is required.']);
    exit;
}

$userModel = new UserModel();
$user = $userModel->findUserByEmail($email);

if ($user) {
    // Generate secure token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', time() + 3600); // 1 hour from now
    $userModel->setResetToken($user['id'], $token, $expiry);

    // Send reset email using PHPMailer
    $resetLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
        "://" . $_SERVER['HTTP_HOST'] .
        dirname(dirname($_SERVER['REQUEST_URI'])) . "/reset-password.php?token=$token";
    $subject = 'Password Reset Request';
    $body = "Hello,\n\nWe received a request to reset your password. Click the link below to reset it:\n$resetLink\n\nIf you did not request this, you can ignore this email.";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ghadharden@gmail.com';
        $mail->Password = 'yautwmbowjogrbzg';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('ghadharden@gmail.com', 'phpmailer');
        $mail->addAddress($email);
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Reset link has been sent.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to send reset link.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email not found.']);
    exit;
} 