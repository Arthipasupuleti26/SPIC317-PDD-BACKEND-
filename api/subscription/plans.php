<?php
$data = [
    ["plan" => "Free", "price" => 0, "minutes" => 45],
    ["plan" => "Pro", "price" => 29, "minutes" => 300],
    ["plan" => "Enterprise", "price" => 99, "minutes" => 9999]
];

header('Content-Type: application/json');
echo json_encode($data);
?>
