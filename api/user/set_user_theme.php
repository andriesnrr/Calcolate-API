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

if (!isset($data['user_id']) || !isset($data['theme'])) {
    echo json_encode(["error" => "User ID dan tema diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$theme = $data['theme'];

$sql = "UPDATE users SET theme = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $theme, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Tema berhasil diperbarui"]);
} else {
    echo json_encode(["error" => "Gagal memperbarui tema"]);
}

$stmt->close();
$conn->close();
?>
