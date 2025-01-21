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

if (!isset($data['user_id']) || !isset($data['workout_name']) || !isset($data['duration']) || !isset($data['calories_burned'])) {
    echo json_encode(["error" => "User ID, workout name, duration, dan calories burned diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$workout_name = $data['workout_name'];
$duration = $data['duration'];
$calories_burned = $data['calories_burned'];

$sql = "INSERT INTO workout_logs (user_id, workout_name, duration, calories_burned) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issd", $user_id, $workout_name, $duration, $calories_burned);

if ($stmt->execute()) {
    echo json_encode(["message" => "Log olahraga berhasil ditambahkan"]);
} else {
    echo json_encode(["error" => "Gagal menambahkan log olahraga"]);
}

$stmt->close();
$conn->close();
?>
