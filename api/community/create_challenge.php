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

if (!isset($data['challenge_name']) || !isset($data['user_id']) || !isset($data['goal'])) {
    echo json_encode(["error" => "Challenge name, User ID, dan goal diperlukan"]);
    exit();
}

$challenge_name = $data['challenge_name'];
$user_id = $data['user_id'];
$goal = $data['goal'];
$description = $data['description'] ?? null;

$sql = "INSERT INTO challenges (challenge_name, description, goal, created_by) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdi", $challenge_name, $description, $goal, $user_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Tantangan berhasil dibuat", "challenge_id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => "Gagal membuat tantangan"]);
}

$stmt->close();
$conn->close();
?>
