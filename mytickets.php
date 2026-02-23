<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

$data = json_decode(file_get_contents("php://input"));
$username = $data->username;

if(!$username){
    echo json_encode(["status"=>"error","message"=>"Пайдаланушы жоқ"]);
    exit;
}

// Кесте атын 'tickets' деп өзгерттік (скриншот бойынша)
$stmt = $conn->prepare("SELECT id, event_title, event_location, price FROM tickets WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

$tickets = [];
while($row = $res->fetch_assoc()){
    $tickets[] = [
        "id"=>$row['id'],
        "title"=>$row['event_title'], // Базада: event_title
        "price"=>$row['price'],
        "loc"=>$row['event_location'] // Базада: event_location
    ];
}

echo json_encode(["status"=>"success","tickets"=>$tickets]);
?>