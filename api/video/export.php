<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../projects/helpers.php';

$input = json_decode(file_get_contents("php://input"), true);
if (!$input) $input = $_POST;
if (!$input) $input = $_GET;

$projectId = $input['project_id'] ?? null;
$filename  = $input['filename'] ?? null;

if (!$projectId || !$filename) {
    echo json_encode(["success" => false, "message" => "project_id & filename required"]);
    exit;
}

$uploadDir = __DIR__ . '/../../uploads/output/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$downloadUrl = "uploads/output/" . $filename;

$stmt = $pdo->prepare("
    UPDATE projects 
    SET output_video_path = ?, status='completed'
    WHERE id=?
");
$stmt->execute([$downloadUrl, $projectId]);

// log history
add_history($pdo, $projectId, "completed", "Final dubbed video generated");

echo json_encode([
    "success" => true,
    "message" => "Output video saved",
    "project_id" => $projectId,
    "download_url" => $downloadUrl
]);
?>
