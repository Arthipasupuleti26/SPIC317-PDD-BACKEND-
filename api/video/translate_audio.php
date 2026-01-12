<?php
header("Content-Type: application/json; charset=utf-8");
require __DIR__ . '/../../db.php';

if (!isset($_POST['project_id'])) {
    echo json_encode(["error" => "project_id missing"]);
    exit;
}
$project_id = intval($_POST['project_id']);

// GET PROJECT
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$proj = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proj) {
    echo json_encode(["error" => "Project not found"]);
    exit;
              
}

$audioPath = __DIR__ . "/../../" . $proj['extracted_audio_path'];

if (!file_exists($audioPath)) {
    echo json_encode(["error" => "Extracted audio not found", "audio" => $audioPath]);
    exit;
}

// ------------------------------
// CALL OPENAI WHISPER API
// ------------------------------
$apiKey = "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";

$curl = curl_init();

$data = [
    "file" => new CURLFile($audioPath, "audio/wav", basename($audioPath)),
    "model" => "gpt-4o-transcribe", 
    "response_format" => "json",
    "language" => "te",
    "translate" => "true"
]
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.openai.com/v1/audio/transcriptions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey"
    ]   
]);

$response = curl_exec($curl);

if (!$response) {
    echo json_encode([
        "error" => "CURL failed",
        "details" => curl_error($curl)
    ]);
    exit;
}

curl_close($curl);

$json = json_decode($response, true);

if (!$json || !isset($json['text'])) {
    echo json_encode([
        "error" => "API returned empty response",
        "raw_response" => $response
    ]);
    exit;
}

$english = $json['text'];

// SAVE TRANSLATED TEXT
$up = $pdo->prepare("UPDATE projects SET translated_text=? WHERE id=?");
$up->execute([$english, $project_id]);

echo json_encode([
    "success" => true,
    "message" => "Telugu → English translation completed",
    "english_text" => $english
]);
?>
