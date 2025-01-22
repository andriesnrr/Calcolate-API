<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Log the incoming data for debugging
error_log("Received data: " . print_r($data, true));

// Ensure required data is provided
$requiredFields = ['user_id', 'nama', 'karbohidrat', 'protein', 'lemak', 'gambar', 'ingredients', 'steps'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($data[$field])) {
        $missingFields[] = $field;
    }
}

// If any field is missing, log the missing fields and return an error
if (!empty($missingFields)) {
    $errorMessage = "Missing required fields: " . implode(", ", $missingFields);
    error_log($errorMessage);
    echo json_encode(["message" => $errorMessage]);
    exit;
}

// Assign data from the request
$user_id = $data['user_id'];
$nama = $data['nama'];
$karbohidrat = $data['karbohidrat'];
$protein = $data['protein'];
$lemak = $data['lemak'];
$gambar = $data['gambar'];  // This should be the base64 encoded image
$ingredients = $data['ingredients']; // This should be an array
$steps = $data['steps']; // This should be an array

// Begin transaction to ensure atomicity of multiple inserts
$conn->begin_transaction();

try {
    // SQL query to insert recipe data
    $sql = "INSERT INTO recipes (nama, karbohidrat, protein, lemak, gambar, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nama, $karbohidrat, $protein, $lemak, $gambar, $user_id);
    $stmt->execute();
    $recipe_id = $stmt->insert_id;  // Get the last inserted recipe ID
    $stmt->close();

    // Insert ingredients data
    foreach ($ingredients as $ingredient) {
        $ingredient_name = $ingredient['name'];
        $ingredient_quantity = $ingredient['quantity'];
        $sql = "INSERT INTO ingredients (recipe_id, name, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $recipe_id, $ingredient_name, $ingredient_quantity);
        $stmt->execute();
        $stmt->close();
    }

    // Insert steps data
    foreach ($steps as $step) {
        $step_text = $step['step'];
        $sql = "INSERT INTO steps (recipe_id, step) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $recipe_id, $step_text);
        $stmt->execute();
        $stmt->close();
    }

    // Commit the transaction
    $conn->commit();

    echo json_encode(["message" => "Recipe added successfully"]);
} catch (Exception $e) {
    // Rollback the transaction in case of any error
    $conn->rollback();
    error_log("Failed to add recipe: " . $e->getMessage());
    echo json_encode(["message" => "Failed to add recipe: " . $e->getMessage()]);
}

// Close connection
$conn->close();
?>
