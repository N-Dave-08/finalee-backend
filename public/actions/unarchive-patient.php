<?php
require_once __DIR__ . '/../../app/helpers/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        require_once __DIR__ . '/../../app/models/UserModel.php';
        $userModel = new UserModel();
        $conn = (new ReflectionClass($userModel))->getProperty('conn');
        $conn->setAccessible(true);
        $db = $conn->getValue($userModel);

        // Start transaction
        $db->begin_transaction();
        try {
            // Copy from archived_users to user
            $copy = $db->prepare('INSERT INTO user (id, username, first_name, last_name, middle_name, date_of_birth, place_of_birth, gender, password, email, contact_num, role, created_at, reset_token, reset_token_expiry) SELECT id, username, first_name, last_name, middle_name, date_of_birth, place_of_birth, gender, password, email, contact_num, role, created_at, reset_token, reset_token_expiry FROM archived_users WHERE id = ?');
            $copy->bind_param('i', $id);
            $copySuccess = $copy->execute();
            $copy->close();

            if (!$copySuccess) throw new Exception('Copy failed');

            // Delete from archived_users
            $delete = $db->prepare('DELETE FROM archived_users WHERE id = ?');
            $delete->bind_param('i', $id);
            $deleteSuccess = $delete->execute();
            $delete->close();

            if (!$deleteSuccess) throw new Exception('Delete failed');

            $db->commit();
            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}
echo json_encode(['success' => false, 'error' => 'Invalid request']); 