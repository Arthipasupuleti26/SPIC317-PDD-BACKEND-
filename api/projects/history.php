<?php
header("Content-Type: application/json");
require "../../db.php";

if (!isset($_GET["project_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "project_id required"
    ]);
    exit;
}

$project_id = $_GET["project_id"];

$stmt = $pdo->prepare(
    "SELECT * FROM project_history 
     WHERE project_id = ?
     ORDER BY created_at DESC"
);

$stmt->execute([$project_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "count" => count($history),
    "history" => $history
]);
?>
