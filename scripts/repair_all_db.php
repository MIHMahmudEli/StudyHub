<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "<h2>Full Database Repair Tool</h2>";
$db = Database::getInstance()->getConnection();

function tableExistsAndHealthy($db, $table) {
    try {
        $result = $db->query("SELECT 1 FROM `$table` LIMIT 1");
        return $result !== false;
    } catch (Throwable $e) {
        return false;
    }
}

// 1. Check/Repair Users
echo "<h3>Checking 'users' table...</h3>";
if (tableExistsAndHealthy($db, 'users')) {
    echo "<span style='color:green'>Users table is healthy. Preserving data.</span><br>";
} else {
    echo "<span style='color:red'>Users table is corrupted. Recreating...</span><br>";
    $db->query("DROP TABLE IF EXISTS users");
    $sql = "CREATE TABLE users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('student', 'admin', 'moderator') DEFAULT 'student',
        points INT(11) DEFAULT 0,
        verified TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if ($db->query($sql)) echo "Users table restored.<br>";
    else echo "Error restoring users: " . $db->error . "<br>";
}

// 2. Repair 'notes'
echo "<h3>Repairing 'notes' table...</h3>";
$db->query("DROP TABLE IF EXISTS notes");
$sql = "CREATE TABLE notes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    uploader_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    subject VARCHAR(100),
    course_code VARCHAR(50),
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(20),
    avg_rating DECIMAL(3,2) DEFAULT 0.00,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    downloads INT(11) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploader_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($db->query($sql)) echo "Notes table restored.<br>";
else echo "Error restoring notes: " . $db->error . "<br>";

// 3. Repair 'events'
echo "<h3>Repairing 'events' table...</h3>";
$db->query("DROP TABLE IF EXISTS events");
$sql = "CREATE TABLE events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    type VARCHAR(50) NOT NULL,
    at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($db->query($sql)) echo "Events table restored.<br>";
else echo "Error restoring events: " . $db->error . "<br>";

// 4. Repair 'bookmarks'
echo "<h3>Repairing 'bookmarks' table...</h3>";
$db->query("DROP TABLE IF EXISTS bookmarks");
$sql = "CREATE TABLE bookmarks (
    user_id INT(11) NOT NULL,
    note_id INT(11) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, note_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($db->query($sql)) echo "Bookmarks table restored.<br>";
else echo "Error restoring bookmarks: " . $db->error . "<br>";

// 5. Repair 'reviews'
echo "<h3>Repairing 'reviews' table...</h3>";
$db->query("DROP TABLE IF EXISTS reviews");
$sql = "CREATE TABLE reviews (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    note_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(1) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($db->query($sql)) echo "Reviews table restored.<br>";
else echo "Error restoring reviews: " . $db->error . "<br>";

// 6. Repair 'resources'
echo "<h3>Repairing 'resources' table...</h3>";
$db->query("DROP TABLE IF EXISTS resources");
$sql = "CREATE TABLE resources (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($db->query($sql)) echo "Resources table restored.<br>";
else echo "Error restoring resources: " . $db->error . "<br>";

// 7. Repair 'reports'
echo "<h3>Repairing 'reports' table...</h3>";
$db->query("DROP TABLE IF EXISTS reports");
$sql = "CREATE TABLE reports (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    note_id INT(11),
    reason TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if ($db->query($sql)) echo "Reports table restored.<br>";
else echo "Error restoring reports: " . $db->error . "<br>";

echo "<h2>Repair Complete</h2>";
?>
