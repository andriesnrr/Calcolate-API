<?php
// Aktifkan CORS jika diperlukan
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Ambil path dari URL
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Hapus bagian awal yang merujuk ke `index.php`
$path = str_replace(dirname($script_name), '', $request_uri);

// Parsing path
$path = trim($path, '/');

// Routing sederhana
switch ($path) {
    case 'create_progress':
        require_once 'api/create_progress.php';
        break;
    case 'create_recipe':
        require_once 'api/create_recipe.php';
        break;
    case 'get_bahan':
        require_once 'api/get_bahan.php';
        break;
    case 'get_preview_progress':
        require_once 'api/get_preview_progress.php';
        break;
    case 'get_progress':
        require_once 'api/get_progress.php';
        break;
    case 'get_recipe_detail':
        require_once 'api/get_recipe_detail.php';
        break;
    case 'get_recipes':
        require_once 'api/get_recipes.php';
        break;
    case 'get_step':
        require_once 'api/get_step.php';
        break;
    case 'login':
        require_once 'api/login.php';
        break;
    case 'register':
        require_once 'api/register.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "API endpoint not found"]);
        break;
}
