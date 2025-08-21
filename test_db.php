<?php
$host = "localhost";
$user = "studyhub_user";
$pass = "StudyHub@123"; 
$db   = "studyhub";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Connected to database successfully!<br>";

$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    echo " - " . $row[0] . "<br>";
}
$conn->close();
?>
