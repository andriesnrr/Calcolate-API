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

if (!isset($data['user_id']) || !isset($data['offline_data'])) {
    echo json_encode(["error" => "User ID dan offline data diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$offline_data = json_encode($data['offline_data']);

$sql = "INSERT INTO offline_data (user_id, data) VALUES (?, ?) ON DUPLICATE KEY UPDATE data = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $offline_data, $offline_data);

if ($stmt->execute()) {
    echo json_encode(["message" => "Data offline berhasil disinkronkan"]);
} else {
    echo json_encode(["error" => "Gagal menyinkronkan data offline"]);
}

$stmt->close();
$conn->close();
?>
