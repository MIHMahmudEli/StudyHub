<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudyHub - Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link rel="stylesheet" href="<?php echo asset('css/forgot-password.css'); ?>">
</head>
<body>

<header class="auth-header">
    <a href="<?php echo url(''); ?>" class="logo">
        <i class="fa fa-graduation-cap"></i> StudyHub
    </a>
</header>

<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
    <div class="glass-card p-4 rounded-4 shadow-lg" style="max-width: 420px; width: 100%;">

        <!-- Flash Messages -->
        <?php if(isset($_SESSION['fp_error'])): ?>
            <div class="alert alert-danger text-center py-2">
                <?= htmlspecialchars($_SESSION['fp_error']); unset($_SESSION['fp_error']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['fp_success'])): ?>
            <div class="alert alert-success text-center py-2">
                <?= htmlspecialchars($_SESSION['fp_success']); unset($_SESSION['fp_success']); ?>
            </div>
        <?php endif; ?>

        <!-- Step 1 -->
        <form id="step1" method="POST" action="<?php echo url('forgot_password'); ?>">
            <h2 class="text-center mb-3">Forgot Password 🔑</h2>
            <p class="text-center text-light mb-4">Enter your registered email to receive a verification code.</p>

            <div class="input-group mb-3 position-relative">
                <span class="input-icon"><i class="fa fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">Send Verification Code</button>
            <a href="<?php echo url('auth'); ?>#login" class="btn btn-secondary w-100">Cancel</a>
        </form>

        <!-- Step 2 -->
        <form id="step2" method="POST" action="<?php echo url('reset_password'); ?>" class="d-none">
            <h2 class="text-center mb-3">Reset Password 🔒</h2>
            <p class="text-center text-light mb-4">Enter the verification code and set a new password.</p>

            <div class="input-group mb-3 position-relative">
                <span class="input-icon"><i class="fa fa-key"></i></span>
                <input type="text" name="verification_code" class="form-control" placeholder="Verification Code" required>
            </div>

            <div class="input-group mb-3 position-relative">
                <span class="input-icon"><i class="fa fa-lock"></i></span>
                <input type="password" name="new_password" id="newPassword" class="form-control" placeholder="New Password" required>
            </div>

            <div class="input-group mb-3 position-relative">
                <span class="input-icon"><i class="fa fa-lock"></i></span>
                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
            </div>

            <div id="formMessage" class="text-center small mb-2" style="display:none;"></div>

            <button type="submit" class="btn btn-primary w-100 mb-2">Reset Password</button>
            <button type="button" class="btn btn-outline-light w-100 mb-2" id="resendCode">Resend Code</button>
            <a href="<?php echo url('auth'); ?>#login" class="btn btn-secondary w-100" id="cancelReset">Cancel</a>

            <p class="info-text mt-3 text-center small text-light">
                Didn’t get the code? Check spam or click “Resend Code”.
            </p>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/forgot-password.js'); ?>"></script>
</body>
</html>
