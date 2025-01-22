<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User ID diperlukan"]);
    exit();
}

// Berat badan
$sql_weight = "SELECT MIN(weight) AS min_weight, MAX(weight) AS max_weight FROM weight_logs WHERE user_id = ?";
$stmt_weight = $conn->prepare($sql_weight);
$stmt_weight->bind_param("i", $user_id);
$stmt_weight->execute();
$weight_result = $stmt_weight->get_result()->fetch_assoc();

// BMI
$sql_bmi = "SELECT MIN(bmi) AS min_bmi, MAX(bmi) AS max_bmi FROM bmi_logs WHERE user_id = ?";
$stmt_bmi = $conn->prepare($sql_bmi);
$stmt_bmi->bind_param("i", $user_id);
$stmt_bmi->execute();
$bmi_result = $stmt_bmi->get_result()->fetch_assoc();

echo json_encode([
    "weight_progress" => $weight_result,
    "bmi_progress" => $bmi_result
]);

$stmt_weight->close();
$stmt_bmi->close();
$conn->close();
?>
