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

if (!$user_id) {
    echo json_encode(["error" => "User ID diperlukan"]);
    exit();
}

$sql = "SELECT theme FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $theme = $result->fetch_assoc()['theme'];
    echo json_encode(["theme" => $theme]);
} else {
    echo json_encode(["error" => "Pengguna tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
