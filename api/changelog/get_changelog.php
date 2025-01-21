<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Contoh log perubahan
$changelog = [
    ["version" => "1.0.0", "date" => "2023-01-01", "changes" => ["Initial release"]],
    ["version" => "1.1.0", "date" => "2023-02-15", "changes" => ["Added user profiles", "Improved performance"]],
    ["version" => "1.2.0", "date" => "2023-03-30", "changes" => ["Introduced AI recommendations", "Bug fixes"]]
];

echo json_encode($changelog);
?>
