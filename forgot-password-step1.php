<?php
session_start();
include("includes/db.php");
include("mailer.php"); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $otp = rand(100000, 999999);

        $_SESSION['fp_email'] = $email;
        $_SESSION['fp_otp'] = $otp;
        $_SESSION['fp_otp_time'] = time();

        if(sendOTP($email, $user['name'], $otp, 'forgotpassword')){
            $_SESSION['fp_success'] = "OTP sent to your email!";
            header("Location: forgot-password.php#forgot-password-step2");
            exit();
        } else {
            $_SESSION['fp_error'] = "Failed to send OTP. Please try again.";
            header("Location: forgot-password.php");
            exit();
        }
    } else {
        $_SESSION['fp_error'] = "Email not found!";
        header("Location: forgot-password.php");
        exit();
    }
}
