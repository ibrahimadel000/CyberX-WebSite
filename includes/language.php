<?php
/**
 * CyberX Language Handler
 * Detects language preference, loads translations, provides t() helper
 */

// Detect language: ?lang= param > session > cookie > default
$supported_langs = ['en', 'ar'];
$default_lang = 'en';

// Check query param first
if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_langs)) {
    $current_lang = $_GET['lang'];
    $_SESSION['lang'] = $current_lang;
    setcookie('cyberx_lang', $current_lang, time() + (86400 * 30), '/'); // 30 days
} elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $supported_langs)) {
    $current_lang = $_SESSION['lang'];
} elseif (isset($_COOKIE['cyberx_lang']) && in_array($_COOKIE['cyberx_lang'], $supported_langs)) {
    $current_lang = $_COOKIE['cyberx_lang'];
    $_SESSION['lang'] = $current_lang;
} else {
    $current_lang = $default_lang;
}

// Set RTL flag
$is_rtl = ($current_lang === 'ar');

// Load translation file
$lang_file = __DIR__ . '/../lang/' . $current_lang . '.json';
$translations = [];

if (file_exists($lang_file)) {
    $json = file_get_contents($lang_file);
    $translations = json_decode($json, true);
    if ($translations === null) {
        $translations = [];
    }
}

/**
 * Get translated string by dot-notation key
 * Example: t('nav.home') returns "Home" or "الرئيسية"
 * Supports variable substitution: t('footer.copyright', ['year' => 2026])
 * 
 * @param string $key Dot-notation key (e.g., 'nav.home')
 * @param array $vars Optional variables to substitute {var} placeholders
 * @return string Translated string or the key itself as fallback
 */
function t($key, $vars = []) {
    global $translations;
    
    $keys = explode('.', $key);
    $value = $translations;
    
    foreach ($keys as $k) {
        if (is_array($value) && isset($value[$k])) {
            $value = $value[$k];
        } else {
            // Key not found — return the key itself as fallback
            return $key;
        }
    }
    
    // If the result is not a string, return key
    if (!is_string($value)) {
        return $key;
    }
    
    // Substitute variables
    if (!empty($vars)) {
        foreach ($vars as $varName => $varValue) {
            $value = str_replace('{' . $varName . '}', $varValue, $value);
        }
    }
    
    return $value;
}

/**
 * Build URL with current language parameter preserved
 * 
 * @param string $url The base URL
 * @return string URL with ?lang= appended if not default
 */
function lang_url($url) {
    global $current_lang, $default_lang;
    
    // Always append lang param to keep consistency
    $separator = (strpos($url, '?') !== false) ? '&' : '?';
    return $url . $separator . 'lang=' . $current_lang;
}

/**
 * Get the switch URL (toggle to other language)
 * Preserves the current page URL but switches the lang param
 * 
 * @return string URL to switch to the other language
 */
function switch_lang_url() {
    global $current_lang;
    $other_lang = ($current_lang === 'en') ? 'ar' : 'en';
    
    // Get current URL and replace/add lang param
    $current_url = $_SERVER['REQUEST_URI'];
    
    // Remove existing lang param
    $current_url = preg_replace('/([?&])lang=[^&]*/', '', $current_url);
    // Clean up trailing ? or &
    $current_url = rtrim($current_url, '?&');
    
    $separator = (strpos($current_url, '?') !== false) ? '&' : '?';
    return $current_url . $separator . 'lang=' . $other_lang;
}
