<?php
// -------------------------------------------------------
// Allow Browser, Postman, and CLI
// -------------------------------------------------------

if (php_sapi_name() === "cli") {
    if ($argc < 2) {
        echo "Missing project_id\n";
        exit;
    }
    $project_id = (int)$argv[1];
} else {
    $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
}

if (!$project_id) {
    echo json_encode(["error" => "project_id missing"]);
    exit;
}

// -------------------------------------------------------
// Load DB
// -------------------------------------------------------
require __DIR__ . '/../../../db.php';
$OPENAI_KEY ="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";

if (!isset($pdo)) {
    die(json_encode(["error" => "DB connection failed"]));
}

// -------------------------------------------------------
// Fetch project
// -------------------------------------------------------
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id=?");
$stmt->execute([$project_id]);
$proj = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proj) {
    echo json_encode(["error" => "Project not found"]);
    exit;
}

// -------------------------------------------------------
// Original video path
// -------------------------------------------------------
$orig = __DIR__ . '/../../../' . $proj['original_video_path'];

if (!file_exists($orig)) {
    echo json_encode(["error" => "Original video missing"]);
    exit;
}

// -------------------------------------------------------
// Update progress → 5%
// -------------------------------------------------------
$progress = $pdo->prepare("UPDATE projects SET progress=? WHERE id=?");
$progress->execute([5, $project_id]);

sleep(1);

// -------------------------------------------------------
// EMOTION + TEXT
// -------------------------------------------------------
$emotion = $proj['emotion_detected'] ?? 'neutral';
$text    = $proj['english_text'] ?? 'Hello, this is the generated English voice';

// -------------------------------------------------------
// Generate English Emotion-Aware Audio (Python TTS)
// -------------------------------------------------------
$audioDir = __DIR__ . '/../../../uploads/generated_audio/';
if (!is_dir($audioDir)) mkdir($audioDir, 0777, true);

$generatedAudio = $audioDir . "gen_" . $project_id . ".wav";

$python = "C:/Users/HP INDIA/myenv/Scripts/python.exe";
$ttsScript = __DIR__ . "/../../../ai/tts_generate.py";

$cmdTTS =
    "\"$python\" \"$ttsScript\" "
    . escapeshellarg($text) . " "
    . escapeshellarg($emotion) . " "
    . escapeshellarg($generatedAudio) . " "
    . escapeshellarg($OPENAI_KEY)
    . " 2>&1";


exec($cmdTTS, $ttsLog);

// ❌ STOP IF TTS FAILED (VERY IMPORTANT)
if (!file_exists($generatedAudio)) {
    echo json_encode([
        "success" => false,
        "error" => "TTS generation failed",
        "tts_log" => $ttsLog
    ]);
    exit;
}

// Update progress → 50%
$progress->execute([50, $project_id]);

// -------------------------------------------------------
// Merge video + generated audio
// -------------------------------------------------------
$videoDir = __DIR__ . '/../../../uploads/generated_video/';

if (!is_dir($videoDir)) mkdir($videoDir, 0777, true);

$generatedVideo = $videoDir . "final_" . $project_id . ".mp4";

$cmdMerge =
    "ffmpeg -y -i " . escapeshellarg($orig)
    . " -i " . escapeshellarg($generatedAudio)
    . " -map 0:v -map 1:a -c:v copy -shortest "
    . escapeshellarg($generatedVideo)
    . " 2>&1";

exec($cmdMerge, $mergeLog);

// -------------------------------------------------------
// Save result
// -------------------------------------------------------
$relVideo = "uploads/generated_video/" . basename($generatedVideo);

$done = $pdo->prepare("
    UPDATE projects 
    SET status='done', progress=100, generated_video_path=? 
    WHERE id=?
");
$done->execute([$relVideo, $project_id]);

// -------------------------------------------------------
// Final output
// -------------------------------------------------------
echo json_encode([
    "success" => true,
    "message" => "Emotion-aware generation completed",
    "emotion" => $emotion,
    "generated_video" => $relVideo,
    "tts_log" => $ttsLog,
    "ffmpeg_merge_log" => $mergeLog
]);
exit;
