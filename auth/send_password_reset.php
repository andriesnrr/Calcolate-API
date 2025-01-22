<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'])) {
    echo json_encode(["error" => "Email diperlukan"]);
    exit();
}

$email = $data['email'];

// Periksa apakah email terdaftar
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $token = bin2hex(random_bytes(16));
    $expire_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Simpan token reset password
    $sql = "INSERT INTO password_resets (email, token, expire_at) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $token, $expire_at);
    $stmt->execute();

    // Kirim email
    $reset_link = "https://yourdomain.com/reset_password.php?token=$token";
    mail($email, "Reset Password", "Klik link berikut untuk reset password: $reset_link");

    echo json_encode(["message" => "Email reset password telah dikirim"]);
} else {
    echo json_encode(["error" => "Email tidak ditemukan"]);
}
?>
