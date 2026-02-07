<?php
require __DIR__ . '/../../db.php';
session_start();

header("Content-Type: application/json");

// ------------------------------------------------------------
// ONLY POST
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// ------------------------------------------------------------
// READ JSON
// ------------------------------------------------------------
$input = json_decode(file_get_contents("php://input"), true);

$name     = trim($input['name'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// ------------------------------------------------------------
// VALIDATION
// ------------------------------------------------------------
if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Name, Email and Password required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid email format"]);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["error" => "Password must be at least 6 characters"]);
    exit;
}

$pdo = $GLOBALS['pdo'];

// ------------------------------------------------------------
// CHECK IF EMAIL EXISTS
// ------------------------------------------------------------
$check = $pdo->prepare(
    "SELECT id FROM users WHERE email = :email LIMIT 1"
);

$check->execute([
    ":email" => $email
]);

if ($check->fetch()) {
    http_response_code(409);
    echo json_encode(["error" => "Email already registered"]);
    exit;
}

// ------------------------------------------------------------
// CREATE USER + TOKEN
// ------------------------------------------------------------
$hash  = password_hash($password, PASSWORD_BCRYPT);
$token = bin2hex(random_bytes(32)); // secure token

$stmt = $pdo->prepare(
    "INSERT INTO users (name, email, password, token)
     VALUES (:name, :email, :password, :token)"
);

$stmt->execute([
    ":name"     => $name,
    ":email"    => $email,
    ":password" => $hash,
    ":token"    => $token
]);

$user_id = $pdo->lastInsertId();

// ------------------------------------------------------------
// CREATE SESSION (optional)
// ------------------------------------------------------------
$_SESSION['user_id'] = (string)$user_id;
$_SESSION['email']   = $email;

// ------------------------------------------------------------
// RESPONSE (SEND TOKEN)
// ------------------------------------------------------------
echo json_encode([
    "status"  => "success",
    "message" => "Registered successfully",
    "user" => [
        "id"    => $user_id,
        "name"  => $name,
        "email" => $email
    ],
    "token" => $token
]);

exit;
