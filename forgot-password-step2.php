<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp_input = trim($_POST['verification_code']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if(!isset($_SESSION['fp_email']) || !isset($_SESSION['fp_otp'])){
        $_SESSION['fp_error'] = "Session expired. Try again.";
        header("Location: forgot-password.php");
        exit();
    }

    if($otp_input != $_SESSION['fp_otp']){
        $_SESSION['fp_error'] = "Incorrect OTP!";
        header("Location: forgot-password.php#forgot-password-step2");
        exit();
    }

    if($new_password !== $confirm_password){
        $_SESSION['fp_error'] = "Passwords do not match!";
        header("Location: forgot-password.php#forgot-password-step2");
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashed_password, $_SESSION['fp_email']);

    if($stmt->execute()){
        unset($_SESSION['fp_email'], $_SESSION['fp_otp'], $_SESSION['fp_otp_time']);
        $_SESSION['fp_success'] = "Password updated successfully! You can now login.";
        //header("Location: index.php");
        header("Location: forgot-password.php#forgot-password-step2");
        exit();
    } else {
        $_SESSION['fp_error'] = "Database error. Try again.";
        header("Location: forgot-password.php#forgot-password-step2");
        exit();
    }
}
