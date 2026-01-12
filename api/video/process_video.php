<?php
session_start();
header("Content-Type: application/json");

// TEMP SESSION FIX (REMOVE LATER)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

require __DIR__ . '/../../db.php';

// ✅ ENSURE PDO EXISTS
if (!isset($pdo)) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// ---------------------------------
// READ INPUT
// ---------------------------------
$input = json_decode(file_get_contents("php://input"), true);
$project_id = intval($input['project_id'] ?? 0);

if (!$project_id) {
    echo json_encode(["error" => "project_id required"]);
    exit;
}

// ---------------------------------
// FETCH PROJECT (PDO FIX)
// ---------------------------------
$stmt = $pdo->prepare("
    SELECT original_video_path, extracted_audio_path
    FROM projects
    WHERE id = ?
");
$stmt->execute([$project_id]);

$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    echo json_encode(["error" => "Project not found"]);
    exit;
}

// Absolute paths
$videoFile = realpath(__DIR__ . "/../../" . $project['original_video_path']);
$audioFile = realpath(__DIR__ . "/../../" . $project['extracted_audio_path']);

if (!$videoFile || !$audioFile) {
    echo json_encode(["error" => "Video or audio file missing"]);
    exit;
}

$translatedDir = __DIR__ . "/../../uploads/translated/";
if (!is_dir($translatedDir)) mkdir($translatedDir, 0777, true);

// ---------------------------------
// 1️⃣ EMOTION DETECTION
// ---------------------------------
$emotionCmd = "python \"" . __DIR__ . "/../../ai/detect_emotions.py\" \"$videoFile\" 2>&1";
$emotion = trim(shell_exec($emotionCmd));
if (!$emotion) $emotion = "neutral";

// ---------------------------------
// 2️⃣ WHISPER STT (AUDIO)
// ---------------------------------
$sttCmd = "python \"" . __DIR__ . "/../../ai/transcribe_translate.py\" \"$audioFile\" 2>&1";
$sttJson = shell_exec($sttCmd);
$sttData = json_decode($sttJson, true);

$englishText = trim($sttData['english'] ?? '');

if (!$englishText) {
    echo json_encode([
        "error" => "Whisper STT failed",
        "raw_output" => $sttJson
    ]);
    exit;
}


// ---------------------------------
// 3️⃣ TTS
// ---------------------------------
$audioName = "tts_" . time() . ".wav";
$audioOut  = $translatedDir . $audioName;

$ttsCmd = "python \"" . __DIR__ . "/../../ai/tts_generate.py\" "
        . escapeshellarg($englishText) . " "
        . escapeshellarg($emotion) . " "
        . escapeshellarg($audioOut) . " 2>&1";

exec($ttsCmd, $ttsLog);

if (!file_exists($audioOut)) {
    echo json_encode(["error" => "TTS audio not created", "tts_log" => $ttsLog]);
    exit;
}

// ---------------------------------
// 4️⃣ MERGE VIDEO
// ---------------------------------
$videoName = "final_" . time() . ".mp4";
$videoOut  = $translatedDir . $videoName;

exec(
    "ffmpeg -y -i \"$videoFile\" -i \"$audioOut\" -map 0:v -map 1:a -c:v copy -shortest \"$videoOut\" 2>&1",
    $ffmpegLog
);

if (!file_exists($videoOut)) {
    echo json_encode(["error" => "FFmpeg merge failed", "log" => $ffmpegLog]);
    exit;
}

// ---------------------------------
// UPDATE DB (PDO)
// ---------------------------------
$stmt = $pdo->prepare("
    UPDATE projects
    SET translated_path = ?,
        emotion_detected = ?,
        status = 'completed',
        updated_at = NOW()
    WHERE id = ?
");
$stmt->execute([$videoName, $emotion, $project_id]);

// ---------------------------------
// SUCCESS RESPONSE
// ---------------------------------
echo json_encode([
    "success" => true,
    "project_id" => $project_id,
    "emotion_detected" => $emotion,
    "english_text" => $englishText,
    "final_video" => $videoName,
    "download_url" =>
        "http://localhost/voice_converter_backend/uploads/translated/" . $videoName
]);
exit;
