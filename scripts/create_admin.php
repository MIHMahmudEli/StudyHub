<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

$db = Database::getInstance()->getConnection();

$name = "Admin";
$email = "mohsin@gmail.com";
$password = "admin";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "admin";
$verified = 1;

// Check if exists
$check = $db->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo "User with this email already exists.";
    exit;
}

$stmt = $db->prepare("INSERT INTO users (name, email, password, role, verified, points) VALUES (?, ?, ?, ?, ?, 100)");
$stmt->bind_param("ssssi", $name, $email, $hashed_password, $role, $verified);

if ($stmt->execute()) {
    echo "Admin user created successfully.<br>";
    echo "Email: $email<br>";
    echo "Password: $password<br>";
} else {
    echo "Error: " . $stmt->error;
}
?>
