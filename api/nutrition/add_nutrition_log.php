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

if (!isset($data['user_id']) || !isset($data['food_id']) || !isset($data['date']) || !isset($data['calories'])) {
    echo json_encode(["error" => "User ID, Food ID, tanggal, dan kalori diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$food_id = $data['food_id'];
$date = $data['date'];
$calories = $data['calories'];

$sql = "INSERT INTO nutrition_logs (user_id, food_id, date, calories) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisd", $user_id, $food_id, $date, $calories);

if ($stmt->execute()) {
    echo json_encode(["message" => "Log nutrisi berhasil ditambahkan"]);
} else {
    echo json_encode(["error" => "Gagal menambahkan log nutrisi"]);
}

$stmt->close();
$conn->close();
?>
