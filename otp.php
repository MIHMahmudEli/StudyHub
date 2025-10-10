<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['reg_form_data']) || !isset($_SESSION['otp'])) {
    header("Location: main_index.php");
    exit();
}

$message = "";

if (isset($_POST['verify'])) {
    $entered_otp = $_POST['otp'];
    $session_otp = $_SESSION['otp'];

    if ($entered_otp == $session_otp) {
        $data = $_SESSION['reg_form_data'];
        $name = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        // Set timezone to Bangladesh
        date_default_timezone_set('Asia/Dhaka');
        $created_at = date('Y-m-d H:i:s');

        // Insert user with explicit local time
        $stmt = $conn->prepare("INSERT INTO users (`name`, email, `password`, verified, created_at) VALUES (?, ?, ?, 1, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $created_at);

        if ($stmt->execute()) {
            // Registration success
            unset($_SESSION['reg_form_data']);
            unset($_SESSION['otp']);
            header("Location: main_index.php#login");
            exit();
        } else {
            if ($conn->errno === 1062) {
                // 1062 = duplicate entry error
                $message = "⚠️ This email is already registered. Please log in instead.";
            } else {
                $message = "❌ Database error. Please try again later.";
            }
        }

        $stmt->close();
    } else {
        $message = "❌ Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | StudyHub</title>
    <link rel="stylesheet" href="assets/css/otp-style.css">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <div class="container">
        <div class="otp-card">
            <h2>🔑 Verify Your Email</h2>
            <p>Enter the OTP sent to your email to complete registration.</p>

            <?php if ($message != ""): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" required maxlength="6">
                <button type="submit" name="verify">Verify OTP</button>
            </form>
            <p class="note">Didn't receive the code? Check your spam folder or <a href="main_index.php#register">register again</a>.</p>
        </div>
    </div>
</body>
</html>
