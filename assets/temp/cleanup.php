<?php
header("Content-Type: application/json");

// Get the temp folder path safely
$folder = __DIR__;

// If folder does NOT exist → return clean JSON error
if (!is_dir($folder)) {
    echo json_encode([
        "status" => "error",
        "message" => "Temp folder not found"
    ]);
    exit;
}

// Get all files inside the folder
$files = glob($folder . "/*");

$deleted = 0;

foreach ($files as $file) {
    // ✅ Only delete normal files
    // ✅ Do NOT delete index.php
    // ✅ Do NOT delete this cleanup.php itself
    if (
        is_file($file) &&
        basename($file) !== "index.php" &&
        basename($file) !== "cleanup.php"
    ) {
        if (unlink($file)) {
            $deleted++;
        }
    }
}

// Final clean JSON response
echo json_encode([
    "status" => "success",
    "deleted_files" => $deleted
]);
?>
