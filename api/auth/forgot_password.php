<?php
header("Content-Type: application/json");

// ------------------------------------------------------------
// DB CONNECTION
// ------------------------------------------------------------
require __DIR__ . "/../../db.php";

// ------------------------------------------------------------
// ONLY POST
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// ------------------------------------------------------------
// READ JSON INPUT
// ------------------------------------------------------------
$input = json_decode(file_get_contents("php://input"), true);

$email            = trim($input['email'] ?? '');
$newPassword      = $input['new_password'] ?? '';
$confirmPassword  = $input['confirm_password'] ?? '';

// ------------------------------------------------------------
// VALIDATION
// ------------------------------------------------------------
if (!$email || !$newPassword || !$confirmPassword) {
    http_response_code(400);
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

if ($newPassword !== $confirmPassword) {
    http_response_code(400);
    echo json_encode(["error" => "Passwords do not match"]);
    exit;
}

if (strlen($newPassword) < 6) {
    http_response_code(400);
    echo json_encode(["error" => "Password must be at least 6 characters"]);
    exit;
}

// ------------------------------------------------------------
// CHECK USER EXISTS
// ------------------------------------------------------------
$stmt = $pdo->prepare(
    "SELECT id FROM users WHERE email = :email LIMIT 1"
);
$stmt->execute([
    ":email" => $email
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo json_encode(["error" => "Email not registered"]);
    exit;
}

// ------------------------------------------------------------
// UPDATE PASSWORD
// ------------------------------------------------------------
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

$update = $pdo->prepare(
    "UPDATE users 
     SET password = :password, token = NULL 
     WHERE email = :email"
);

$update->execute([
    ":password" => $hashedPassword,
    ":email"    => $email
]);

// ------------------------------------------------------------
// SUCCESS RESPONSE
// ------------------------------------------------------------
echo json_encode([
    "status"  => "success",
    "message" => "Password reset successful. Please login again."
]);

exit;
