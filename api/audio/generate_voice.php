<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('max_execution_time', 300);

if (
    !isset($_POST['project_id']) ||
    !isset($_POST['voice']) ||
    !isset($_POST['text']) ||
    !isset($_POST['style'])   // ⭐ ADD THIS LINE
) {
    echo json_encode([
        "success" => false,
        "error" => "Missing parameters"
    ]);
    exit;
}

$projectId = $_POST['project_id'];
$text  = $_POST['text'];
$voice = $_POST['voice'];
$style = $_POST['style'];   // ⭐ RECEIVE STYLE (future use)

$cmd = "chcp 65001 >nul && python ../../python/voice_generator.py "
     . escapeshellarg($text) . " "
     . escapeshellarg($voice) . " 2>&1";

exec($cmd, $output, $code);

if ($code !== 0) {
    echo json_encode([
        "success" => false,
        "error" => "Voice generation failed",
        "debug" => $output
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "audio" => "outputs/voice/output.mp3",
    "voice" => $voice,
    "style" => $style    // optional return
]);
