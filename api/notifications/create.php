<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../db.php";

if(
    !isset($_POST["user_id"]) ||
    !isset($_POST["message"])
){
    echo json_encode([
        "success"=>false,
        "error"=>"Missing params"
    ]);
    exit;
}

$user_id = $_POST["user_id"];
$message = $_POST["message"];

try{
    $stmt = $pdo->prepare(
        "INSERT INTO notifications(user_id,message,is_read)
         VALUES(?,?,0)"
    );
    $stmt->execute([$user_id,$message]);

    echo json_encode(["success"=>true]);

}catch(PDOException $e){
    echo json_encode([
        "success"=>false,
        "error"=>$e->getMessage()
    ]);
}
?>
