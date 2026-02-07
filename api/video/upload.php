<?php
require __DIR__ . "/../../db.php";
require __DIR__ . "/../projects/helpers.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

$title   = $_POST["title"] ?? '';
$user_id = $_POST["user_id"] ?? '';

if (!$title || !$user_id || !isset($_FILES["video"])) {
    http_response_code(400);
    echo json_encode(["error" => "Title, user_id and video file required"]);
    exit;
}

$file = $_FILES["video"];
$uploadDir = __DIR__ . "/../../uploads/videos/";

if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$filename = time() . "_" . basename($file["name"]);
$path = "uploads/videos/" . $filename;
$fullPath = __DIR__ . "/../../" . $path;

if (!move_uploaded_file($file["tmp_name"], $fullPath)) {
    http_response_code(500);
    echo json_encode(["error" => "Video upload failed"]);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO projects (user_id, title, video_path, status)
    VALUES (?, ?, ?, 'uploaded')
");
$stmt->execute([$user_id, $title, $path]);

$project_id = $pdo->lastInsertId();

// log history
add_history($pdo, $project_id, "uploaded", "Video uploaded successfully");

echo json_encode([
    "success" => true,
    "project_id" => $project_id,
    "video_path" => $path
]);
exit;
?>
