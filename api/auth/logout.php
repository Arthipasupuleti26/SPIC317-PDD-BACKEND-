<?php
// api/auth/logout.php
session_start();

header("Content-Type: application/json");

// Allow only POST (optional but recommended)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// Destroy session
session_unset();
session_destroy();

// JSON response
echo json_encode([
    "success" => true,
    "message" => "Logged out"
]);
