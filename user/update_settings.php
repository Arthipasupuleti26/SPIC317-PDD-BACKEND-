<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ------------------------------------------
// 1. INCLUDE TOKEN CHECK (this also loads db.php)
// ------------------------------------------
require __DIR__ . '/../api/auth/auth_token.php';

// Get PDO
$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection not available"]);
    exit;
}

// Logged-in user id
$user_id = intval($AUTH_USER['id'] ?? 0);
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or missing token"]);
    exit;
}

// ------------------------------------------
// 2. POST ONLY
// ------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// ------------------------------------------
// 3. READ JSON INPUT
// ------------------------------------------
$input = json_decode(file_get_contents("php://input"), true);

$emotion     = $input['emotion'] ?? null;
$language    = $input['language'] ?? null;
$voice_style = $input['voice_style'] ?? null;

if (!$emotion && !$language && !$voice_style) {
    echo json_encode(["error" => "Nothing to update"]);
    exit;
}

// ------------------------------------------
// 4. UPDATE USER SETTINGS (PDO)
// ------------------------------------------
$stmt = $pdo->prepare(
    "UPDATE users
     SET emotion = :emotion,
         preferred_language = :language,
         voice_style = :voice_style
     WHERE id = :id"
);

$success = $stmt->execute([
    ":emotion" => $emotion,
    ":language" => $language,
    ":voice_style" => $voice_style,
    ":id" => $user_id
]);

if ($success) {
    echo json_encode([
        "success" => true,
        "message" => "Settings updated"
    ]);
} else {
    echo json_encode([
        "error" => "Update failed"
    ]);
}

exit;
