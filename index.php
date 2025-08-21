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

<div class="container">
    <!-- Login Form -->
    <form id="loginForm" class="active" method="POST" action="login.php">
        <h2>Login</h2>

        <!-- Error message -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-messages">
                <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']);
                ?>
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
            <!-- Redirect to forgot-password.php -->
            <a href="forgot-password.php">Forgot Password?</a>
        </div>

        <button type="submit">Login</button>
        <button type="button" class="toggle-btn">Don't have an account? Register</button>
    </form>

    <!-- Register Form -->
    <form id="registerForm" method="POST" action="register.php">
        <h2>Register</h2>

        <!-- Error message -->
        <?php if(isset($_SESSION['reg_error'])): ?>
            <div style="color: red; text-align:center; margin-bottom:10px;">
                <?php 
                echo htmlspecialchars($_SESSION['reg_error']); 
                unset($_SESSION['reg_error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Success message -->
        <?php if(isset($_SESSION['reg_success'])): ?>
            <div style="color: green; text-align:center; margin-bottom:10px;">
                <?php 
                echo htmlspecialchars($_SESSION['reg_success']); 
                unset($_SESSION['reg_success']);
                ?>
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

        <div class="input-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" id="confirmPassword" required>
            <i class="fa fa-eye toggle-password"></i>
        </div>
        
        <span class="error-message"></span>

        <button type="submit">Register</button>
        <button type="button" class="toggle-btn">Already have an account? Login</button>
    </form>
</div>

<script src="assets/js/index-script.js"></script>
</body>
</html>

<?php
// Clear registration form data after displaying
if (isset($_SESSION['reg_form_data'])) {
    unset($_SESSION['reg_form_data']);
}
?>
