<?php
// Database connection configuration
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $config = require __DIR__ . '/config.inc.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Connection failed: Database is not available.");
        }
    }
    return $pdo;
}