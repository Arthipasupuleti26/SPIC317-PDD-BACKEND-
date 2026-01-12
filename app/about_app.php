<?php
// api/app/about_app.php

// Always return JSON
header("Content-Type: application/json");

// You don't really need session here, but if you want to keep same pattern:
// session_start();

// Static app info
echo json_encode([
    "title" => "AI Content: Indian Telugu - English Emotion-Aware Voice Generator",
    "version" => "1.0.0",
    "developer" => "Your App Team",
    "description" => "This application converts Telugu videos into English voice videos with emotion mapping. Future versions will support English → Telugu and advanced voice cloning."
]);
?>
