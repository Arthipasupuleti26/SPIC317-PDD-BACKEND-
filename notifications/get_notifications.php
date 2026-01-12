<?php
// notifications/get_notifications.php

// 1) Start the session to read logged-in user
session_start();

// 2) Always return JSON
header('Content-Type: application/json');

// 3) Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

// 4) (Optional) Get the user_id, if you want user-specific notifications later
$user_id = $_SESSION['user_id'];

// 5) For now, return static notifications (you can change to DB-driven later)
$notifications = [
    [
        "id"         => 1,
        "title"      => "Your translation is ready",
        "message"    => "One of your Telugu → English projects is completed.",
        "created_at" => date("Y-m-d H:i:s")
    ],
    [
        "id"         => 2,
        "title"      => "New feature available",
        "message"    => "Emotion-aware dubbing presets have been improved.",
        "created_at" => date("Y-m-d H:i:s", strtotime("-10 minutes"))
    ]
];

// 6) Send as JSON array
echo json_encode($notifications);
?>
