<?php
include("includes/db.php");
$name = "Super Admin";
$email = "mohsin12@gmail.com";
$password = password_hash("m", PASSWORD_DEFAULT);

// Insert user into database
$stmt = $conn->prepare("INSERT INTO users (name, email, password, verified) VALUES (?, ?, ?, 1)");
$stmt->bind_param("sss", $name, $email, $password);
if($stmt->execute()){
    echo "Admin user inserted successfully.";
} else {
    echo "Error inserting admin user: " . $stmt->error;
}
?>