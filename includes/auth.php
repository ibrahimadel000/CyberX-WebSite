<?php
/**
 * CyberX Authentication Functions
 */

/**
 * Generate secure random token
 */
function generate_token($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Hash password
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if password meets requirements
 */
function validate_password($password) {
    if (strlen($password) < 8) {
        return ['valid' => false, 'error' => 'Password must be at least 8 characters'];
    }
    return ['valid' => true];
}

// ============================================
// Remember Me Token Functions
// ============================================

define('REMEMBER_COOKIE_ADMIN', 'cyberx_remember_admin');
define('REMEMBER_EXPIRY_DAYS', 30);

/**
 * Create a remember me token and set cookie
 */
function create_remember_token($user_type, $user_id) {
    global $db;
    
    // Generate a secure random token
    $token = bin2hex(random_bytes(32));
    $token_hash = password_hash($token, PASSWORD_DEFAULT);
    $expires_at = date('Y-m-d H:i:s', time() + (REMEMBER_EXPIRY_DAYS * 24 * 60 * 60));
    
    // Remove any existing tokens for this user
    $db->delete('remember_tokens', 'user_type = :type AND user_id = :id', [
        'type' => $user_type,
        'id' => $user_id
    ]);
    
    // Insert new token
    $db->insert('remember_tokens', [
        'user_type' => $user_type,
        'user_id' => $user_id,
        'token_hash' => $token_hash,
        'expires_at' => $expires_at
    ]);
    
    // Set the cookie with the raw token
    $cookie_name = REMEMBER_COOKIE_ADMIN;
    $cookie_value = $user_id . ':' . $token;
    
    setcookie(
        $cookie_name,
        $cookie_value,
        [
            'expires' => time() + (REMEMBER_EXPIRY_DAYS * 24 * 60 * 60),
            'path' => '/',
            'secure' => false, // Set to true in production with HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );
    
    return true;
}

/**
 * Verify remember me token and auto-login
 */
function verify_remember_token($user_type) {
    global $db;
    
    $cookie_name = REMEMBER_COOKIE_ADMIN;
    
    if (!isset($_COOKIE[$cookie_name])) {
        return false;
    }
    
    $cookie_value = $_COOKIE[$cookie_name];
    $parts = explode(':', $cookie_value, 2);
    
    if (count($parts) !== 2) {
        clear_remember_token($user_type);
        return false;
    }
    
    list($user_id, $token) = $parts;
    $user_id = (int)$user_id;
    
    // Get the stored token hash
    $stored = $db->fetch(
        "SELECT * FROM remember_tokens WHERE user_type = :type AND user_id = :id AND expires_at > NOW()",
        ['type' => $user_type, 'id' => $user_id]
    );
    
    if (!$stored) {
        clear_remember_token($user_type);
        return false;
    }
    
    // Verify the token
    if (!password_verify($token, $stored['token_hash'])) {
        clear_remember_token($user_type);
        return false;
    }
    
    // Token is valid - get user and login
    $user = $db->fetch("SELECT * FROM admins WHERE id = :id", ['id' => $user_id]);
    if ($user) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['admin_email'] = $user['email'];
        return true;
    }
    
    clear_remember_token($user_type);
    return false;
}

/**
 * Clear remember me token and cookie
 */
function clear_remember_token($user_type) {
    global $db;
    
    $cookie_name = REMEMBER_COOKIE_ADMIN;
    
    // Get user ID from cookie before clearing
    if (isset($_COOKIE[$cookie_name])) {
        $parts = explode(':', $_COOKIE[$cookie_name], 2);
        if (count($parts) === 2) {
            $user_id = (int)$parts[0];
            // Delete from database
            $db->delete('remember_tokens', 'user_type = :type AND user_id = :id', [
                'type' => $user_type,
                'id' => $user_id
            ]);
        }
    }
    
    // Clear the cookie
    setcookie(
        $cookie_name,
        '',
        [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );
    
    unset($_COOKIE[$cookie_name]);
}
