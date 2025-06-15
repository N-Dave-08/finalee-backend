<?php
session_name('finalee_session');
session_start();

function require_role($role) {
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
    if (!isset($_SESSION['user'])) {
        header('Location: index.html');
        exit();
    }
} 