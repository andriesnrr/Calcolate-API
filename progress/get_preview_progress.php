<?php
// Set header CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcolatedb";

// Dapatkan user_id dari POST body
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

// Validasi user_id
if (!$user_id) {
    echo json_encode(["message" => "User ID is required"]);
    exit;
}

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(["message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Query SQL untuk mendapatkan data progress berdasarkan user_id
$sql = "SELECT id, user_id, date, weight FROM progress WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Cek jika statement berhasil dipersiapkan
if (!$stmt) {
    echo json_encode(["message" => "Failed to prepare statement"]);
    exit;
}

$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Array untuk menyimpan data progress
$progress = [];

// Periksa apakah ada data yang ditemukan
if ($result->num_rows > 0) {
    // Ambil setiap baris data
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

// Tutup statement dan koneksi
$stmt->close();
$conn->close();

// Kembalikan data progress dalam format JSON
echo json_encode($progress);
?>
