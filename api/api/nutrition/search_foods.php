<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// FatSecret API credentials
$client_id = "5d3260c55fd1490a83d50c65196ebaad";
$client_secret = "fd7ad731ce154b37a157414e9cb8976a";

// Ambil query pencarian
$query = $_GET['query'] ?? '';

if (!$query) {
    echo json_encode(["error" => "Kata kunci pencarian diperlukan"]);
    exit();
}

// Request token access
$token_url = "https://oauth.fatsecret.com/connect/token";
$data = [
    "grant_type" => "client_credentials",
    "scope" => "basic",
    "client_id" => $client_id,
    "client_secret" => $client_secret
];

$options = [
    "http" => [
        "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
        "method" => "POST",
        "content" => http_build_query($data)
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($token_url, false, $context);
$token = json_decode($response, true)['access_token'];

// Cari makanan
$search_url = "https://platform.fatsecret.com/rest/server.api";
$params = [
    "method" => "foods.search",
    "format" => "json",
    "search_expression" => $query,
    "oauth_token" => $token
];

$search_response = file_get_contents($search_url . '?' . http_build_query($params));
echo $search_response;
?>
