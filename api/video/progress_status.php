<?php
header("Content-Type: application/json");

// DB
require __DIR__ . '/../../db.php';

// ----------------------------
// 1. READ TOKEN FROM HEADER
// ----------------------------
$headers = getallheaders();

$authHeader =
    $headers['Authorization']
    ?? $headers['authorization']
    ?? $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? '';

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["error" => "No token received"]);
    exit;
}

$token = $matches[1];

// ----------------------------
// 2. FIND USER BY TOKEN
// ----------------------------
$stmt = $conn->prepare("SELECT id FROM users WHERE token=? LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

$user = $res->fetch_assoc();
$user_id = (int)$user["id"];

// ----------------------------
// 3. GET PROJECT ID
// ----------------------------
$project_id = $_GET["project_id"] ?? null;

if (!$project_id) {
    echo json_encode(["error" => "project_id missing"]);
    exit;
}

// ----------------------------
// 4. GET PROJECT
// ----------------------------
$stmt2 = $conn->prepare("
    SELECT id, status, translated_path, emotion_detected
    FROM projects
    WHERE id = ? AND user_id = ?
");
$stmt2->bind_param("ii", $project_id, $user_id);
$stmt2->execute();
$result = $stmt2->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Project not found"]);
    exit;
}

$data = $result->fetch_assoc();

// ----------------------------
// 5. SEND OUTPUT
// ----------------------------
echo json_encode([
    "status" => "success",
    "project" => $data
]);
exit;
?>
