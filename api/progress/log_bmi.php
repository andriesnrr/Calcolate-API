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

if (!isset($data['user_id']) || !isset($data['bmi']) || !isset($data['category']) || !isset($data['date'])) {
    echo json_encode(["error" => "User ID, BMI, category, dan date diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$bmi = $data['bmi'];
$category = $data['category'];
$date = $data['date'];

$sql = "INSERT INTO bmi_logs (user_id, bmi, category, date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("idss", $user_id, $bmi, $category, $date);

if ($stmt->execute()) {
    echo json_encode(["message" => "BMI berhasil dicatat"]);
} else {
    echo json_encode(["error" => "Gagal mencatat BMI"]);
}

$stmt->close();
$conn->close();
?>
