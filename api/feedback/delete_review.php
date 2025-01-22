<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../config/database.php'; // Update path sesuai lokasi database.php

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['review_id'])) {
    echo json_encode(["error" => "Review ID diperlukan"]);
    exit();
}

$review_id = $data['review_id'];

$sql = "DELETE FROM reviews WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $review_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Ulasan berhasil dihapus"]);
} else {
    echo json_encode(["error" => "Gagal menghapus ulasan"]);
}

$stmt->close();
$conn->close();
?>
