<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

// Ambil data POST
$user_id = $_POST['user_id'] ?? null;
$goal_type = $_POST['goal_type'] ?? null;
$current_weight = $_POST['current_weight'] ?? null;
$target_weight = $_POST['target_weight'] ?? null;
$height = $_POST['height'] ?? null;
$weekly_goal = $_POST['weekly_goal'] ?? null;

// Validasi data
if (!$user_id || !$goal_type || !$current_weight || !$target_weight || !$height || !$weekly_goal) {
    echo json_encode(["message" => "All fields are required"]);
    exit;
}

// Simpan data ke database
$sql = "INSERT INTO user_goals (user_id, goal_type, current_weight, target_weight, height, weekly_goal) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $user_id, $goal_type, $current_weight, $target_weight, $height, $weekly_goal);

if ($stmt->execute()) {
    echo json_encode(["message" => "User goal saved successfully"]);
} else {
    echo json_encode(["message" => "Failed to save user goal"]);
}

$stmt->close();
$conn->close();
?>
