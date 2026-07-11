<?php

namespace App\Core;

class Controller {
    
    /**
     * Render a view template.
     * 
     * @param string $view The view path (e.g. 'public/home')
     * @param array $args Data to pass to the view
     */
    public function view($view, $args = []) {
        // Extract the variables to a local namespace
        extract($args);
        
        // Ensure language functions are loaded so views can use t()
        if (!function_exists('t')) {
            require_once ROOT_PATH . '/includes/language.php';
        }
        
        // Make global language configuration available to the view
        global $current_lang, $default_lang, $is_rtl;

        $file = ROOT_PATH . "/app/Views/$view.php";

        if (is_readable($file)) {
            require $file;
        } else {
            die("View not found: $file");
        }
    }
    
    /**
     * Redirect to a specific URL safely.
     */
    public function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}