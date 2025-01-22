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

if (!isset($data['group_name']) || !isset($data['user_id'])) {
    echo json_encode(["error" => "Group name dan User ID diperlukan"]);
    exit();
}

$group_name = $data['group_name'];
$user_id = $data['user_id'];
$description = $data['description'] ?? null;

$sql = "INSERT INTO groups (group_name, description, created_by) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $group_name, $description, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Grup berhasil dibuat", "group_id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => "Gagal membuat grup"]);
}

$stmt->close();
$conn->close();
?>
