<?php
header("Content-Type: application/json");

// Include token auth middleware
require __DIR__ . '/../auth/auth_token.php';

// $AUTH_USER contains logged-in user
$user_id = $AUTH_USER['id'];

// Read JSON input
$project_id = intval($_POST["project_id"] ?? 0);
$title      = trim($_POST["title"] ?? "");


// Validate inputs
if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "project_id required"]);
    exit;
}

// Update DB
require __DIR__ . '/../../db.php';

$stmt = $conn->prepare("
    UPDATE projects SET title=?, updated_at=NOW()
    WHERE id=? AND user_id=?
");
$stmt->bind_param("sii", $title, $project_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Project updated"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => $stmt->error]);
}
?>
