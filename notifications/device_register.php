<?php
// api/notifications/device_register.php

// 1) Include DB connection
require __DIR__ . '/../db.php';

// 2) Start session to read logged-in user
session_start();

// 3) Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    header('Content-Type: application/json');
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

// 4) Read device_token from POST (form-data or x-www-form-urlencoded)
$device_token = $_POST['device_token'] ?? '';

// Simple validation
if (!$device_token) {
    http_response_code(400); // Bad request
    header('Content-Type: application/json');
    echo json_encode(["error" => "device_token required"]);
    exit;
}

// 5) Prepare SQL: insert or update device token for this user
$sql = "INSERT INTO devices (user_id, device_token)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE device_token = VALUES(device_token)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["error" => "DB prepare failed", "details" => $conn->error]);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt->bind_param("is", $user_id, $device_token);
$ok = $stmt->execute();

if (!$ok) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["error" => "DB execute failed", "details" => $stmt->error]);
    exit;
}

// 6) Success response
header('Content-Type: application/json');
echo json_encode(["success" => true, "user_id" => $user_id, "device_token" => $device_token]);
?>
