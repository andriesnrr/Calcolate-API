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

// Get the recipe ID from the request
$resepId = isset($_GET['id']) ? $_GET['id'] : '';

// Check if the recipe ID is provided
if (empty($resepId)) {
    echo json_encode(['message' => 'Recipe ID is required']);
    exit();
}

// Prepare the SQL query to fetch steps based on recipe_id
$sql = "SELECT step FROM steps WHERE recipe_id = ? ORDER BY id";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $resepId); // Bind the recipe ID parameter

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if steps are found
if ($result->num_rows > 0) {
    $steps = [];
    while ($step = $result->fetch_assoc()) {
        $steps[] = $step['step'];
    }
    // Return the steps as JSON
    echo json_encode(['steps' => $steps]);
} else {
    echo json_encode(['message' => 'No steps found for this recipe']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
