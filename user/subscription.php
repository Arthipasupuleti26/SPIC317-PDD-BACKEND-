<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// --------------------------------------------------
// TOKEN AUTH (loads $AUTH_USER + PDO)
// --------------------------------------------------
require_once __DIR__ . '/../api/auth/auth_token.php';

$pdo = $GLOBALS['pdo'] ?? null;
$user_id = intval($AUTH_USER['id'] ?? 0);

if (!$pdo || $user_id <= 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or missing token"]);
    exit;
}

// --------------------------------------------------
// FETCH SUBSCRIPTION DETAILS
// --------------------------------------------------
$stmt = $pdo->prepare(
    "SELECT subscription_type, subscription_expires
     FROM users
     WHERE id = :id"
);

$stmt->execute([
    ":id" => $user_id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
    exit;
}

// --------------------------------------------------
// RESPONSE
// --------------------------------------------------
echo json_encode([
    "subscription" => [
        "type" => $user['subscription_type'],          // free / premium
        "expires" => $user['subscription_expires'],    // null OR date
        "user_id" => $user_id
    ]
]);

exit;
