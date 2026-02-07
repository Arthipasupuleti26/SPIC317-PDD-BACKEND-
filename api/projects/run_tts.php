<?php
require "../../db.php";
require __DIR__ . "/helpers.php";


$projectId = $_POST["project_id"];
$text = $_POST["text"];
$voice = $_POST["voice"];
$style = $_POST["style"] ?? null;
$rate  = $_POST["rate"] ?? "0%";
$pitch = $_POST["pitch"] ?? "0Hz";

add_history($pdo, $projectId, "tts_started", "Voice synthesis started");

$payload = json_encode([
    "project_id" => $projectId,
    "text" => $text,
    "voice" => $voice,
    "style" => $style,
    "rate" => $rate,
    "pitch" => $pitch
]);

$cmd = "python C:/xampp/htdocs/ai_dub/python/tts.py";
$result = shell_exec("echo " . escapeshellarg($payload) . " | " . $cmd);

var_dump($result);

