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

// Ensure required data is provided
if (!isset($data['user_id']) || !isset($data['weight']) || !isset($data['date']) || !isset($data['image'])) {
    echo json_encode(["message" => "User ID, weight, date, and image are required"]);
    exit;
}

$user_id = $data['user_id'];
$weight = $data['weight'];
$date = $data['date'];
$image = $data['image'];  // This should be the base64 encoded image

// SQL query to insert progress data
$sql = "INSERT INTO progress (user_id, weight, date, image) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $user_id, $weight, $date, $image);

// Execute the query and check if it was successful
if ($stmt->execute()) {
    echo json_encode(["message" => "Progress added successfully"]);
} else {
    echo json_encode(["message" => "Failed to add progress"]);
}

// Close connection
$stmt->close();
$conn->close();
?>
