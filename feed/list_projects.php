<?php
// api/feed/list_projects.php
// Lists all projects for the currently authenticated user (JSON output).

// 0) Load DB connection
require __DIR__ . '/../db.php';

// 1) Start or resume session (must be done before reading $_SESSION)
session_start();

// 2) Ensure response will be JSON
header('Content-Type: application/json; charset=utf-8');

// 3) Authentication check: require user_id in session
if (!isset($_SESSION['user_id']) || !is_int($_SESSION['user_id']) && !ctype_digit((string)$_SESSION['user_id'])) {
    // 401 Unauthorized if not logged in
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

// Normalize user_id as integer
$userId = (int) $_SESSION['user_id'];

// 4) Prepare SQL statement to avoid SQL injection
$sql = "SELECT id, title, original_path, translated_path, translate_direction, status, created_at
        FROM projects
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    // Prepare failed
    http_response_code(500);
    echo json_encode(["error" => "Database query preparation failed"]);
    exit;
}

// 5) Bind parameter and execute
$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Database query execution failed"]);
    exit;
}

// 6) Fetch result set
$res = $stmt->get_result();
$rows = [];

// 7) Iterate rows and push to array
while ($r = $res->fetch_assoc()) {
    // Optionally cast types, format dates here
    $rows[] = $r;
}

// 8) Close statement (optional but good practice)
$stmt->close();

// 9) Return JSON
http_response_code(200);
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
exit;
?>
