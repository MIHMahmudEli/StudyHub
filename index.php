<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudyHub - Login/Register</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="assets/css/index-style.css">
<link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>

<!-- Logo Header -->
<header class="auth-header">
    <a href="index.html" class="logo">
        <i class="fa fa-graduation-cap"></i> StudyHub
    </a>
</header>

<div class="container">
    <!-- Login Form -->
    <form id="loginForm" class="active" method="POST" action="login.php">
        <h2>Welcome Back 👋</h2>
        <p class="subtitle">Sign in to continue your learning journey</p>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-messages">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="input-group">
            <i class="fa fa-envelope icon-left"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="password" placeholder="Password" id="loginPassword" required>
            <i class="fa fa-eye toggle-password"></i>
        </div>

        <div class="forgot-password">
            <a href="forgot-password.php">Forgot Password?</a>
        </div>

        <button type="submit" class="primary-btn">Login</button>
        <button type="button" class="toggle-btn">Don’t have an account? Register</button>
    </form>

    <!-- Register Form -->
    <form id="registerForm" method="POST" action="register.php">
        <h2>Join StudyHub ✨</h2>
        <p class="subtitle">Create your account and start sharing knowledge</p>

        <?php if(isset($_SESSION['reg_error'])): ?>
            <div class="error-messages">
                <?php echo htmlspecialchars($_SESSION['reg_error']); unset($_SESSION['reg_error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['reg_success'])): ?>
            <div class="success-messages">
                <?php echo htmlspecialchars($_SESSION['reg_success']); unset($_SESSION['reg_success']); ?>
            </div>
        <?php endif; ?>

        <div class="input-group">
            <i class="fa fa-user icon-left"></i>
            <input type="text" name="name" placeholder="Full Name" required>
        </div>

        <div class="input-group">
            <i class="fa fa-envelope icon-left"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="password" placeholder="Password" id="registerPassword" required>
            <i class="fa fa-eye toggle-password"></i>
        </div>

        <!-- Password rules -->
        <div class="password-rules" id="passwordRules">
            <p>Password must contain:</p>
            <ul>
                <li id="rule-length"><i class="fa fa-circle"></i> At least 8 characters</li>
                <li id="rule-uppercase"><i class="fa fa-circle"></i> 1 uppercase letter</li>
                <li id="rule-lowercase"><i class="fa fa-circle"></i> 1 lowercase letter</li>
                <li id="rule-number"><i class="fa fa-circle"></i> 1 number</li>
                <li id="rule-special"><i class="fa fa-circle"></i> 1 special character (@$!%*?&#)</li>
            </ul>
        </div>

        <div class="input-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" id="confirmPassword" required>
            <i class="fa fa-eye toggle-password"></i>
        </div>

        <span class="error-message"></span>

        <button type="submit" class="primary-btn" id="registerSubmit" disabled>Register</button>
        <button type="button" class="toggle-btn">Already have an account? Login</button>
    </form>
</div>

<script src="assets/js/index-script.js"></script>
</body>
</html>

<?php
if (isset($_SESSION['reg_form_data'])) unset($_SESSION['reg_form_data']);
?>
