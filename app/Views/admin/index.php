<?php
/**
 * Admin Index - Security Redirect
 * Prevents directory listing and redirects to login page
 */

// Redirect to login page
header('Location: ' . SITE_URL . '/admin/login');
exit;
