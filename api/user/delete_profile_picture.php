<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../config/database.php'; // Update path sesuai lokasi database.php


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id'])) {
    echo json_encode(["error" => "User ID diperlukan"]);
    exit();
}

$user_id = $data['user_id'];

$sql = "SELECT profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $photo_path = $user['profile_picture'];

    if ($photo_path && file_exists($photo_path)) {
        unlink($photo_path);
    }

    $sql = "UPDATE users SET profile_picture = NULL WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Foto profil berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus foto profil"]);
    }
} else {
    echo json_encode(["error" => "Pengguna tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
