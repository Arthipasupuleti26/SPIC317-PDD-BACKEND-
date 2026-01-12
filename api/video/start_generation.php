<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// --------------------------------------------------
// DB CONNECTION
// --------------------------------------------------
require __DIR__ . '/../../db.php';

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection not found"]);
    exit;
}

// --------------------------------------------------
// HELPER: GET BEARER TOKEN
// --------------------------------------------------
function getBearerToken() {
    $headers = getallheaders();

    if (isset($headers['Authorization']) &&
        preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $m)) {
        return $m[1];
    }

    if (isset($headers['authorization']) &&
        preg_match('/Bearer\s(\S+)/', $headers['authorization'], $m)) {
        return $m[1];
    }

    return null;
}

// --------------------------------------------------
// ONLY POST
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

// --------------------------------------------------
// TOKEN VALIDATION
// --------------------------------------------------
$token = getBearerToken();
if (!$token) {
    http_response_code(401);
    echo json_encode(["error" => "No token"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE token=? LIMIT 1");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

$user_id = (int)$user['id'];

// --------------------------------------------------
// READ INPUT (DEFINE project_id FIRST ✅)
// --------------------------------------------------
$input = json_decode(file_get_contents("php://input"), true);
$project_id = intval($input['project_id'] ?? 0);

if ($project_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "project_id required"]);
    exit;
}

// --------------------------------------------------
// VERIFY PROJECT OWNERSHIP
// --------------------------------------------------


// --------------------------------------------------
// UPDATE STATUS
// --------------------------------------------------
$pdo->prepare(
    "UPDATE projects SET status='processing', progress=5 WHERE id=?"
)->execute([$project_id]);

// --------------------------------------------------
// START BACKGROUND WORKER (WINDOWS)
// --------------------------------------------------
$phpPath = "C:\\xampp\\php\\php.exe";
$worker  = __DIR__ . "/worker/generate_voice.php";

$cmd = "\"$phpPath\" \"$worker\" \"$project_id\" > NUL 2>&1";
pclose(popen("start /B " . $cmd, "r"));

// --------------------------------------------------
// RESPONSE
// --------------------------------------------------
echo json_encode([
    "success"    => true,
    "message"    => "Processing started",
    "project_id" => $project_id
]);
exit;
