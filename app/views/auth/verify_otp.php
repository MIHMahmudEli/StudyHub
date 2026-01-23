<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | StudyHub</title>
    <link rel="stylesheet" href="<?php echo asset('css/otp-style.css'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
</head>
<body>
    <div class="container">
        <div class="otp-card">
            <?php 
               $context = $_SESSION['otp_context'] ?? 'register';
               if ($context === 'forgot_password'):
            ?>
                <h2>🔑 Reset Password</h2>
                <p>Enter the OTP sent to your email to reset your password.</p>
            <?php else: ?>
                <h2>🔑 Verify Your Email</h2>
                <p>Enter the OTP sent to your email to complete registration.</p>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo url('otp/verify'); ?>">
                <input type="text" name="otp" placeholder="Enter OTP" required maxlength="6">
                <button type="submit" name="verify">Verify OTP</button>
            </form>
            <p class="note">Didn't receive the code? Check your spam folder or <a href="<?php echo url('#register'); ?>">register again</a>.</p>
        </div>
    </div>
</body>
</html>
