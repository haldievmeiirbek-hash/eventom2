<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include "db.php";

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "Пайдаланушы табылмады"]);
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(["status" => "error", "message" => "Қате пароль"]);
    exit();
}

echo json_encode(["status" => "success", "username" => $user['username']]);
?>