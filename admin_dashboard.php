<?php
session_start();
include("includes/db.php");
include("includes/redirect_helper.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    redirect("main_index#login");
    exit();
}

$role = $_SESSION['role'];

// ---------------------- Dashboard Queries ---------------------- //

// Notes
$pendingNotes = $conn->query("SELECT COUNT(*) as c FROM notes WHERE status='pending'")->fetch_assoc()['c'];

// Users (only for admin)
$userCount = ($role === 'admin')
    ? $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c']
    : 0;

// Resources
$pendingResources = $conn->query("SELECT COUNT(*) as c FROM resources WHERE status='pending'")->fetch_assoc()['c'];
$totalResources   = $conn->query("SELECT COUNT(*) as c FROM resources")->fetch_assoc()['c'];

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
    <title><?php echo ucfirst($role); ?> Dashboard - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=3.1">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <li class="active"><a href="<?php echo url('admin_dashboard.php'); ?>" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a href="<?php echo url('pending_notes.php'); ?>" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
            <li class="nav-item"><a href="<?php echo url('manage_resources.php'); ?>" class="nav-link"><i class="fa fa-folder-open me-2"></i>Manage Resources</a></li>
            
            <?php if ($role === 'admin') { ?>
                <li class="nav-item"><a href="<?php echo url('manage_users.php'); ?>" class="nav-link"><i class="fa fa-users me-2"></i>Users</a></li>
            <?php } ?>

            <li class="nav-item"><a href="<?php echo url('trending_subjects.php'); ?>" class="nav-link"><i class="fa fa-chart-bar me-2"></i>Analytics</a></li>

            <?php if ($role === 'admin') { ?>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
            <?php } ?>

            <li class="nav-item"><a href="<?php echo url('home.php'); ?>" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
            <li class="nav-item"><a href="<?php echo url('resources.php'); ?>" class="nav-link"><i class="fa fa-download me-2"></i>Browse Resources</a></li>
            <li class="nav-item"><a href="<?php echo url('show_uploaded.php'); ?>" class="nav-link"><i class="fa fa-upload me-2"></i>Uploaded Notes</a></li>
            <li class="nav-item"><a href="<?php echo url('settings.php'); ?>" class="nav-link"><i class="fa fa-cog me-2"></i>Settings</a></li>
        </ul>
        <div class="logout mt-auto px-3 pb-3">
            <a href="<?php echo url('logout.php'); ?>" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        <!-- Topbar -->
        <header class="topbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn text-white p-0 border-0">
                    <i class="fa fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-semibold">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark"><?php echo ucfirst($role); ?></span>
                <a href="<?php echo url('logout.php'); ?>" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </header>

        <!-- Dashboard Cards -->
        <div class="container">
            <div class="row g-4">
                <!-- Pending Notes -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📄 Pending Notes</h6>
                        <p class="metric"><?php echo $pendingNotes; ?></p>
                        <a href="<?php echo url('pending_notes.php'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>

                <!-- Pending Resources -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>🗂 Pending Resources</h6>
                        <p class="metric"><?php echo $pendingResources; ?></p>
                        <a href="<?php echo url('manage_resources.php'); ?>" class="stretched-link">Review Now</a>
                    </div>
                </div>

                <!-- Total Resources -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📘 Total Resources</h6>
                        <p class="metric"><?php echo $totalResources; ?></p>
                        <a href="<?php echo url('manage_resources.php'); ?>" class="stretched-link">View All</a>
                    </div>
                </div>

                <!-- Total Users (Admin Only) -->
                <?php if ($role === 'admin') { ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>👥 Total Users</h6>
                        <p class="metric"><?php echo $userCount; ?></p>
                        <a href="<?php echo url('manage_users.php'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>
                <?php } ?>

                <!-- Trending Courses -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📚 Trending Courses</h6>
                        <p class="metric"><?php echo count($trendingSubjects); ?> Courses</p>
                        <a href="<?php echo url('trending_subjects.php'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>

                <!-- Active Users -->
                <?php if ($role === 'admin') { ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>🔥 Active Users</h6>
                        <p class="metric"><?php echo count($activeUsers); ?> Users</p>
                        <a href="<?php echo url('active_users.php'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>

                <!-- Reports -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📑 Reports</h6>
                        <p class="metric">Generate</p>
                        <a href="#" class="stretched-link">View Details</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin_dashboard.js?v=3.1"></script>
</body>
</html>
