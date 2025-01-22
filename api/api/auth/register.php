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

// Periksa apakah email sudah terdaftar
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    http_response_code(409); // Conflict
    echo json_encode(["error" => "Email sudah terdaftar"]);
    $stmt->close();
    $conn->close();
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Masukkan pengguna baru ke dalam database
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashed_password);

if ($stmt->execute()) {
    http_response_code(201); // Created
    echo json_encode([
        "message" => "Pendaftaran berhasil",
        "user_id" => $stmt->insert_id
    ]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Terjadi kesalahan saat mendaftar"]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
