<?php
header("Content-Type: application/json");

/* ============================================================
   CORS CONFIG
   ============================================================ */
$allowedOrigins = [
    "http://localhost",
    "http://localhost:5173"
];

$origin = $_SERVER["HTTP_ORIGIN"] ?? "";
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/* ============================================================
   DB CONNECTION (PDO)
   ============================================================ */
require __DIR__ . '/../../db.php';
$pdo = $GLOBALS['pdo'] ?? null;

if (!$pdo) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

/* ============================================================
   SESSION CONFIG (FIXED)
   ============================================================ */
$sessionPath = __DIR__ . "/../../sessions";
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}

session_save_path($sessionPath);
session_name("PHPSESSID");

ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', false);

session_start();

/* ============================================================
   ONLY POST ALLOWED
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST allowed"]);
    exit;
}

/* ============================================================
   READ JSON INPUT
   ============================================================ */
$input = json_decode(file_get_contents("php://input"), true);

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password required"]);
    exit;
}

/* ============================================================
   FETCH USER
   ============================================================ */
$stmt = $pdo->prepare("
    SELECT id, name, email, password
    FROM users
    WHERE email = :email
    LIMIT 1
");
$stmt->execute([
    ":email" => $email
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid email"]);
    exit;
}

/* ============================================================
   VERIFY PASSWORD
   ============================================================ */
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid password"]);
    exit;
}

/* ============================================================
   GENERATE TOKEN
   ============================================================ */
$token = bin2hex(random_bytes(32));

$update = $pdo->prepare("
    UPDATE users SET token = :token WHERE id = :id
");
$update->execute([
    ":token" => $token,
    ":id" => $user['id']
]);

/* ============================================================
   SAVE SESSION
   ============================================================ */
$_SESSION['user_id'] = (string)$user['id'];
$_SESSION['name']    = $user['name'];
$_SESSION['email']   = $user['email'];

session_write_close();

/* ============================================================
   SUCCESS RESPONSE
   ============================================================ */
echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "session_id" => session_id(),
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "token" => $token
    ]
]);

exit;
