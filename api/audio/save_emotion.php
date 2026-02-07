<?php
header("Content-Type: application/json; charset=UTF-8");

if (
    !isset($_POST['project_id']) ||
    !isset($_POST['happiness']) ||
    !isset($_POST['excitement']) ||
    !isset($_POST['sadness'])
) {
    echo json_encode(["success"=>false,"error"=>"Missing parameters"]);
    exit;
}

$projectId = $_POST['project_id'];
$happiness = intval($_POST['happiness']);
$excitement = intval($_POST['excitement']);
$sadness = intval($_POST['sadness']);

# ⭐ project storage folder
$projectFolder = __DIR__ . "/../../projects";
if (!file_exists($projectFolder)) {
    mkdir($projectFolder, 0777, true);
}

$projectFile = "$projectFolder/$projectId.json";

# If project file doesn't exist → create new
if (!file_exists($projectFile)) {
    $data = [];
} else {
    $data = json_decode(file_get_contents($projectFile), true);
}

# ⭐ SAVE emotion values
$data["happiness"] = $happiness;
$data["excitement"] = $excitement;
$data["sadness"] = $sadness;

file_put_contents($projectFile, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(["success"=>true]);
