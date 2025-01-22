<?php
// Headers for CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php'; // Include the database connection

// Get JSON data sent by Flutter
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
if (!isset($data->email) || !isset($data->password)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Email dan password diperlukan"]);
    exit();
}

$email = trim($data->email);
$password = $data->password;

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Format email tidak valid"]);
    exit();
}

// Cari pengguna berdasarkan email
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        http_response_code(200); // OK
        echo json_encode([
            "message" => "Login berhasil",
            "user_id" => $user['id']
        ]);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "Password salah"]);
    }
} else {
    http_response_code(404); // Not Found
    echo json_encode(["error" => "Pengguna tidak ditemukan"]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
