<?php
// api/app/key_differentiators.php

header("Content-Type: application/json");

// session_start(); // not needed, but safe if you want same pattern

echo json_encode([
    "key_features" => [
        "Emotion-aware voice generation",
        "Telugu → English video voice translation",
        "High-quality AI voice output",
        "Smart audio chunk processing",
        "Fast cloud-based conversion",
        "User-friendly project dashboard"
    ]
]);
?>
