<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

echo "<h1>Welcome to your Dashboard</h1>";
echo "<p>User ID: " . $_SESSION['user_id'] . "</p>";
echo "<p>Role: " . $_SESSION['role'] . "</p>";
echo "<a href='logout.php'>Logout</a>";
echo "<br>";
echo "<a href='upload.php'>upload</a>";
echo "<br>";
?>
