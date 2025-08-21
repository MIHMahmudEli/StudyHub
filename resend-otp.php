<?php
session_start();
include("includes/db.php");
include("mailer.php");

header("Content-Type: application/json");

if(!isset($_SESSION['fp_email'])){
    echo json_encode(["status" => "error", "message" => "Session expired. Please start again."]);
    exit();
}

$email = $_SESSION['fp_email'];

// Get user name (optional, for nicer email)
$stmt = $conn->prepare("SELECT name FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Email not found."]);
    exit();
}

$user = $result->fetch_assoc();
$otp = rand(100000, 999999);

// Update session
$_SESSION['fp_otp'] = $otp;
$_SESSION['fp_otp_time'] = time();

// Send new OTP
if(sendOTP($email, $user['name'], $otp, 'forgotpassword')){
    echo json_encode(["status" => "success", "message" => "A new OTP has been sent to your email."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to send OTP. Try again."]);
}
