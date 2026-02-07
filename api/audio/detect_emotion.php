<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('max_execution_time', 300);

if (!isset($_POST['project_id']) || !isset($_POST['english_text'])) {
    echo json_encode(["success"=>false,"error"=>"Missing parameters"]);
    exit;
}

$englishText = $_POST['english_text'];

$cmd = "chcp 65001 >nul && python ../../python/emotion.py " . escapeshellarg($englishText);
$output = shell_exec($cmd);

if (!$output) {
    echo json_encode(["success"=>false,"error"=>"Python emotion failed"]);
    exit;
}

echo trim($output);
