<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../../db.php";

// Check input
if (!isset($_GET["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "user_id required"
    ]);
    exit;
}

$user_id = $_GET["user_id"];

try {
    $stmt = $pdo->prepare(
        "SELECT message, is_read 
         FROM notifications 
         WHERE user_id = ?"
    );

    $stmt->execute([$user_id]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "notifications" => $data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
