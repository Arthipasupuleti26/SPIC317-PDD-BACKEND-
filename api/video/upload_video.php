<?php
header("Content-Type: application/json; charset=utf-8");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// --------------------------------------------------
// DB CONNECTION
// --------------------------------------------------
require_once __DIR__ . "/../../db.php";

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection not found"]);
    exit;
}

// --------------------------------------------------
// HELPER FUNCTIONS
// --------------------------------------------------
function json_error($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(["error" => $msg]);
    exit;
}

function getBearerToken() {
    $headers = getallheaders();

    if (isset($headers['Authorization']) &&
        preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $m)) {
        return $m[1];
    }

    if (isset($headers['authorization']) &&
        preg_match('/Bearer\s(\S+)/', $headers['authorization'], $m)) {
        return $m[1];
    }

    return null;
}

// --------------------------------------------------
// ONLY POST
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error("Only POST allowed", 405);
}

// --------------------------------------------------
// TOKEN VALIDATION (BROWSER + API SUPPORT)
// --------------------------------------------------
$token = getBearerToken();

if ($token) {
    // API / Postman / Android flow
    $stmt = $pdo->prepare("SELECT id FROM users WHERE token=? LIMIT 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        json_error("Invalid token", 401);
    }

    $user_id = (int)$user['id'];
} else {
    // Browser test fallback
    // ⚠️ Make sure this user exists in DB
    $user_id = 1;
}




// --------------------------------------------------
// TEMP DIRECTORY CHECK
// --------------------------------------------------
$tmpDir = sys_get_temp_dir();
if (!is_dir($tmpDir) || !is_writable($tmpDir)) {
    json_error("PHP temp directory not writable: " . $tmpDir, 500);
}

// --------------------------------------------------
// FILE CHECK
// --------------------------------------------------
if (!isset($_FILES['video'])) {
    json_error("No video file uploaded");
}

$video = $_FILES['video'];

if ($video['error'] !== UPLOAD_ERR_OK) {
    json_error("Upload failed with error code " . $video['error']);
}

// --------------------------------------------------
// FILE SIZE VALIDATION ✅ ADDED
// --------------------------------------------------
$MAX_VIDEO_SIZE = 1024 * 1024 * 1024; // 1 GB

if ($video['size'] > $MAX_VIDEO_SIZE) {
    json_error("Video too large. Maximum allowed size is 1GB", 413);
}

// --------------------------------------------------
// FILE TYPE VALIDATION (OPTIONAL BUT RECOMMENDED)
// --------------------------------------------------
$ext = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));
$allowedExt = ['mp4', 'mkv', 'avi', 'mov', 'webm'];

if (!in_array($ext, $allowedExt)) {
    json_error("Invalid video format");
}

// --------------------------------------------------
// TITLE
// --------------------------------------------------
$title = $_POST['title'] ?? pathinfo($video['name'], PATHINFO_FILENAME);

// --------------------------------------------------
// CREATE UPLOAD DIRECTORIES
// --------------------------------------------------
$baseUploadDir = realpath(__DIR__ . "/../../uploads");
if (!$baseUploadDir) {
    json_error("Base upload directory not found", 500);
}

$videoDir = $baseUploadDir . "/original_videos";
$audioDir = $baseUploadDir . "/extracted_audio";

if (!is_dir($videoDir)) mkdir($videoDir, 0777, true);
if (!is_dir($audioDir)) mkdir($audioDir, 0777, true);

// --------------------------------------------------
// SAVE VIDEO
// --------------------------------------------------
$filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;

$videoPath = $videoDir . "/" . $filename;
$relativeVideo = "uploads/original_videos/" . $filename;

if (!move_uploaded_file($video['tmp_name'], $videoPath)) {
    json_error("Failed to save uploaded video file");
}

// --------------------------------------------------
// INSERT PROJECT
// --------------------------------------------------
$stmt = $pdo->prepare("
    INSERT INTO projects (
        user_id,
        title,
        original_video_path,
        status,
        progress,
        created_at
    ) VALUES (
        :user_id,
        :title,
        :path,
        'uploaded',
        0,
        NOW()
    )
");

$stmt->execute([
    ":user_id" => $user_id,
    ":title"   => $title,
    ":path"    => $relativeVideo
]);

$project_id = (int)$pdo->lastInsertId();

// --------------------------------------------------
// EXTRACT AUDIO USING FFMPEG
// --------------------------------------------------
$audioFile = $audioDir . "/audio_" . $project_id . ".wav";
$relativeAudio = "uploads/extracted_audio/audio_" . $project_id . ".wav";

$cmd = "ffmpeg -y -i " . escapeshellarg($videoPath) .
       " -vn -acodec pcm_s16le -ar 16000 -ac 1 " .
       escapeshellarg($audioFile) . " 2>&1";

exec($cmd, $out, $code);

if ($code !== 0 || !file_exists($audioFile)) {
    $pdo->prepare("UPDATE projects SET status='error_extract' WHERE id=?")
        ->execute([$project_id]);
    json_error("FFmpeg audio extraction failed", 500);
}

// --------------------------------------------------
// UPDATE AUDIO PATH
// --------------------------------------------------
$pdo->prepare("
    UPDATE projects SET extracted_audio_path=? WHERE id=?
")->execute([$relativeAudio, $project_id]);

// --------------------------------------------------
// SUCCESS RESPONSE
// --------------------------------------------------
echo json_encode([
    "success"    => true,
    "message"    => "Video uploaded & audio extracted",
    "project_id" => $project_id,
    "video"      => $relativeVideo,
    "audio"      => $relativeAudio
]);
exit;
