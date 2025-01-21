<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'constants.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['amount']) || !isset($data['currency'])) {
    echo json_encode(["error" => "Amount dan currency diperlukan"]);
    exit();
}

$amount = $data['amount'];
$currency = $data['currency'];

// Simulasi intent pembayaran
$payment_intent = [
    "id" => uniqid("pi_"),
    "amount" => $amount,
    "currency" => $currency,
    "status" => "pending"
];

echo json_encode(["message" => "Payment intent created", "data" => $payment_intent]);
?>
