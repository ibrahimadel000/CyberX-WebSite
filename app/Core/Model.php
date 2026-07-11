<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Model {
    protected static $pdo = null;

    /**
     * Get the PDO database connection centrally.
     */
    protected static function getDB() {
        if (self::$pdo === null) {
            // Ensure config is loaded to get DB constants
            if (!defined('DB_HOST')) {
                require_once ROOT_PATH . '/config/config.php';
            }

            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // In production, you might want to log this instead of dying.
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}