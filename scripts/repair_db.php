<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "<h2>Database Repair Tool</h2>";
$db = Database::getInstance()->getConnection();

echo "Attempting to DROP 'users' table...<br>";
// We use DROP TABLE IF EXISTS to handle both corruption and normal existence
if ($db->query("DROP TABLE IF EXISTS users")) {
    echo "DROP successful.<br>";
} else {
    echo "DROP failed: " . $db->error . "<br>";
}

echo "Attempting to CREATE 'users' table...<br>";
$createSql = "CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin', 'moderator') DEFAULT 'student',
    points INT(11) DEFAULT 0,
    verified TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($createSql)) {
    echo "CREATE successful.<br>";
    echo "<h3>Table 'users' restored.</h3>";
} else {
    echo "CREATE failed: " . $db->error . "<br>";
}

// Optional: Insert a default admin for testing?
// No, let user register to test the flow.
?>
