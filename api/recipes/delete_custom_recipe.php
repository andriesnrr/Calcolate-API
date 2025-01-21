
<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['recipe_id'])) {
    echo json_encode(["error" => "Recipe ID diperlukan"]);
    exit();
}

$recipe_id = $data['recipe_id'];

$sql = "DELETE FROM custom_recipes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Resep berhasil dihapus"]);
} else {
    echo json_encode(["error" => "Gagal menghapus resep"]);
}

$stmt->close();
$conn->close();
?>
