<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch recipes
$sql = "SELECT id, nama, gambar FROM recipes"; // Adjust table and column names as needed
$result = $conn->query($sql);

// Prepare an array to store recipes
$recipes = [];

// Check if there are any recipes
if ($result->num_rows > 0) {
    // Fetch and store each recipe
    while($row = $result->fetch_assoc()) {
        $recipes[] = [
            'id' => $row['id'],
            'nama' => $row['nama'],
            'gambar' => $row['gambar']
        ];
    }
} else {
    echo json_encode(["message" => "No recipes found"]);
    exit;
}

// Close connection
$conn->close();

// Return recipes as JSON
echo json_encode($recipes);
?>
