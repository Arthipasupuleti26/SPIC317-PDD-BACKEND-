<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('max_execution_time', 300);

if (!isset($_POST['project_id']) || !isset($_POST['telugu_text'])) {
    echo json_encode(["success"=>false,"error"=>"Missing parameters"], JSON_UNESCAPED_UNICODE);
    exit;
}

$teluguText = $_POST['telugu_text'];

// ⭐ FORCE UTF-8 CMD (same fix as transcription)
$cmd = "chcp 65001 >nul && python ../../python/translate.py " . escapeshellarg($teluguText);

$output = shell_exec($cmd);

if (!$output) {
    echo json_encode(["success"=>false,"error"=>"Python translation failed"], JSON_UNESCAPED_UNICODE);
    exit;
}

// ⭐ CLEAN OUTPUT (remove hidden spaces/newlines)
$output = trim($output);

echo $output;
