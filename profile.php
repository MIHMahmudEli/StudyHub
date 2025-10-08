<?php
session_start();
include("includes/db.php");

// Security: must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit();
}

$userId = intval($_SESSION['user_id']);
$message = "";
$error = "";

// --- Fetch current user info ---
$stmt = $conn->prepare("SELECT name, email, role, points, password FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// --- Handle Profile Update (Name only, email locked) ---
if (isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);

    if (!empty($new_name)) {
        $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
        $stmt->bind_param("si", $new_name, $userId);
        $stmt->execute();

        $_SESSION['user_name'] = $new_name;
        $user['name'] = $new_name;

        $message = "Profile updated successfully!";
    } else {
        $error = "Name cannot be empty.";
    }
}

// --- Handle Password Update ---
if (isset($_POST['update_password'])) {
    $current_pass = trim($_POST['current_password']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if (empty($new_pass) || empty($confirm_pass)) {
        $error = "Please enter and confirm your new password.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "New password and confirm password do not match.";
    } else {
        // Validate password strength
        if (!preg_match("/.{8,}/", $new_pass)) {
            $error = "Password must be at least 8 characters long.";
        } elseif (!preg_match("/[A-Z]/", $new_pass)) {
            $error = "Password must contain at least 1 uppercase letter.";
        } elseif (!preg_match("/[a-z]/", $new_pass)) {
            $error = "Password must contain at least 1 lowercase letter.";
        } elseif (!preg_match("/[0-9]/", $new_pass)) {
            $error = "Password must contain at least 1 number.";
        } elseif (!preg_match("/[@$!%*?&#]/", $new_pass)) {
            $error = "Password must contain at least 1 special character (@$!%*?&#).";
        } else {
            // Verify current password
            if (password_verify($current_pass, $user['password'])) {
                $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param("si", $hashed, $userId);
                $stmt->execute();
                $message = "Password updated successfully!";
            } else {
                $error = "Current password is incorrect.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - StudyHub</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav">
            <li><a href="user_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li><a href="upload.php"><i class="fa fa-upload"></i> <span>Upload Notes</span></a></li>
            <li><a href="leaderboard.php"><i class="fa fa-trophy"></i> <span>Leaderboard</span></a></li>
            <li class="active"><a href="profile.php"><i class="fa fa-user"></i> <span>Profile</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-toggle">
                    <i class="fa fa-bars"></i>
                </div>
                <h2>My Profile - <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
            </div>
            <div class="topbar-right">
                <span class="role"><?php echo ucfirst($user['role']); ?></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </header>


        <!-- Page Content -->
        <div class="profile-container">
            <!-- Messages -->
            <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

            <!-- Profile Info Section -->
            <section class="settings-section">
                <h3>👤 Update Profile Info</h3>
                <form method="post">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                    <label>Email:</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

                    <label>Role:</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['role']); ?>" disabled>

                    <label>Points:</label>
                    <input type="text" value="<?php echo intval($user['points']); ?>" disabled>

                    <button type="submit" name="update_profile">Save Profile</button>
                </form>
            </section>

            <!-- Password Update Section -->
            <section class="settings-section">
                <h3>🔒 Change Password</h3>
                <form method="post" name="password_form">
                    <label>Current Password:</label>
                    <input type="password" name="current_password" placeholder="Enter current password" required>

                    <label>New Password:</label>
                    <input type="password" name="new_password" placeholder="Enter new password" required>

                    <label>Confirm New Password:</label>
                    <input type="password" name="confirm_password" placeholder="Confirm new password" required>

                    <button type="submit" name="update_password">Update Password</button>
                </form>
            </section>
        </div>
    </main>

    <script src="assets/js/profile-script.js"></script>
    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
