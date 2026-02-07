<?php
header("Content-Type: application/json");

if (!isset($_FILES['video'])) {
    echo json_encode(["success"=>false,"error"=>"No video"]);
    exit;
}

$uploadDir = realpath(__DIR__ . "/../../uploads/videos");
$filename = time() . ".mp4";
$inputPath = $uploadDir . "/" . $filename;

move_uploaded_file($_FILES['video']['tmp_name'], $inputPath);

// CALL PYTHON (BLOCKING)
$cmd = "python C:/xampp/htdocs/ai_dub/python/video_processor.py \"$inputPath\" 2>&1";
$output = shell_exec($cmd);

// EXPECT FINAL FILE
$outputFile = str_replace(".mp4", "_final.mp4", $inputPath);

if (!file_exists($outputFile)) {
    echo json_encode([
        "success"=>false,
        "error"=>"Processing failed",
        "log"=>$output
    ]);
    exit;
}

echo json_encode([
    "success"=>true,
    "output"=>basename($outputFile)
]);
