<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the resepId from the request
$resepId = isset($_GET['id']) ? $_GET['id'] : '';

// Check if the resepId is provided
if (empty($resepId)) {
    echo json_encode(['message' => 'Recipe ID is required']);
    exit();
}

// Prepare the SQL query to fetch ingredients based on resepId
$sql = "SELECT name, quantity FROM ingredients WHERE recipe_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $resepId); // Bind the resepId parameter

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if ingredients are found
if ($result->num_rows > 0) {
    $ingredients = [];
    while ($ingredient = $result->fetch_assoc()) {
        $ingredients[] = [
            'name' => $ingredient['name'],
            'quantity' => $ingredient['quantity']
        ];
    }
    // Return the ingredients as JSON
    echo json_encode(['ingredients' => $ingredients]);
} else {
    echo json_encode(['message' => 'No ingredients found for this recipe']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
