<?php
// Strict error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define absolute path to the hidden root directory
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Load Core Configuration & Global Helpers
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/includes/language.php';
require_once ROOT_PATH . '/includes/functions.php';

// Simple PSR-4 Autoloader for the App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load Router and dispatch
use App\Core\Router;

$router = new Router();

// Public Routes
$router->add('/', 'PageController@home');
$router->add('/about', 'PageController@about');
$router->add('/contact', 'PageController@contact');
$router->add('/portfolio', 'PageController@portfolio');
$router->add('/services', 'PageController@services');
$router->add('/courses', 'PageController@courses');
$router->add('/enroll', 'PageController@enroll');
$router->add('/watch', 'PageController@watch');
$router->add('/verify-enrollment', 'PageController@verifyEnrollment');

// Admin Routes
$router->add('/admin', 'AdminController@index');
$router->add('/admin/dashboard', 'AdminController@dashboard');
$router->add('/admin/courses', 'AdminController@courses');
$router->add('/admin/lessons', 'AdminController@lessons');
$router->add('/admin/login', 'AdminController@login');
$router->add('/admin/logout', 'AdminController@logout');
$router->add('/admin/messages', 'AdminController@messages');
$router->add('/admin/services', 'AdminController@services');
$router->add('/admin/solutions', 'AdminController@solutions');
$router->add('/admin/students', 'AdminController@students');

$url = $_GET['url'] ?? '';
$router->dispatch($url);