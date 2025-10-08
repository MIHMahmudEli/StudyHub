<?php
session_start();
include("includes/db.php");

// Security: only logged-in students allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: main_index.php#login");
    exit();
}

// Get user info
$userId = intval($_SESSION['user_id']);
$stmt = $conn->prepare("SELECT name, email, points, created_at FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - StudyHub</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"><!-- reuse admin dashboard styles -->
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
            <li class="active"><a href="user_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li><a href="upload.php"><i class="fa fa-upload"></i> <span>Upload Notes</span></a></li>
            <li><a href="leaderboard.php"><i class="fa fa-trophy"></i> <span>Leaderboard</span></a></li>
            <li><a href="profile.php"><i class="fa fa-user"></i> <span>Profile</span></a></li>
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
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
            </div>
            <div class="topbar-right">
                <span class="role">Student</span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </header>


        <!-- Dashboard Cards -->
        <section class="cards">
            <!-- Profile Card -->
            <div class="card">
                <h3>👤 Profile</h3>
                <p><strong><?php echo htmlspecialchars($user['name']); ?></strong></p>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>⭐ Points: <?php echo intval($user['points']); ?></p>
                <a href="profile.php">View / Edit Profile</a>
            </div>

            <!-- Uploaded Notes Card -->
            <div class="card">
                <h3>📘 Uploaded Notes</h3>
                <p>Share your study notes with others.</p>
                <a href="show_uploaded.php">Uploaded Note</a>
            </div>
        </section>
    </main>

    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
