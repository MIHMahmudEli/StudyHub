<?php
session_start();
include("includes/db.php"); // Make sure this connects properly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $_SESSION['reg_form_data'] = [
        'name' => $name,
        'email' => $email,
        'password' => $password
    ];

    // Check passwords
    if ($password !== $confirmPassword) {
        $_SESSION['reg_error'] = "Passwords do not match.";
        header("Location: index.php#register");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if(!$stmt){
        die("Prepare failed: ".$conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email exists
        $_SESSION['reg_error'] = "This email is already registered. Please use another email.";
        $stmt->close();
        header("Location: index.php#register");
        exit();
    }
    $stmt->close();

    // Generate OTP
    $_SESSION['otp'] = rand(100000, 999999);

    include('mailer.php');
    if(sendOTP($email, $name, $_SESSION['otp'])) {
        header("Location: otp.php");
        exit();
    } else {
        $_SESSION['reg_error'] = "Failed to send OTP email. Please try again.";
        header("Location: index.php#register");
        exit();
    }

    $conn->close();
}
?>
