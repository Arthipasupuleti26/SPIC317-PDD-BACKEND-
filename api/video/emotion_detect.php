<?php
// --------------------------------------------------
// IMPORTANT: prevent timeout for long python runs
// --------------------------------------------------
set_time_limit(0);
ini_set('max_execution_time', 0);

header("Content-Type: application/json; charset=utf-8");
error_reporting(E_ALL);
ini_set("display_errors", 1);

// --------------------------------------------------
// 1. READ project_id
// --------------------------------------------------
$project_id = intval($_POST["project_id"] ?? 0);

if ($project_id <= 0) {
    echo json_encode(["error" => "project_id missing or invalid"]);
    exit;
}

// --------------------------------------------------
// 2. DB CONNECTION
// --------------------------------------------------
require __DIR__ . "/../../db.php";

if (!isset($pdo)) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// --------------------------------------------------
// 3. FETCH VIDEO + AUDIO PATH FROM PROJECT
// --------------------------------------------------
$stmt = $pdo->prepare("
    SELECT original_video_path, extracted_audio_path
    FROM projects
    WHERE id = ?
    LIMIT 1
");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    echo json_encode(["error" => "Project not found"]);
    exit;
}

// Resolve absolute paths
$videoPath = realpath(__DIR__ . "/../../" . $project["original_video_path"]);
$audioPath = realpath(__DIR__ . "/../../" . $project["extracted_audio_path"]);

if (!$videoPath || !file_exists($videoPath)) {
    echo json_encode(["error" => "Video file missing"]);
    exit;
}

if (!$audioPath || !file_exists($audioPath)) {
    echo json_encode(["error" => "Audio file missing"]);
    exit;
}

// --------------------------------------------------
// 4. RUN PYTHON EMOTION SCRIPT (TOP-2 JSON OUTPUT)
// --------------------------------------------------
$python  = "C:/Users/HP INDIA/myenv/Scripts/python.exe";
$script  = realpath(__DIR__ . "/../../ai/detect_emotions.py");
$logFile = __DIR__ . "/emotion_debug.log";

$cmd = "\"$python\" \"$script\" \"$videoPath\" \"$audioPath\" 2> \"$logFile\"";
$output = shell_exec($cmd);
$log    = file_get_contents($logFile);

// --------------------------------------------------
// 5. VALIDATE PYTHON OUTPUT
// --------------------------------------------------
if (!$output) {
    echo json_encode([
        "error" => "Python execution failed",
        "log"   => $log
    ]);
    exit;
}

// --------------------------------------------------
// 6. PARSE PYTHON JSON OUTPUT (TOP-2)
// --------------------------------------------------
$output = trim($output);
$data = json_decode($output, true);

if (!$data || !isset($data["audio_top2"], $data["face_top2"], $data["final_emotion"])) {
    echo json_encode([
        "error" => "Invalid python response format",
        "raw_output" => $output
    ]);
    exit;
}

$audio_top2   = $data["audio_top2"];
$face_top2    = $data["face_top2"];
$final_emotion = $data["final_emotion"];

// --------------------------------------------------
// 7. SAVE FINAL EMOTION TO DATABASE
// --------------------------------------------------
$stmt = $pdo->prepare("
    UPDATE projects
    SET emotion_detected = ?, status = 'emotion_detected'
    WHERE id = ?
");
$stmt->execute([$final_emotion, $project_id]);

// --------------------------------------------------
// 8. RETURN RESPONSE
// --------------------------------------------------
echo json_encode([
    "success"        => true,
    "project_id"     => $project_id,
    "audio_top2"     => $audio_top2,
    "face_top2"      => $face_top2,
    "final_emotion"  => $final_emotion
]);
exit;
