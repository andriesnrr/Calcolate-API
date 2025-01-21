<?php
// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500); // Internal Server Error
    die(json_encode(["message" => "Database connection failed"]));
}

// Read POST data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are provided
if (isset($data->email) && isset($data->password)) {
    $email = $data->email;
    $password = $data->password;

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("SELECT id, password, umur FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and validate password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Successful login
            echo json_encode([
                "message" => "Login successful",
                "user_id" => $user['id'],
                "umur" => $user['umur']  // Include 'umur' in the response
            ]);
        } else {
            // Invalid password
            http_response_code(401); // Unauthorized
            echo json_encode(["message" => "Invalid password"]);
        }
    } else {
        // User not found
        http_response_code(404); // Not Found
        echo json_encode(["message" => "User not found"]);
    }

    // Close statement
    $stmt->close();
} else {
    // Missing required fields
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Missing required fields"]);
}

// Close database connection
$conn->close();
?>
