<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP is working<br>";

// FULL PATH (important)
$videoPath = "C:/xampp/htdocs/ai_dub/uploads/videos/f1.mp4";

$cmd = 'python ../python/translate.py "'.$videoPath.'" 2>&1';

echo "Running command:<br>$cmd<br><br>";

$output = shell_exec($cmd);

echo "<pre>";
print_r($output);
echo "</pre>";

?>
