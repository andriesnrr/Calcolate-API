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

if (!isset($data['group_id']) || !isset($data['user_id'])) {
    echo json_encode(["error" => "Group ID dan User ID diperlukan"]);
    exit();
}

$group_id = $data['group_id'];
$user_id = $data['user_id'];

$sql = "INSERT INTO group_members (group_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $group_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Berhasil bergabung dengan grup"]);
} else {
    echo json_encode(["error" => "Gagal bergabung dengan grup"]);
}

$stmt->close();
$conn->close();
?>
