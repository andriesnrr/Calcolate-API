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

$token = $_GET['token'];

$sql = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["message" => "Email berhasil diverifikasi"]);
} else {
    echo json_encode(["error" => "Token tidak valid atau sudah digunakan"]);
}
?>
