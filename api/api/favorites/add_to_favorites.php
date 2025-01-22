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

if (!isset($data['user_id']) || !isset($data['item_id']) || !isset($data['item_type'])) {
    echo json_encode(["error" => "User ID, item ID, dan item type diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$item_id = $data['item_id'];
$item_type = $data['item_type'];

$sql = "INSERT INTO favorites (user_id, item_id, item_type) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $item_id, $item_type);

if ($stmt->execute()) {
    echo json_encode(["message" => "Item berhasil ditambahkan ke favorit"]);
} else {
    echo json_encode(["error" => "Gagal menambahkan item ke favorit"]);
}

$stmt->close();
$conn->close();
?>
