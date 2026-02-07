<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('max_execution_time', 300);

if (!isset($_POST['project_id']) || !isset($_POST['audio_path'])) {
    echo json_encode(["success"=>false,"error"=>"Missing parameters"], JSON_UNESCAPED_UNICODE);
    exit;
}

$audioPath = realpath(__DIR__ . "/../../" . $_POST['audio_path']);

if (!$audioPath || !file_exists($audioPath)) {
    echo json_encode([
        "success"=>false,
        "error"=>"Audio file not found",
        "path"=>$audioPath
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

# ⭐ FORCE WINDOWS UTF-8
$python = "C:\Users\HP INDIA\AppData\Local\Programs\Python\Python310\python.exe";
$script = realpath(__DIR__ . "/../../python/transcribe.py");

$cmd = "chcp 65001 >nul && \"$python\" \"$script\" \"$audioPath\"";

$output = shell_exec($cmd . " 2>&1");

if (!$output) {
    echo json_encode(["success"=>false,"error"=>"Python transcription failed"], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    "success" => true,
    "text" => trim($output)
], JSON_UNESCAPED_UNICODE);
