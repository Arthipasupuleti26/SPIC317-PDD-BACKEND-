<?php
header("Content-Type: application/json");
require "../../db.php";

// -----------------------------------
// GET AUTH TOKEN (APACHE SAFE)
// -----------------------------------
$headers = function_exists('getallheaders') ? getallheaders() : [];

$token =
    $headers['Authorization']
    ?? $headers['authorization']
    ?? $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? '';

// Remove Bearer prefix
if (stripos($token, 'Bearer ') === 0) {
    $token = trim(substr($token, 7));
}

if (empty($token)) {
    http_response_code(401);
    echo json_encode([
        "error" => "Authorization token missing"
    ]);
    exit;
}

// -----------------------------------
// READ JSON BODY
// -----------------------------------
$data = json_decode(file_get_contents("php://input"), true);

// -----------------------------------
// VALIDATE NAME
// -----------------------------------
if (!isset($data['name']) || trim($data['name']) === '') {
    http_response_code(400);
    echo json_encode([
        "error" => "Name is required"
    ]);
    exit;
}

$name = trim($data['name']);

// -----------------------------------
// UPDATE NAME USING TOKEN
// -----------------------------------
$stmt = $pdo->prepare(
    "UPDATE users SET name = ? WHERE token = ?"
);

$stmt->execute([$name, $token]);

if ($stmt->rowCount() === 0) {
    http_response_code(401);
    echo json_encode([
        "error" => "Invalid token or no changes made"
    ]);
    exit;
}

// -----------------------------------
// SUCCESS RESPONSE
// -----------------------------------
echo json_encode([
    "status" => "success",
    "name" => $name
]);

exit;
