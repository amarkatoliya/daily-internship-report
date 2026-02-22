<?php
/**
 * EasyCart - PostgreSQL Database Connection
 * Uses PDO for secure, prepared-statement-ready queries
 * 
 * Usage: require_once 'Database/db.php';
 *        Or via data.php: require_once 'data.php';
 */

// Database Configuration
$db_host = 'localhost';
$db_port = '5432';
$db_name = 'easyCart';
$db_user = 'postgres';
$db_pass = '7979'; // Change this to your PostgreSQL password

try {
    // Create PDO connection
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";

    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    // Throw exceptions on error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Return associative arrays
        PDO::ATTR_EMULATE_PREPARES => false,                     // Use real prepared statements
    ]);

} catch (PDOException $e) {
    // Show error in development, hide in production
    die("Database connection failed: " . $e->getMessage());
}
