<?php
header("Content-Type: application/json");

// ALLOW BOTH FRONTEND & DIRECT BROWSER CALL
$allowedOrigins = [
    "http://localhost",
    "http://localhost:5173"
];

$origin = $_SERVER["HTTP_ORIGIN"] ?? "";
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Access-Control-Allow-Credentials", "true");

// SET SESSION PATH (make sure folder exists: /sessions)
session_save_path(__DIR__ . "/../../sessions");

// Correct cookie name
session_name("PHPSESSID");

// Same settings used during login
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', false);

// Start session
session_start();

echo json_encode([
    "cookies_received" => $_COOKIE,
    "session_id" => session_id(),
    "session_data" => $_SESSION ?: null,
    "status" => isset($_SESSION['user_id']) ? "success" : "error",
    "message" => isset($_SESSION['user_id']) ? "Session Active" : "No Active Session"
]);
?>
