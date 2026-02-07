<?php
header("Content-Type: application/json");

if(
    !isset($_POST['project_id']) ||
    !isset($_POST['speed']) ||
    !isset($_POST['pitch'])
){
    echo json_encode(["success"=>false,"error"=>"Missing parameters"]);
    exit;
}

$projectId = $_POST['project_id'];
$speed = floatval($_POST['speed']);
$pitch = floatval($_POST['pitch']);

$projectFile = __DIR__ . "/../../projects/$projectId.json";

if(!file_exists($projectFile)){
    echo json_encode(["success"=>false,"error"=>"Project not found"]);
    exit;
}

$data = json_decode(file_get_contents($projectFile), true);

$data["voice_tuning"] = [
    "speed"=>$speed,
    "pitch"=>$pitch
];

file_put_contents($projectFile, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(["success"=>true]);
