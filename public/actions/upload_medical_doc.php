<?php
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

function redirect_with_error($type, $extra = '') {
    header('Location: ../admin/medical-request.php?error=' . urlencode($type . ($extra ? (':' . $extra) : '')));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_FILES['medical_doc'])) {
    $request_id = intval($_POST['request_id']);
    $file = $_FILES['medical_doc'];
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Upload error: ' . $file['error']]);
            exit();
        }
        redirect_with_error('upload_error', $file['error']);
    }
    if (!in_array($file['type'], $allowed_types)) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
            exit();
        }
        redirect_with_error('type', $file['type']);
    }
    if ($file['size'] > $max_size) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'File too large.']);
            exit();
        }
        redirect_with_error('size', $file['size']);
    }

    // Ensure upload directory exists
    $upload_dir = dirname(__DIR__, 2) . '/uploads/medical_docs/';
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Failed to create upload directory.']);
                exit();
            }
            redirect_with_error('mkdir_failed');
        }
    }

    // Generate unique file name
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'meddoc_' . $request_id . '_' . time() . '.' . $ext;
    $destination = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $conn = get_db_connection();
        $stmt = $conn->prepare('UPDATE medical_documents_requests SET file_path = ?, status = ? WHERE id = ?');
        $status = 'For Pick Up';
        $stmt->bind_param('ssi', $new_filename, $status, $request_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => true, 'file_path' => $new_filename]);
            exit();
        }
        header('Location: ../admin/medical-request.php?success=upload');
        exit();
    } else {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
            exit();
        }
        redirect_with_error('move_failed');
    }
} else {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit();
    }
    redirect_with_error('invalid_request');
} 