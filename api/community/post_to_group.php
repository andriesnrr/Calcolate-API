<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['group_id']) || !isset($data['user_id']) || !isset($data['content'])) {
    echo json_encode(["error" => "Group ID, User ID, dan konten diperlukan"]);
    exit();
}

$group_id = $data['group_id'];
$user_id = $data['user_id'];
$content = $data['content'];

$sql = "INSERT INTO group_posts (group_id, user_id, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $group_id, $user_id, $content);

if ($stmt->execute()) {
    echo json_encode(["message" => "Postingan berhasil dibuat"]);
} else {
    echo json_encode(["error" => "Gagal membuat postingan"]);
}

$stmt->close();
$conn->close();
?>
