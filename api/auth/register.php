<?php
// Headers for CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../config/database.php'; // Update path sesuai lokasi database.php

// Get JSON data sent by Flutter
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["message" => "Email dan password harus diisi"]);
    exit();
}

$email = $data['email'];
$password = $data['password'];

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "Email tidak valid"]);
    exit();
}

// Cek apakah email sudah terdaftar
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["message" => "Email sudah terdaftar"]);
} else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Registrasi berhasil"]);
    } else {
        echo json_encode(["message" => "Terjadi kesalahan saat registrasi"]);
    }
}

$stmt->close();
$conn->close();
?>
