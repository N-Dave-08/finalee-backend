<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    public function login($username, $password) {
        $userModel = new UserModel();
        $user = $userModel->findUser($username);
        if ($user) {
            if ($password === $user['password']) { 
                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'role' => $user['role']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid password'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }
    }
} 