<?php
session_start();
include("includes/db.php");
include("includes/redirect_helper.php");

// If accessed via GET, redirect to main index
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redirect("main_index#login");
    exit();
}

// Process POST login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

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
            redirect("main_index#login");
            exit();
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Save session
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['points']    = $row['points'];

            // Login event
            date_default_timezone_set('Asia/Dhaka');
            $timestamp = date('Y-m-d H:i:s');
            $type = 'view';
            $event = $conn->prepare("INSERT INTO events (user_id, `type`, `at`) VALUES (?, ?, ?)");
            $event->bind_param("iss", $_SESSION['user_id'], $type, $timestamp);
            $event->execute();

            if ($event->error) {
                // Log error but don't die - allow login to continue
                error_log("Event logging failed: " . $event->error);
            }

            // Redirect based on role
            if ($row['role'] === 'admin' || $row['role'] === 'moderator') {
                redirect("admin_dashboard");
            } else {
                redirect("home");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            redirect("main_index#login");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        redirect("main_index#login");
        exit();
    }
}
?>