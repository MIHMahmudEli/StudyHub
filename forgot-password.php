<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudyHub - Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="assets/css/forgot-password.css">
<link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>

<div class="container">

    <!-- Show success/error messages -->
    <?php if(isset($_SESSION['fp_error'])): ?>
        <div class="form-message error-msg"><?= $_SESSION['fp_error']; unset($_SESSION['fp_error']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['fp_success'])): ?>
        <div class="form-message success-msg"><?= $_SESSION['fp_success']; unset($_SESSION['fp_success']); ?></div>
    <?php endif; ?>

    <!-- Step 1: Confirm Email -->
    <form id="step1" method="POST" action="forgot-password-step1.php" class="form active">
        <h2>Reset Your Password</h2>
        <p>Please confirm your email:</p>
        <div class="input-group">
            <i class="fa fa-envelope icon-left"></i>
            <input type="email" name="email" placeholder="Enter your registered Email" required>
        </div>
        <button type="submit">Confirm Email</button>
        <button type="button" class="toggle-btn" id="backToLogin">Cancel</button>
    </form>

    <!-- Step 2: Enter Verification Code + New Password -->
    <form id="step2" method="POST" action="forgot-password-step2.php" class="form">
        <h2>Reset Your Password</h2>

        <!-- Message placeholder inside form -->
        <div id="formMessage" class="message"></div>

        <div class="input-group">
            <i class="fa fa-key icon-left"></i>
            <input type="text" name="verification_code" placeholder="Verification Code" required>
        </div>
        <div class="input-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="new_password" id="newPassword" placeholder="New Password" required>
        </div>
        <div class="input-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm Password" required>
        </div>
        <button type="submit">Reset Password</button>
        <button type="button" class="toggle-btn" id="resendCode">Resend Code</button>
        <button type="button" class="toggle-btn" id="cancelReset">Cancel</button>
        <p class="info-text">Step 1: Enter the verification code you received by email.<br>
        Step 2: If you didn’t receive the code, check spam or click resend.</p>
    </form>

</div>

<script src="assets/js/forgot-password.js"></script>
</body>
</html>
