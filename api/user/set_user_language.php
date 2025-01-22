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

if (!isset($data['user_id']) || !isset($data['language'])) {
    echo json_encode(["error" => "User ID dan bahasa diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$language = $data['language'];

$sql = "UPDATE users SET language = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $language, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Bahasa pengguna berhasil diperbarui"]);
} else {
    echo json_encode(["error" => "Gagal memperbarui bahasa pengguna"]);
}

$stmt->close();
$conn->close();
?>
