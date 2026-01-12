<?php
header("Content-Type: application/json");
require __DIR__ . "/log_helper.php";

write_log("Test API log entry created.");
write_video_error("Test video error log entry created.");

echo json_encode([
    "status" => "log test successful"
]);
?>
