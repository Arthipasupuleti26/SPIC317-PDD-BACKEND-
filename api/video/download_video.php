<?php
// ⭐ THIS FILE SERVES VIDEO FOR STREAM + DOWNLOAD

$videoPath = __DIR__ . "/outputs/final/dubbed_video.mp4";

if (!file_exists($videoPath)) {
    http_response_code(404);
    echo "Video not found";
    exit;
}

$fileSize = filesize($videoPath);
$fileName = "AI_Dubbed_Video.mp4";

// ⭐ IMPORTANT HEADERS FOR ANDROID DOWNLOAD MANAGER
header("Content-Description: File Transfer");
header("Content-Type: video/mp4");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Length: " . $fileSize);
header("Accept-Ranges: bytes");
header("Cache-Control: no-cache");
header("Pragma: public");

// ⭐ OUTPUT FILE STREAM
readfile($videoPath);
exit;
