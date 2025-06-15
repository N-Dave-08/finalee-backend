<?php
session_name('finalee_session');
session_start();
session_unset();
// Remove the session cookie for all possible paths and domains
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // Remove for current path
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    // Remove for root path
    setcookie(session_name(), '', time() - 42000,
        '/', $params["domain"],
        $params["secure"], $params["httponly"]
    );
    // Remove for /public path
    setcookie(session_name(), '', time() - 42000,
        '/public', $params["domain"],
        $params["secure"], $params["httponly"]
    );
    // Remove for /admin path
    setcookie(session_name(), '', time() - 42000,
        '/admin', $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit();
?> 