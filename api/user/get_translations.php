<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'database.php';

$language = $_GET['language'] ?? 'en';

$sql = "SELECT `key`, `value` FROM translations WHERE language_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $language);
$stmt->execute();
$result = $stmt->get_result();

$translations = [];
while ($row = $result->fetch_assoc()) {
    $translations[$row['key']] = $row['value'];
}

echo json_encode($translations);

$stmt->close();
$conn->close();
?>
