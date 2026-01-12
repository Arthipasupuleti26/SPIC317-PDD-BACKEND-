<?php
require __DIR__ . '/../../db.php';
session_start();

header("Content-Type: application/json");

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// Validate input
if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "email & password required"]);
    exit;
}

// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = $GLOBALS['pdo'];

    // Insert user
    $stmt = $pdo->prepare(
        "INSERT INTO users (full_name, email, password)
         VALUES (:name, :email, :password)"
    );

    $stmt->execute([
        ":name" => $name,
        ":email" => $email,
        ":password" => $hash
    ]);

    $user_id = $pdo->lastInsertId();

    // Create session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $email;

    echo json_encode([
        "success" => true,
        "user_id" => $user_id,
        "message" => "Registered and logged in"
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Registration failed",
        "details" => $e->getMessage()
    ]);
}

exit;
