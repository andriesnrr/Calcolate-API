<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['name']) || !isset($data['email'])) {
    echo json_encode(["error" => "User ID, name, dan email diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$name = trim($data['name']);
$email = trim($data['email']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Format email tidak valid"]);
    exit();
}

$sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $email, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Profil berhasil diperbarui"]);
} else {
    echo json_encode(["error" => "Gagal memperbarui profil"]);
}

$stmt->close();
$conn->close();
?>
