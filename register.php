<?php
session_start();
include("includes/db.php"); // connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Store form data in session to repopulate the form
    $_SESSION['reg_form_data'] = [
        'name' => $name,
        'email' => $email
    ];

    // 1. Confirm passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['reg_error'] = "Passwords do not match.";
        header("Location: index.php#register");
        exit();
    }

    // 2. Check if email already exists using prepared statement
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email exists - stay on registration form
        $_SESSION['reg_error'] = "This email is already registered. Please use another one.";
        header("Location: index.php#register");
        exit();
    }
    $stmt->close();

    // 3. Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insert user into DB
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Clear form data from session
        unset($_SESSION['reg_form_data']);
        $_SESSION['reg_success'] = "Registration successful. You can login now.";
        header("Location: index.php#register");
        exit();
    } else {
        $_SESSION['reg_error'] = "Something went wrong. Please try again.";
        header("Location: index.php#register");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>