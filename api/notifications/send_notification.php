<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../config/database.php'; // Update path sesuai lokasi database.php

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['message'])) {
    echo json_encode(["error" => "User ID dan message diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$message = $data['message'];

$sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $message);

if ($stmt->execute()) {
    echo json_encode(["message" => "Notifikasi berhasil dikirim"]);
} else {
    echo json_encode(["error" => "Gagal mengirim notifikasi"]);
}

$stmt->close();
$conn->close();
?>
