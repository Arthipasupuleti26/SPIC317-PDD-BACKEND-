<?php
header("Content-Type: application/json");
require "../../db.php";

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Required values
$project_id = $data["project_id"] ?? null;
$voice_name = $data["voice"] ?? null;
$voice_style = $data["style"] ?? null;

// Optional values (fallback to DB defaults)
$emotion_happiness = $data["emotion_happiness"] ?? 50;
$emotion_sadness   = $data["emotion_sadness"]   ?? 0;
$emotion_anger     = $data["emotion_anger"]     ?? 0;
$emotion_neutral   = $data["emotion_neutral"]   ?? 0;
$speed             = $data["speed"]             ?? 50;
$pitch             = $data["pitch"]             ?? 50;
$subtitle_enabled  = $data["subtitle_enabled"]  ?? 1;
$sync_offset       = $data["sync_offset"]       ?? 0;

if (!$project_id || !$voice_name || !$voice_style) {
    http_response_code(400);
    echo json_encode(["error" => "project_id, voice, style are required"]);
    exit;
}

// Check if exists
$check = $pdo->prepare("SELECT id FROM video_settings WHERE project_id = ?");
$check->execute([$project_id]);

if ($check->rowCount() > 0) {
    // UPDATE
    $stmt = $pdo->prepare("
        UPDATE video_settings SET
            voice_name = ?,
            voice_style = ?,
            emotion_happiness = ?,
            emotion_sadness = ?,
            emotion_anger = ?,
            emotion_neutral = ?,
            speed = ?,
            pitch = ?,
            subtitle_enabled = ?,
            sync_offset = ?,
            updated_at = NOW()
        WHERE project_id = ?
    ");

    $success = $stmt->execute([
        $voice_name, $voice_style,
        $emotion_happiness, $emotion_sadness,
        $emotion_anger, $emotion_neutral,
        $speed, $pitch, $subtitle_enabled, $sync_offset,
        $project_id
    ]);

} else {
    // INSERT
    $stmt = $pdo->prepare("
        INSERT INTO video_settings (
            project_id, voice_name, voice_style,
            emotion_happiness, emotion_sadness, emotion_anger, emotion_neutral,
            speed, pitch, subtitle_enabled, sync_offset, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $success = $stmt->execute([
        $project_id, $voice_name, $voice_style,
        $emotion_happiness, $emotion_sadness, $emotion_anger, $emotion_neutral,
        $speed, $pitch, $subtitle_enabled, $sync_offset
    ]);
}

echo json_encode([
    "success" => $success,
    "message" => $check->rowCount() > 0 ? "Video settings updated" : "Video settings saved"
]);
exit;
?>
