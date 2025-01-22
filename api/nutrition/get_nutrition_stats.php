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

$user_id = $_GET['user_id'] ?? null;
$date = $_GET['date'] ?? null;

if (!$user_id || !$date) {
    echo json_encode(["error" => "User ID dan tanggal diperlukan"]);
    exit();
}

$sql = "SELECT SUM(calories) AS total_calories FROM nutrition_logs WHERE user_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stats = $result->fetch_assoc();
    echo json_encode($stats);
} else {
    echo json_encode(["total_calories" => 0]);
}

$stmt->close();
$conn->close();
?>
