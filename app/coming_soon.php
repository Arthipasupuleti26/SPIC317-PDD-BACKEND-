<?php
// api/app/coming_soon.php

header("Content-Type: application/json");

// session_start(); // optional

echo json_encode([
    "coming_soon" => [
        "English → Telugu voice conversion",
        "Voice cloning for creators",
        "Music background removal",
        "Automatic subtitles (SRT)",
        "AI-powered script rewriting",
        "Cloud storage for video history"
    ]
]);
?>
