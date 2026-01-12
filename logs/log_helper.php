<?php

function write_log($msg) {
    $file = __DIR__ . "/api_log.txt";
    $date = date("Y-m-d H:i:s");
    file_put_contents($file, "[$date] $msg\n", FILE_APPEND);
}

function write_video_error($msg) {
    $file = __DIR__ . "/video_errors.txt";
    $date = date("Y-m-d H:i:s");
    file_put_contents($file, "[$date] $msg\n", FILE_APPEND);
}
?>
