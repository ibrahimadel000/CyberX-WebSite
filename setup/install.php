<?php
/**
 * CyberX Database Setup Script
 * Run this once to create all tables and sample data
 */

require_once '../config/database.php';

echo "<h1>CyberX Database Setup</h1>";
echo "<pre>";

try {
    $pdo = $db->getConnection();
    
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Split by semicolons but keep them for execution
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $success_count++;
            
            // Show table creation messages
            if (stripos($statement, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE.*?`?(\w+)`?/i', $statement, $matches);
                echo "✓ Created table: " . ($matches[1] ?? 'unknown') . "\n";
            } elseif (stripos($statement, 'INSERT INTO') !== false) {
                preg_match('/INSERT INTO\s+`?(\w+)`?/i', $statement, $matches);
                echo "✓ Inserted data into: " . ($matches[1] ?? 'unknown') . "\n";
            }
        } catch (PDOException $e) {
            // Ignore "already exists" errors
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Duplicate') === false) {
                echo "⚠ Warning: " . $e->getMessage() . "\n";
                $error_count++;
            }
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ Setup Complete!\n";
    echo "Statements executed: $success_count\n";
    if ($error_count > 0) {
        echo "Warnings: $error_count\n";
    }
    echo str_repeat("=", 50) . "\n\n";
    
    echo "<strong>Default Admin Credentials:</strong>\n";
    echo "Email: admin@cyberx.com\n";
    echo "Password: admin123\n\n";
    
    echo "<a href='../'>→ Go to Homepage</a>\n";
    echo "<a href='../admin/login'>→ Go to Admin Login</a>\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
