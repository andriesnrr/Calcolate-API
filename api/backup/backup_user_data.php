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

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User ID diperlukan"]);
    exit();
}

// Contoh backup: Simpan semua data pengguna ke dalam file JSON
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $backup_file = "backups/user_{$user_id}.json";
    file_put_contents($backup_file, json_encode($user_data));
    echo json_encode(["message" => "Backup berhasil dibuat", "backup_file" => $backup_file]);
} else {
    echo json_encode(["error" => "Data pengguna tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
