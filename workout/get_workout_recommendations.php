<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Contoh: Rekomendasi olahraga berdasarkan level kebugaran
$user_fitness_level = $_GET['fitness_level'] ?? 'beginner';

$recommendations = [
    'beginner' => [
        ["name" => "Walking", "duration" => "30 mins", "calories_burned" => 120],
        ["name" => "Yoga", "duration" => "20 mins", "calories_burned" => 80]
    ],
    'intermediate' => [
        ["name" => "Running", "duration" => "20 mins", "calories_burned" => 200],
        ["name" => "Cycling", "duration" => "30 mins", "calories_burned" => 250]
    ],
    'advanced' => [
        ["name" => "HIIT", "duration" => "20 mins", "calories_burned" => 300],
        ["name" => "Weightlifting", "duration" => "40 mins", "calories_burned" => 350]
    ]
];

echo json_encode($recommendations[$user_fitness_level] ?? []);
?>
