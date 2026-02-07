<?php
header("Content-Type: application/json");
ini_set('max_execution_time', 300);

/* ===================================================
   REAL FOLDER PATHS (ABSOLUTE)
   =================================================== */

$videoFolder = realpath(__DIR__ . "/../../uploads/videos");
$audioFile   = realpath(__DIR__ . "/../audio/outputs/voice/output.mp3");
$outputDir   = __DIR__ . "/outputs/final";
$outputFile  = $outputDir . "/dubbed_video.mp4";

/* create output folder */
if(!file_exists($outputDir)){
    mkdir($outputDir,0777,true);
}

/* ===================================================
   GET LATEST UPLOADED VIDEO (timestamp file)
   =================================================== */
$videos = glob($videoFolder . "/*.mp4");

if(!$videos){
    echo json_encode([
        "success"=>false,
        "error"=>"No uploaded video found"
    ]);
    exit;
}

/* get latest uploaded video */
usort($videos, function($a,$b){
    return filemtime($b) - filemtime($a);
});
$videoFile = $videos[0];

/* ===================================================
   CHECK FILES EXIST
   =================================================== */
if(!file_exists($videoFile)){
    echo json_encode(["success"=>false,"error"=>"Video missing"]);
    exit;
}

if(!file_exists($audioFile)){
    echo json_encode(["success"=>false,"error"=>"Audio missing"]);
    exit;
}

/* ===================================================
   FFMPEG MERGE (REMOVE TELUGU AUDIO + ADD ENGLISH)
   =================================================== */

$ffmpeg = "C:\\ffmpeg-8.0.1-essentials_build\\bin\\ffmpeg.exe";

$cmd = "$ffmpeg -y -i \"$videoFile\" -i \"$audioFile\" -map 0:v -map 1:a -c:v copy -shortest \"$outputFile\" 2>&1";

exec($cmd,$log,$code);

if($code !== 0){
    echo json_encode([
        "success"=>false,
        "error"=>"FFmpeg failed",
        "debug"=>$log
    ]);
    exit;
}

/* ===================================================
   SUCCESS RESPONSE
   =================================================== */

echo json_encode([
    "success"=>true,
    "stream"=>"https://1j7cp4fh-80.inc1.devtunnels.ms/ai_dub/api/video/outputs/final/dubbed_video.mp4",
    "download"=>"https://1j7cp4fh-80.inc1.devtunnels.ms/ai_dub/api/video/download_video.php"
]);

