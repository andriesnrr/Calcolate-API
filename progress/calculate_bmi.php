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

if (!isset($data['weight']) || !isset($data['height'])) {
    echo json_encode(["error" => "Weight dan height diperlukan"]);
    exit();
}

$weight = $data['weight'];
$height = $data['height'] / 100; // Konversi cm ke meter

$bmi = $weight / ($height * $height);

$category = $bmi < 18.5 ? "Underweight" :
    ($bmi < 24.9 ? "Normal" :
    ($bmi < 29.9 ? "Overweight" : "Obese"));

echo json_encode([
    "bmi" => round($bmi, 2),
    "category" => $category
]);
?>
