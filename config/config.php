<?php
/**
 * CyberX Configuration File
 * Security-hardened version with environment variable support
 */

// ============================================
// Load Environment Variables
// ============================================
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Helper function to get environment variable with default
function env($key, $default = '') {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// ============================================
// Application Environment
// ============================================
define('APP_ENV', env('APP_ENV', 'development'));
define('IS_PRODUCTION', APP_ENV === 'production');

// ============================================
// Session Security Configuration (BEFORE session_start)
// ============================================
// Only set session settings if session hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Lax');
    if (IS_PRODUCTION || isset($_SERVER['HTTPS'])) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// ============================================
// Error Reporting Configuration
// ============================================
if (IS_PRODUCTION) {
    // Production: Hide errors, log them instead
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__) . '/error.log');
} else {
    // Development: Show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Site Configuration
define('SITE_NAME', 'CyberX');
define('SITE_URL', 'http://localhost/CyberX');
define('SITE_EMAIL', 'cyberx.ye@gmail.com');

// ============================================
// SMTP Email Configuration (from .env file)
// ============================================
define('SMTP_ENABLED', env('SMTP_ENABLED', 'false') === 'true');
define('SMTP_HOST', env('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int)env('SMTP_PORT', 587));
define('SMTP_USER', env('SMTP_USER', ''));
define('SMTP_PASS', env('SMTP_PASS', ''));
define('SMTP_FROM_EMAIL', env('SMTP_FROM_EMAIL', ''));
define('SMTP_FROM_NAME', env('SMTP_FROM_NAME', 'CyberX'));
define('SMTP_SECURE', env('SMTP_SECURE', 'tls'));
define('SMTP_DEBUG', (int)env('SMTP_DEBUG', 0));

// Database Configuration (from .env file)
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'cyberx_db'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', 'utf8mb4');

// Path Configuration
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'public/assets/uploads/');
define('UPLOADS_URL', SITE_URL . '/assets/uploads/');

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Video Upload Settings
define('MAX_VIDEO_SIZE', 300 * 1024 * 1024); // 300MB
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/ogg']);

// Security Settings
define('CSRF_TOKEN_NAME', 'csrf_token');

// Generate CSRF token
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

/**
 * Get CSRF token
 */
function get_csrf_token() {
    return $_SESSION[CSRF_TOKEN_NAME] ?? '';
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Secure redirect - validates URL is within the same domain
 * Use this for user-supplied redirect URLs
 */
function secure_redirect($url, $default = null) {
    $default = $default ?? SITE_URL;
    
    // If URL is empty, use default
    if (empty($url)) {
        redirect($default);
    }
    
    // Check if URL starts with our site URL
    if (strpos($url, SITE_URL) === 0) {
        redirect($url);
    }
    
    // Check if it's a relative URL (starts with /)
    if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
        redirect(SITE_URL . $url);
    }
    
    // Fallback to default
    redirect($default);
}

/**
 * Set flash message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Check if admin is logged in
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Require admin login
 */
function require_admin_login() {
    if (!is_admin_logged_in()) {
        redirect(SITE_URL . '/admin/login.php');
    }
}
