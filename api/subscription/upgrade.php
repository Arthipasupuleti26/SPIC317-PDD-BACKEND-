<?php
header("Content-Type: application/json");

require_once __DIR__ . "/../../db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data["plan"], $data["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE users SET subscription_plan = ? WHERE id = ?"
    );

    $stmt->execute([
        $data["plan"],
        $data["user_id"]
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Plan updated"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
