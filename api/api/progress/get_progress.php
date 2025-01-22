<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

// Get the user_id from the POST request
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

if ($user_id === null) {
    echo json_encode(["message" => "User ID is required"]);
    exit;
}

// SQL query to fetch progress data for the specific user
$sql = "SELECT date, weight, image FROM progress WHERE user_id = ? ORDER BY date ASC"; // Adjust table and column names as needed
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare an array to store progress data
$progressData = [];

// Check if there are any progress records
if ($result->num_rows > 0) {
    // Fetch and store each progress record
    while ($row = $result->fetch_assoc()) {
        $progressData[] = [
            'date' => $row['date'],
            'weight' => $row['weight'],
            'image' => $row['image']
        ];
    }
} else {
    echo json_encode(["message" => "No progress data found for the specified user"]);
    exit;
}

// Close connection
$stmt->close();
$conn->close();

// Return progress data directly as JSON
echo json_encode($progressData);
?>
