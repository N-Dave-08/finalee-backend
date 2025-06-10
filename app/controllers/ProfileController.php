<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/EmergencyContactModel.php';

class ProfileController {
    public function updateProfileAndContact($user_id, $userData, $emergencyData) {
        $userModel = new UserModel();
        $emergencyModel = new EmergencyContactModel();
        $userResult = $userModel->updateProfile(
            $user_id,
            $userData['first_name'],
            $userData['last_name'],
            $userData['middle_name'],
            $userData['date_of_birth'],
            $userData['place_of_birth'],
            $userData['gender'],
            $userData['email'],
            $userData['contact_num']
        );
        if (!$userResult['success']) {
            return ['success' => false, 'message' => $userResult['message']];
        }
        $emergencyResult = $emergencyModel->upsert(
            $user_id,
            $emergencyData['first_name'],
            $emergencyData['last_name'],
            $emergencyData['middle_name'],
            $emergencyData['relationship'],
            $emergencyData['contact_num'],
            $emergencyData['email']
        );
        if (!$emergencyResult['success']) {
            return ['success' => false, 'message' => $emergencyResult['message']];
        }
        return ['success' => true];
    }
    public function getProfileAndContact($user_id) {
        $userModel = new UserModel();
        $emergencyModel = new EmergencyContactModel();
        $user = $userModel->findUserById($user_id);
        $emergency = $emergencyModel->getByUserId($user_id);
        return ['user' => $user, 'emergency' => $emergency];
    }
} 