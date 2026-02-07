<?php

function add_history(
    PDO $pdo,
    int $project_id,
    string $status,
    string $message,
    array $context = []
) {
    try {
        $ctx = empty($context) ? null : json_encode($context, JSON_UNESCAPED_UNICODE);
        $stmt = $pdo->prepare("
            INSERT INTO project_history (project_id, status, message, context, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$project_id, $status, $message, $ctx]);
        return true;

    } catch (Exception $e) {
        error_log("History insert failed: " . $e->getMessage());
        return false;
    }
}
?>
