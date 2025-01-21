<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

// Headers for CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed"]));
}

// Get JSON data sent by Flutter
$data = json_decode(file_get_contents("php://input"));

// Create an array to track missing fields
$missingFields = [];

// Check each field and add to the missingFields array if not set
if (!isset($data->email)) {
    $missingFields[] = 'email';
}
if (!isset($data->password)) {
    $missingFields[] = 'password';
}

// If there are missing fields, return them in the response
if (!empty($missingFields)) {
    echo json_encode([
        "message" => "Missing required fields",
        "missing_fields" => $missingFields
    ]);
    exit;
}

$email = trim($data->email);
$password = $data->password;

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "Invalid email format"]);
    exit;
}

// Check if email already exists
$checkEmailStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkEmailStmt->bind_param("s", $email);
$checkEmailStmt->execute();
$checkEmailResult = $checkEmailStmt->get_result();

if ($checkEmailResult->num_rows > 0) {
    echo json_encode(["message" => "Email already exists"]);
    exit;
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user data into the database
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["message" => "Registration successful"]);
} else {
    echo json_encode(["message" => "Error during registration"]);
}

$stmt->close();
$conn->close();
?>
