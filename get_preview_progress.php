<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

// Get the user_id from the POST body
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

// Validate the user_id
if (!$user_id) {
    echo json_encode(["message" => "User ID is required"]);
    exit;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch progress data for the provided user_id
$sql = "SELECT id, user_id, date, weight FROM progress WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare an array to store progress data
$progress = [];

// Check if there are any records
if ($result->num_rows > 0) {
    // Fetch and store each progress record
    while ($row = $result->fetch_assoc()) {
        $progress[] = [
            'id' => $row['id'],
            'date' => $row['date'],
            'weight' => $row['weight']
        ];
    }
} else {
    echo json_encode(["message" => "No progress data found for the given user ID"]);
    exit;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return progress data as JSON
echo json_encode($progress);
?>
