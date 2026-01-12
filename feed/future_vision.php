<?php
// api/feed/future_vision.php

header('Content-Type: application/json');

$data = [
    "title" => "Future Vision",
    "subtitle" => "Upcoming Features of Telugu–English Emotion-Aware Voice Generator",
    "features" => [
        "Real-time Telugu to English voice translation",
        "Emotion-based AI dubbing",
        "Automatic lip-sync for translated videos",
        "Multiple Indian language support",
        "Cloud-based fast rendering",
        "Team sharing and collaboration"
    ],
    "note" => "These features are planned for future versions."
];

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>
