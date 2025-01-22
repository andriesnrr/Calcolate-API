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

if (!isset($data['user_id']) || !isset($data['weight']) || !isset($data['date'])) {
    echo json_encode(["error" => "User ID, weight, dan date diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$weight = $data['weight'];
$date = $data['date'];

$sql = "INSERT INTO weight_logs (user_id, weight, date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ids", $user_id, $weight, $date);

if ($stmt->execute()) {
    echo json_encode(["message" => "Berat badan berhasil dicatat"]);
} else {
    echo json_encode(["error" => "Gagal mencatat berat badan"]);
}

$stmt->close();
$conn->close();
?>
