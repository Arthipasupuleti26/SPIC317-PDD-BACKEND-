<?php
// api/app/limitations.php

header("Content-Type: application/json");

// session_start(); // optional

echo json_encode([
    "limitations" => [
        "Long videos take more time to process",
        "Emotion detection accuracy may vary",
        "Background noise can affect translation",
        "Internet required for video upload/processing"
    ]
]);
?>
