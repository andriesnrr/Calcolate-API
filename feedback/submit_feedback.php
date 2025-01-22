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

if (!isset($data['user_id']) || !isset($data['feedback'])) {
    echo json_encode(["error" => "User ID dan feedback diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$feedback = $data['feedback'];

$sql = "INSERT INTO feedback (user_id, feedback) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $feedback);

if ($stmt->execute()) {
    echo json_encode(["message" => "Feedback berhasil dikirim"]);
} else {
    echo json_encode(["error" => "Gagal mengirim feedback"]);
}

$stmt->close();
$conn->close();
?>
