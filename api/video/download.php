<?php
// api/video/download.php

require __DIR__ . '/../auth/auth_token.php';
require __DIR__ . '/../../db.php';

$headers = getallheaders();

$authHeader =
    $headers['Authorization']
    ?? $headers['authorization']
    ?? $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? null;

if (!$authHeader) {
    http_response_code(401);
    echo json_encode(["error" => "No Authorization header"]);
    exit;
}

if (strpos($authHeader, 'Bearer ') !== 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token format"]);
    exit;
}

$token = trim(str_replace("Bearer ", "", $authHeader));  // FIXED

if (!$AUTH_USER || !isset($AUTH_USER['id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or expired token"]);
    exit;
}

$user_id = intval($AUTH_USER['id']);

$project_id = intval($_GET['project_id'] ?? 0);
if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "project_id required"]);
    exit;
}

$stmt = $conn->prepare("SELECT translated_path FROM projects WHERE id=? AND user_id=? LIMIT 1");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Project not found"]);
    exit;
}

$row = $res->fetch_assoc();
$translatedPath = $row['translated_path'];

$file = realpath(__DIR__ . '/../../uploads/translated/' . $translatedPath);

if (!$file || !file_exists($file)) {
    http_response_code(404);
    echo json_encode(["error" => "File missing on server"]);
    exit;
}

while (ob_get_level()) ob_end_clean();

$basename = basename($file);

header('Content-Description: File Transfer');
header('Content-Type: video/mp4');
header('Content-Disposition: attachment; filename="' . $basename . '"');
header('Content-Length: ' . filesize($file));
header("Cache-Control: public");

readfile($file);
exit;
?>
