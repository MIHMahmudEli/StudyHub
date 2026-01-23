<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "<h2>Database Debugger</h2>";
echo "Connecting to Host: " . DB_HOST . ", User: " . DB_USER . ", DB: " . DB_NAME . "<br>";

$db = Database::getInstance()->getConnection();

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
echo "Connected successfully.<br>";

$result = $db->query("SHOW TABLES");

if ($result) {
    echo "<h3>Tables in " . DB_NAME . ":</h3><ul>";
    $tables = [];
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";

    echo "<h3>Testing access to ALL tables:</h3>";
    foreach ($tables as $table) {
        echo "<strong>Checking '$table'...</strong> ";
        try {
            $check = $db->query("SELECT * FROM `$table` LIMIT 1");
            if ($check) {
                echo "<span style='color:green'>OK</span> (Rows: " . $check->num_rows . ")<br>";
            } else {
                 echo "<span style='color:red'>FAILED</span>: " . $db->error . "<br>";
            }
        } catch (Throwable $e) {
            echo "<span style='color:red'>EXCEPTION</span>: " . $e->getMessage() . "<br>";
        }
    }

} else {
    echo "Error showing tables: " . $db->error; 
}
?>
