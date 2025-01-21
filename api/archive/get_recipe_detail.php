<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

$conn = new mysqli($servername, $username, $password, $dbname); // Fix the variable name

// Check connection
if ($conn->connect_error) {
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the recipe ID from the request
$resepId = isset($_GET['id']) ? $_GET['id'] : '';

// Check if the ID is provided
if (empty($resepId)) {
    echo json_encode(['message' => 'Recipe ID is required']);
    exit();
}

// Prepare the SQL query to fetch recipe details
$sql = "SELECT recipes.id, recipes.nama, recipes.gambar, recipes.karbohidrat, recipes.protein, recipes.lemak, users.email 
        FROM recipes 
        JOIN users ON recipes.user_id = users.id
        WHERE recipes.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $resepId); // Bind the recipe ID parameter

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the recipe exists
if ($result->num_rows > 0) {
    $recipe = $result->fetch_assoc();

    // Return the recipe details as JSON without any processing on the 'gambar' field
    echo json_encode([
        'id' => $recipe['id'],
        'nama' => $recipe['nama'],
        'gambar' => $recipe['gambar'], // Return gambar as is (Base64 if it already is)
        'karbohidrat' => $recipe['karbohidrat'],
        'protein' => $recipe['protein'],
        'lemak' => $recipe['lemak'],
        'email' => $recipe['email']
    ]);
} else {
    echo json_encode(['message' => 'Recipe not found']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
