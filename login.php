<?php
session_start();
include("includes/db.php"); // connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepared statement for security
    $stmt = $conn->prepare("SELECT id, name, password, role, verified, points FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check if email is verified
        if ($row['verified'] == 0) {
            $_SESSION['error'] = "⚠ Please verify your email before logging in. Check your inbox.";
            header("Location: index.php#login");
            exit();
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Save session
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['points']    = $row['points']; // now it's correct

            // Redirect to dashboard
            header("Location: home.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: index.php#login");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: index.php#login");
        exit();
    }
}
?>
