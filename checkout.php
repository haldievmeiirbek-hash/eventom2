
<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // локальда жұмыс үшін
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

$data = json_decode(file_get_contents("php://input"));
$username = $data->username;
$tickets = $data->tickets;

if(!$username || !$tickets){
    echo json_encode(["status"=>"error","message"=>"Деректер жетіспейді"]);
    exit;
}
foreach($tickets as $ticket){
    // 'tickets' кестесіне және дұрыс бағандарға жазу
    $stmt = $conn->prepare("INSERT INTO tickets (username, event_title, event_location, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $username, $ticket->title, $ticket->loc, $ticket->price);
    $stmt->execute();
}

echo json_encode(["status"=>"success","message"=>"Билеттер қосылды"]);
?>