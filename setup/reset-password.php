<?php
/**
 * Quick fix script to update admin password
 */
require_once '../config/database.php';

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $db->query("UPDATE admins SET password = :hash WHERE email = 'admin@cyberx.com'", ['hash' => $hash]);
    echo "<h2>Password Reset Complete!</h2>";
    echo "<p>Admin credentials:</p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> admin@cyberx.com</li>";
    echo "<li><strong>Password:</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='../admin/login'>→ Go to Admin Login</a></p>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
