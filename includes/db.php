<?php
$host = "localhost";
$user = "studyhub_user";
$pass = "StudyHub@123"; 
$db   = "studyhub";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
