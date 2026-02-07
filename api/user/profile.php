<?php
header("Content-Type: application/json");
require "../../db.php";

// ------------------------------------------------
// GET AUTH TOKEN (APACHE SAFE)
// ------------------------------------------------
$headers = function_exists('getallheaders') ? getallheaders() : [];

$token =
    $headers['Authorization']
    ?? $headers['authorization']
    ?? $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? '';

// ------------------------------------------------
// REMOVE BEARER PREFIX
// ------------------------------------------------
if (stripos($token, 'Bearer ') === 0) {
    $token = trim(substr($token, 7));
}

// ------------------------------------------------
// VALIDATE TOKEN
// ------------------------------------------------
if (empty($token)) {
    http_response_code(401);
    echo json_encode(["error" => "Token missing"]);
    exit;
}

// ------------------------------------------------
// FETCH USER USING TOKEN
// ------------------------------------------------
$stmt = $pdo->prepare(
    "SELECT id, name, email FROM users WHERE token = ? LIMIT 1"
);

$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// ------------------------------------------------
// SUCCESS RESPONSE
// ------------------------------------------------
echo json_encode([
    "status" => "success",
    "user" => $user
]);

exit;
