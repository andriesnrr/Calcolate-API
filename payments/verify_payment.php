<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['payment_id'])) {
    echo json_encode(["error" => "Payment ID diperlukan"]);
    exit();
}

$payment_id = $data['payment_id'];

// Simulasi verifikasi pembayaran
$is_successful = rand(0, 1) === 1;

if ($is_successful) {
    echo json_encode(["message" => "Payment verified successfully", "payment_id" => $payment_id]);
} else {
    echo json_encode(["error" => "Payment verification failed"]);
}
?>
