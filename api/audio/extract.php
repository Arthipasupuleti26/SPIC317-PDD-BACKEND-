<?php
header("Content-Type: application/json");

ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

if (empty($_POST['project_id']) || empty($_POST['video_path'])) {
    echo json_encode([
        "success" => false,
        "error" => "Missing parameters"
    ]);
    exit;
}

$projectId = basename($_POST['project_id']); // safety
$relativeVideoPath = $_POST['video_path'];

// ✅ DO NOT USE realpath()
$videoPath = __DIR__ . "/../../" . $relativeVideoPath;

// Normalize path (Windows safe)
$videoPath = str_replace(["\\", "//"], "/", $videoPath);

if (!file_exists($videoPath)) {
    echo json_encode([
        "success" => false,
        "error" => "Video file not found",
        "expected_path" => $videoPath
    ]);
    exit;
}

$outputDir = __DIR__ . "/../../outputs/audio";
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$audioPath = $outputDir . "/" . $projectId . ".mp3";

// ✅ Full FFmpeg command
$cmd = "ffmpeg -y -i \"$videoPath\" -vn -acodec mp3 \"$audioPath\" 2>&1";

// Run FFmpeg
exec($cmd, $output, $returnCode);

if ($returnCode !== 0 || !file_exists($audioPath)) {
    echo json_encode([
        "success" => false,
        "error" => "Audio extraction failed",
        "ffmpeg_log" => $output
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "audio_path" => "outputs/audio/$projectId.mp3"
]);
