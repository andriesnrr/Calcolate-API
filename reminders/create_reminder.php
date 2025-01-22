<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['title']) || !isset($data['date'])) {
    echo json_encode(["error" => "User ID, title, dan date diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$title = $data['title'];
$date = $data['date'];

$sql = "INSERT INTO reminders (user_id, title, date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $title, $date);

if ($stmt->execute()) {
    echo json_encode(["message" => "Pengingat berhasil dibuat"]);
} else {
    echo json_encode(["error" => "Gagal membuat pengingat"]);
}

$stmt->close();
$conn->close();
?>
