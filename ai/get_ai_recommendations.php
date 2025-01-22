<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Contoh rekomendasi berbasis AI
$recommendations = [
    ["type" => "workout", "name" => "HIIT Training", "duration" => "20 mins"],
    ["type" => "nutrition", "name" => "Low-carb Salad", "calories" => 200],
    ["type" => "hydration", "name" => "Drink 2 liters of water"]
];

echo json_encode($recommendations);
?>
