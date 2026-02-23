<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include "db.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status"=>"error","message"=>"Дерек алынбады"]);
    exit();
}

$username = trim($data['username']);
$password = trim($data['password']);

if (empty($username) || empty($password)) {
    echo json_encode(["status"=>"error","message"=>"Барлық өрістерді толтырыңыз"]);
    exit();
}

// Логин бар ма тексеру
$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status"=>"error","message"=>"Қолданушы бұрыннан бар"]);
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success","message"=>"Тіркелді"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Қате пайда болды"]);
}

$stmt->close();
$conn->close();
?>