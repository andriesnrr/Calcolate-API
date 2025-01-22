<?php
// Aktifkan CORS jika diperlukan
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Ambil path dari URL
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Hapus bagian awal yang merujuk ke `index.php`
$path = str_replace(dirname($script_name), '', $request_uri);

// Parsing path
$path = trim($path, '/');

// Routing berdasarkan struktur folder
switch ($path) {
    // User Authentication
    case 'login':
        require_once 'api/auth/login.php';
        break;
    case 'register':
        require_once 'api/auth/register.php';
        break;
    case 'logout':
        require_once 'api/auth/logout.php';
        break;
    case 'send_password_reset':
        require_once 'api/auth/send_password_reset.php';
        break;
    case 'reset_password':
        require_once 'api/auth/reset_password.php';
        break;
    case 'send_verification_email':
        require_once 'api/auth/send_verification_email.php';
        break;
    case 'verify_email':
        require_once 'api/auth/verify_email.php';
        break;
    case 'delete_account':
        require_once 'api/auth/delete_account.php';
        break;

    // User Management
    case 'get_user_profile':
        require_once 'api/user/get_user_profile.php';
        break;
    case 'update_user_profile':
        require_once 'api/user/update_user_profile.php';
        break;
    case 'upload_profile_picture':
        require_once 'api/user/upload_profile_picture.php';
        break;
    case 'delete_profile_picture':
        require_once 'api/user/delete_profile_picture.php';
        break;
    case 'set_user_language':
        require_once 'api/user/set_user_language.php';
        break;
    case 'get_translations':
        require_once 'api/user/get_translations.php';
        break;
    case 'set_user_theme':
        require_once 'api/user/set_user_theme.php';
        break;
    case 'get_user_theme':
        require_once 'api/user/get_user_theme.php';
        break;

    // Progress
    case 'log_bodyweight':
        require_once 'api/progress/log_bodyweight.php';
        break;
    case 'log_bodyweight_with_photo':
        require_once 'api/progress/log_bodyweight_with_photo.php';
        break;
    case 'get_bodyweight_progress':
        require_once 'api/progress/get_bodyweight_progress.php';
        break;
    case 'calculate_bmi':
        require_once 'api/progress/calculate_bmi.php';
        break;
    case 'log_bmi':
        require_once 'api/progress/log_bmi.php';
        break;
    case 'get_bmi_logs':
        require_once 'api/progress/get_bmi_logs.php';
        break;
    case 'get_weight_progress':
        require_once 'api/progress/get_weight_progress.php';
        break;
    case 'get_calorie_progress':
        require_once 'api/progress/get_calorie_progress.php';
        break;
    case 'get_progress_insights':
        require_once 'api/progress/get_progress_insights.php';
        break;

    // Nutrition
    case 'search_foods':
        require_once 'api/nutrition/search_foods.php';
        break;
    case 'get_food_detail':
        require_once 'api/nutrition/get_food_detail.php';
        break;
    case 'add_nutrition_log':
        require_once 'api/nutrition/add_nutrition_log.php';
        break;
    case 'get_nutrition_stats':
        require_once 'api/nutrition/get_nutrition_stats.php';
        break;
    case 'get_food_recommendations':
        require_once 'api/nutrition/get_food_recommendations.php';
        break;

    // Reminders
    case 'create_reminder':
        require_once 'api/reminders/create_reminder.php';
        break;
    case 'get_reminders':
        require_once 'api/reminders/get_reminders.php';
        break;

    // Notifications
    case 'send_notification':
        require_once 'api/notifications/send_notification.php';
        break;
    case 'get_notifications':
        require_once 'api/notifications/get_notifications.php';
        break;

    // Schedules
    case 'create_schedule':
        require_once 'api/schedules/create_schedule.php';
        break;
    case 'get_schedules':
        require_once 'api/schedules/get_schedules.php';
        break;

    // Recipes
    case 'create_custom_recipe':
        require_once 'api/recipes/create_custom_recipe.php';
        break;
    case 'get_custom_recipes':
        require_once 'api/recipes/get_custom_recipes.php';
        break;
    case 'delete_custom_recipe':
        require_once 'api/recipes/delete_custom_recipe.php';
        break;

    // Payments
    case 'create_payment_intent':
        require_once 'api/payments/create_payment_intent.php';
        break;
    case 'verify_payment':
        require_once 'api/payments/verify_payment.php';
        break;
    case 'get_subscription_status':
        require_once 'api/payments/get_subscription_status.php';
        break;

    // Analytics
    case 'get_usage_stats':
        require_once 'api/analytics/get_usage_stats.php';
        break;
    case 'get_admin_analytics':
        require_once 'api/analytics/get_admin_analytics.php';
        break;
    case 'get_user_streak':
        require_once 'api/analytics/get_user_streak.php';
        break;

    // Backup
    case 'backup_user_data':
        require_once 'api/backup/backup_user_data.php';
        break;
    case 'restore_user_data':
        require_once 'api/backup/restore_user_data.php';
        break;

    // Offline
    case 'sync_offline_data':
        require_once 'api/offline/sync_offline_data.php';
        break;
    case 'fetch_offline_data':
        require_once 'api/offline/fetch_offline_data.php';
        break;
    case 'get_cached_data':
        require_once 'api/offline/get_cached_data.php';
        break;

    // AI Recommendations
    case 'get_ai_recommendations':
        require_once 'api/ai/get_ai_recommendations.php';
        break;

    // Changelog
    case 'get_changelog':
        require_once 'api/changelog/get_changelog.php';
        break;

    // Reports
    case 'generate_progress_report':
        require_once 'api/reports/generate_progress_report.php';
        break;

    // Community
    case 'create_group':
        require_once 'api/community/create_group.php';
        break;
    case 'join_group':
        require_once 'api/community/join_group.php';
        break;
    case 'post_to_group':
        require_once 'api/community/post_to_group.php';
        break;
    case 'create_challenge':
        require_once 'api/community/create_challenge.php';
        break;
    case 'join_challenge':
        require_once 'api/community/join_challenge.php';
        break;
    case 'get_challenges':
        require_once 'api/community/get_challenges.php';
        break;
    case 'get_challenge_progress':
        require_once 'api/community/get_challenge_progress.php';
        break;

    // Default: 404 for unknown endpoints
    default:
        http_response_code(404);
        echo json_encode(["message" => "API endpoint not found"]);
        break;
}
?>
