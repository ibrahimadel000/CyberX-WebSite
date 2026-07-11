<?php
/**
 * CyberX Admin Logout
 */

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Clear Remember Me token
clear_remember_token('admin');

// Destroy session
session_unset();
session_destroy();

// Redirect to login
redirect(SITE_URL . '/admin/login');
