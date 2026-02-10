<?php
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$port = 3307; // your MySQL port
$user = "root";
$pass = "";
$dbname = "ai_dub";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "details" => $e->getMessage()
    ]);
    exit;
}

// Make PDO globally available
$GLOBALS['pdo'] = $pdo;

