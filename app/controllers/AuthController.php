<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    public function login($username, $password) {
        $userModel = new UserModel();
        $user = $userModel->findUser($username);
        if ($user) {
            if (password_verify($password, $user['password'])) {
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

    public function register($username, $password, $email = null, $role = 'user') {
        $userModel = new UserModel();
        // Check if user already exists
        $existing = $userModel->findUser($username);
        if ($existing) {
            return [
                'success' => false,
                'message' => 'Username already taken'
            ];
        }
        // Create user
        $result = $userModel->createUser($username, $password, $email, $role);
        if ($result['success']) {
            return [
                'success' => true,
                'message' => 'Registration successful'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Registration failed: ' . ($result['message'] ?? 'Unknown error')
            ];
        }
    }
} 