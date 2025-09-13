<?php
session_start();
include("includes/db.php");

// Security: only admin allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php#login");
    exit();
}

// Example queries
$pendingCount = $conn->query("SELECT COUNT(*) as c FROM notes WHERE status='pending'")
                     ->fetch_assoc()['c'];

$userCount    = $conn->query("SELECT COUNT(*) as c FROM users")
                     ->fetch_assoc()['c'];

// Trending subjects (top 10)
$trendingSubjects = $conn->query("SELECT subject, COUNT(*) as c 
                                  FROM notes 
                                  GROUP BY subject 
                                  ORDER BY c DESC 
                                  LIMIT 10")->fetch_all(MYSQLI_ASSOC);

// Active users (top 10 by activity count)
$activeUsers = $conn->query("SELECT u.name, COUNT(e.id) as activity 
                             FROM events e
                             LEFT JOIN users u ON e.user_id = u.id
                             GROUP BY e.user_id 
                             ORDER BY activity DESC 
                             LIMIT 10")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - StudyHub</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
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
            <li class="active"><a href="admin_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="pending_notes.php"><i class="fa fa-file"></i> <span>Notes</span></a></li>
            <li><a href="manage_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <li><a href="trending_subjects.php"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>
            <li><a href="#"><i class="fa fa-file-alt"></i> <span>Reports</span></a></li>
            <li><a href="settings.php"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php" ><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
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
                <span class="role">Admin</span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </header>

        <!-- Dashboard Cards -->
        <section class="cards">
            <div class="card">
                <h3>📄 Pending Notes</h3>
                <p class="metric"><?php echo $pendingCount; ?></p>
                <a href="pending_notes.php">View Details</a>
            </div>
            <div class="card">
                <h3>👥 Total Users</h3>
                <p class="metric"><?php echo $userCount; ?></p>
                <a href="manage_users.php">View Details</a>
            </div>
            <div class="card">
                <h3>📚 Trending Courses</h3>
                <p class="metric"><?php echo count($trendingSubjects); ?> Courses</p>
                <a href="trending_subjects.php">View Details</a> 
            </div>
            <div class="card">
                <h3>🔥 Active Users</h3>
                <p class="metric"><?php echo count($activeUsers); ?> Users</p>
                <a href="active_users.php">View Details</a>
            </div>
            <div class="card">
                <h3>📑 Reports</h3>
                <p class="metric">Generate</p>
                <a href="#">View Details</a>
            </div>
        </section>
    </main>

    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
