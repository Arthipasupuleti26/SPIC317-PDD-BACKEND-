<?php
// Load DB connection
require __DIR__ . '/../db.php';

header('Content-Type: application/json');

// Safety: check DB connection
if (!isset($conn) || $conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database not connected"
    ]);
    exit;
}

// ✅ Correct SQL using YOUR REAL COLUMNS
$sql = "SELECT 
            id,
            title,
            translated_path AS video_path,
            translate_direction,
            emotion_detected,
            status,
            created_at
        FROM projects
        WHERE status = 'completed'
        ORDER BY created_at DESC
        LIMIT 20";

$result = $conn->query($sql);

// If query fails, return SQL error
if (!$result) {
    echo json_encode([
        "status" => "error",
        "sql_error" => $conn->error
    ]);
    exit;
}

// Fetch rows
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

// Final JSON output
echo json_encode([
    "status" => "success",
    "trending" => $rows
]);
?>
