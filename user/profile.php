<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");

// ------------------------------------------------------------
// DB CONNECTION (PDO)
// ------------------------------------------------------------
require __DIR__ . '/../db.php';
$pdo = $GLOBALS['pdo'] ?? null;

if (!$pdo) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection not available"]);
    exit;
}

// ------------------------------------------------------------
// ONLY POST
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// ------------------------------------------------------------
// READ AUTHORIZATION HEADER
// ------------------------------------------------------------
$headers = function_exists('getallheaders') ? getallheaders() : [];
$authHeader =
    $headers['Authorization']
    ?? $headers['authorization']
    ?? $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? '';

$authHeader = trim(preg_replace('/\s+/', ' ', $authHeader));

if (!preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["error" => "No token received"]);
    exit;
}

$token = trim($matches[1]);

// ------------------------------------------------------------
// VALIDATE TOKEN (PDO)
// ------------------------------------------------------------
$stmt = $pdo->prepare(
    "SELECT id, full_name, email
     FROM users
     WHERE token = :token
     LIMIT 1"
);

$stmt->execute([
    ":token" => $token
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or expired token"]);
    exit;
}

// ------------------------------------------------------------
// SUCCESS RESPONSE
// ------------------------------------------------------------
echo json_encode([
    "status" => "success",
    "message" => "Profile verified",
    "user" => $user
]);

exit;
