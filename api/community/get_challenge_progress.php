<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require '../config/database.php'; // Update path sesuai lokasi database.php

$challenge_id = $_GET['challenge_id'] ?? null;
$user_id = $_GET['user_id'] ?? null;

if (!$challenge_id || !$user_id) {
    echo json_encode(["error" => "Challenge ID dan User ID diperlukan"]);
    exit();
}

$sql = "SELECT progress FROM challenge_progress WHERE challenge_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $challenge_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $progress = $result->fetch_assoc();
    echo json_encode($progress);
} else {
    echo json_encode(["error" => "Progres tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
