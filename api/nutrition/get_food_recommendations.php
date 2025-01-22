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

// Contoh: Preferensi pengguna berdasarkan kebutuhan kalori
$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User ID diperlukan"]);
    exit();
}

// Ambil preferensi pengguna (misalnya, target kalori harian)
$sql = "SELECT target_calories FROM user_goals WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $preferences = $result->fetch_assoc();

    // Contoh respons rekomendasi makanan
    $recommendations = [
        ["name" => "Oatmeal", "calories" => 150],
        ["name" => "Grilled Chicken", "calories" => 200],
        ["name" => "Salad", "calories" => 100]
    ];

    echo json_encode([
        "recommendations" => $recommendations,
        "target_calories" => $preferences['target_calories']
    ]);
} else {
    echo json_encode(["error" => "Preferensi pengguna tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
