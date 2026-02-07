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

$project_id = $data["project_id"] ?? null;

// Validate
if (!$project_id) {
    http_response_code(400);
    echo json_encode(["error" => "project_id is required"]);
    exit;
}

// Use PDO (NOT $conn)
$stmt = $pdo->prepare("SELECT status FROM projects WHERE id = ?");
$stmt->execute([$project_id]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    http_response_code(404);
    echo json_encode(["error" => "Project not found"]);
    exit;
}

// Success response
echo json_encode([
    "success" => true,
    "project_id" => $project_id,
    "status" => $result["status"]
]);

exit;
