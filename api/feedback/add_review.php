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

if (!isset($data['user_id']) || !isset($data['item_id']) || !isset($data['review'])) {
    echo json_encode(["error" => "User ID, item ID, dan review diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$item_id = $data['item_id'];
$review = $data['review'];

$sql = "INSERT INTO reviews (user_id, item_id, review) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $item_id, $review);

if ($stmt->execute()) {
    echo json_encode(["message" => "Ulasan berhasil ditambahkan"]);
} else {
    echo json_encode(["error" => "Gagal menambahkan ulasan"]);
}

$stmt->close();
$conn->close();
?>
