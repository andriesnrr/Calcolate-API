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

if (!isset($data['user_id']) || !isset($data['recipe_name']) || !isset($data['ingredients'])) {
    echo json_encode(["error" => "User ID, recipe name, dan ingredients diperlukan"]);
    exit();
}

$user_id = $data['user_id'];
$recipe_name = $data['recipe_name'];
$ingredients = json_encode($data['ingredients']);

$sql = "INSERT INTO custom_recipes (user_id, recipe_name, ingredients) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $recipe_name, $ingredients);

if ($stmt->execute()) {
    echo json_encode(["message" => "Resep berhasil dibuat"]);
} else {
    echo json_encode(["error" => "Gagal membuat resep"]);
}

$stmt->close();
$conn->close();
?>
