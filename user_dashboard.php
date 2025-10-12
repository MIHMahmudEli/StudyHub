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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap + Fonts + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=3.1">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap me-2"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column px-2">
            <li class="active"><a href="user_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
            <li><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
            <li><a href="upload.php" class="nav-link"><i class="fa fa-upload me-2"></i>Upload Notes</a></li>
            <li><a href="leaderboard.php" class="nav-link"><i class="fa fa-trophy me-2"></i>Leaderboard</a></li>
            <li><a href="show_uploaded.php" class="nav-link"><i class="fa fa-file me-2"></i>Uploaded Notes</a></li>
            <li><a href="profile.php" class="nav-link"><i class="fa fa-user me-2"></i>Profile</a></li>
        </ul>
        <div class="logout px-3 mt-auto pb-3">
            <a href="logout.php" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        <!-- Top Bar -->
        <header class="topbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn text-white p-0 border-0">
                    <i class="fa fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-semibold">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark">Student</span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </header>

        <!-- Dashboard Cards -->
        <section class="container py-4">
            <div class="row g-4">
                <!-- Profile -->
                <div class="col-md-6 col-lg-4">
                    <div class="dashboard-card p-4">
                        <h4 class="mb-2"><i class="fa fa-user-circle me-2 text-primary"></i>Profile</h4>
                        <p class="mb-1 fw-semibold"><?php echo htmlspecialchars($user['name']); ?></p>
                        <p class="text-muted mb-1">📧 <?php echo htmlspecialchars($user['email']); ?></p>
                        <p class="mb-3">⭐ Points: <?php echo intval($user['points']); ?></p>
                        <a href="profile.php" class="btn btn-outline-primary btn-sm">View / Edit Profile</a>
                    </div>
                </div>

                <!-- Uploaded Notes -->
                <div class="col-md-6 col-lg-4">
                    <div class="dashboard-card p-4">
                        <h4 class="mb-2"><i class="fa fa-upload me-2 text-success"></i>Your Notes</h4>
                        <p class="text-muted mb-3">Share your notes and help others learn better.</p>
                        <a href="show_uploaded.php" class="btn btn-outline-success btn-sm">View Uploaded Notes</a>
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="col-md-6 col-lg-4">
                    <div class="dashboard-card p-4">
                        <h4 class="mb-2"><i class="fa fa-trophy me-2 text-warning"></i>Leaderboard</h4>
                        <p class="text-muted mb-3">See top contributors and challenge yourself!</p>
                        <a href="leaderboard.php" class="btn btn-outline-warning btn-sm">View Leaderboard</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin_dashboard.js?v=3.1"></script>
</body>
</html>
