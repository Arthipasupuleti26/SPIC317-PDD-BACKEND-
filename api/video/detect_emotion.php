<?php
header("Content-Type: application/json");

$project_id = $_POST["project_id"] ?? null;

if (!isset($_FILES["video_file"])) {
    echo json_encode(["error" => "Video file missing"]);
    exit;
}

$videoTmp = realpath($_FILES["video_file"]["tmp_name"]);

$python = "C:/Users/HP INDIA/myenv/Scripts/python.exe";
$script = realpath(__DIR__ . "/../../ai/detect_emotions.py");

$cmd = "\"$python\" \"$script\" \"$videoTmp\" 2>&1";
$output = trim(shell_exec($cmd));

if (!$output) {
    echo json_encode(["error" => "No output from python", "cmd" => $cmd]);
    exit;
}

include __DIR__ . "/../../db.php";
$stmt = $conn->prepare("UPDATE projects SET emotion_detected=? WHERE id=?");
$stmt->bind_param("si", $output, $project_id);
$stmt->execute();

echo json_encode([
    "status" => "success",
    "emotion_detected" => $output
]);
?>
