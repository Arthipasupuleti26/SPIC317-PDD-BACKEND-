<?php
header("Content-Type: application/json");
require "../../db.php";

if (!isset($_GET['project_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "project_id required"
    ]);
    exit;
}

$project_id = intval($_GET['project_id']);

// delete project
$stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
$deleted = $stmt->execute([$project_id]);

// also delete settings (optional but recommended)
$stmt2 = $pdo->prepare("DELETE FROM video_settings WHERE project_id = ?");
$stmt2->execute([$project_id]);

echo json_encode([
    "success" => $deleted,
    "project_id" => $project_id,
    "message" => $deleted ? "Project deleted successfully" : "Delete failed"
]);
exit;
?>
