<?php

function require_role($role) {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.html');
        exit();
    }
    if ($_SESSION['user']['role'] !== $role) {
        if ($_SESSION['user']['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: home.php');
        }
        exit();
    }
}

function require_login() {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.html');
        exit();
    }
} 