<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

// --------------------------------------------------
// PHP SETTINGS CHECK
// --------------------------------------------------
$response['file_uploads'] = ini_get('file_uploads');
$response['upload_max_filesize'] = ini_get('upload_max_filesize');
$response['post_max_size'] = ini_get('post_max_size');
$response['memory_limit'] = ini_get('memory_limit');
$response['upload_tmp_dir'] = ini_get('upload_tmp_dir');
$response['sys_temp_dir'] = sys_get_temp_dir();

// --------------------------------------------------
// TEMP DIR CHECK
// --------------------------------------------------
$tmp = sys_get_temp_dir();
$response['temp_dir_exists'] = is_dir($tmp);
$response['temp_dir_writable'] = is_writable($tmp);

// --------------------------------------------------
// FILE CHECK
// --------------------------------------------------
if (!isset($_FILES['video'])) {
    $response['file_received'] = false;
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

$video = $_FILES['video'];
$response['file_received'] = true;
$response['upload_error_code'] = $video['error'];
$response['upload_error_meaning'] = upload_error_to_string($video['error']);
$response['original_name'] = $video['name'];
$response['tmp_name'] = $video['tmp_name'];
$response['tmp_writable'] = is_writable(dirname($video['tmp_name']));

// --------------------------------------------------
// HELPER
// --------------------------------------------------
function upload_error_to_string($code) {
    $errors = [
        UPLOAD_ERR_OK => "OK",
        UPLOAD_ERR_INI_SIZE => "Exceeded upload_max_filesize",
        UPLOAD_ERR_FORM_SIZE => "Exceeded MAX_FILE_SIZE",
        UPLOAD_ERR_PARTIAL => "Partial upload",
        UPLOAD_ERR_NO_FILE => "No file uploaded",
        UPLOAD_ERR_NO_TMP_DIR => "Missing temp directory",
        UPLOAD_ERR_CANT_WRITE => "Cannot write to disk",
        UPLOAD_ERR_EXTENSION => "Stopped by extension"
    ];
    return $errors[$code] ?? "Unknown error";
}

echo json_encode($response, JSON_PRETTY_PRINT);
exit;
