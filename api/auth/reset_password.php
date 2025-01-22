<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../config/database.php'; // Update path sesuai lokasi database.php

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['token']) || !isset($data['new_password'])) {
    echo json_encode(["error" => "Token dan password baru diperlukan"]);
    exit();
}

$token = $data['token'];
$new_password = password_hash($data['new_password'], PASSWORD_DEFAULT);

// Periksa token
$sql = "SELECT email FROM password_resets WHERE token = ? AND expire_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $email = $result->fetch_assoc()['email'];

    // Perbarui password pengguna
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);
    if ($stmt->execute()) {
        // Hapus token
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo json_encode(["message" => "Password berhasil direset"]);
    } else {
        echo json_encode(["error" => "Gagal mengatur ulang password"]);
    }
} else {
    echo json_encode(["error" => "Token tidak valid atau telah kedaluwarsa"]);
}
?>
