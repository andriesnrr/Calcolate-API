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
$token = bin2hex(random_bytes(16));

// Simpan token ke database
$sql = "UPDATE users SET verification_token = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $token, $email);
if ($stmt->execute()) {
    $verification_link = "https://yourdomain.com/verify_email.php?token=$token";
    mail($email, "Verifikasi Email", "Klik link berikut untuk verifikasi email Anda: $verification_link");

    echo json_encode(["message" => "Email verifikasi telah dikirim"]);
} else {
    echo json_encode(["error" => "Gagal mengirim email verifikasi"]);
}
?>
