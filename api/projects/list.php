<?php
header("Content-Type: application/json");
require "../../db.php";

// Fetch projects (newest first)
$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
echo json_encode([
    "success" => true,
    "count" => count($projects),
    "projects" => $projects
], JSON_PRETTY_PRINT);

exit;
?>
