<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User ID diperlukan"]);
    exit();
}

$backup_file = "backups/user_{$user_id}.json";

if (file_exists($backup_file)) {
    $user_data = json_decode(file_get_contents($backup_file), true);
    echo json_encode(["message" => "Data berhasil dipulihkan", "data" => $user_data]);
} else {
    echo json_encode(["error" => "Backup tidak ditemukan"]);
}
?>
