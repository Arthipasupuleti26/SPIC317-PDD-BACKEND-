<?php
header("Content-Type: application/json");

// Correct session save path (3 folders up from /api/auth/)
$sessionPath = realpath(__DIR__ . "/../../..") . "/sessions";

if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}

ini_set('session.save_path', $sessionPath);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', false);

session_start();

echo json_encode([
    "session_save_path" => $sessionPath,
    "received_COOKIE" => $_COOKIE,
    "PHP_session_id()" => session_id(),
    "session_data" => $_SESSION
], JSON_PRETTY_PRINT);
