<?php
// api/auth/auth_token.php

header("Content-Type: application/json");
require_once __DIR__ . '/../../db.php';

// Get PDO
$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection not available"]);
    exit;
}

// ------------------------------------------------------------
// READ AUTHORIZATION HEADER SAFELY
// ------------------------------------------------------------
$headers = function_exists('getallheaders') ? getallheaders() : [];

$authHeader =
    $headers['Authorization']
    ?? $headers['authorization']
    ?? $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? null;

if (!$authHeader || !preg_match('/Bearer\s+(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["error" => "No token provided"]);
    exit;
}

$token = $matches[1];

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
// AUTHENTICATED USER (EXPOSE AS GLOBAL)
// ------------------------------------------------------------
$AUTH_USER = $user;

// If this file is included, do NOT echo anything
// The calling API will use $AUTH_USER
