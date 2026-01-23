<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap + Fonts + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.1'); ?>">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap me-2"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <li class="active"><a href="<?php echo url('user/dashboard'); ?>" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
            <li><a href="<?php echo url('home/dashboard'); ?>" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
            <li><a href="<?php echo url('upload'); ?>" class="nav-link"><i class="fa fa-upload me-2"></i>Upload Notes</a></li>
            <li><a href="<?php echo url('leaderboard'); ?>" class="nav-link"><i class="fa fa-trophy me-2"></i>Leaderboard</a></li>
            <li><a href="<?php echo url('note/my_notes'); ?>" class="nav-link"><i class="fa fa-file me-2"></i>Uploaded Notes</a></li>
            <li><a href="<?php echo url('profile'); ?>" class="nav-link"><i class="fa fa-user me-2"></i>Profile</a></li>
        </ul>
        <div class="logout px-3 mt-auto pb-3">
            <a href="<?php echo url('logout'); ?>" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        <!-- Top Bar -->
        <header class="topbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn text-white p-0 border-0">
                    <i class="fa fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-semibold">Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark">Student</span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </header>

        <!-- Dashboard Cards -->
        <section class="container py-4">
            <div class="row g-4">
                <!-- Profile -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3 h-100">
                        <h6><i class="fa fa-user-circle me-2 text-primary"></i>Profile</h6>
                        <p class="metric"><?php echo intval($user['points']); ?> Pts</p>
                        <p class="text-muted small mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
                        <a href="<?php echo url('profile'); ?>" class="stretched-link">View Profile</a>
                    </div>
                </div>

                <!-- Uploaded Notes -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3 h-100">
                        <h6><i class="fa fa-upload me-2 text-success"></i>Your Notes</h6>
                        <p class="metric">Manage</p>
                        <p class="text-muted small mb-2">Share & help others</p>
                        <a href="<?php echo url('note/my_notes'); ?>" class="stretched-link">View Uploads</a>
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3 h-100">
                        <h6><i class="fa fa-trophy me-2 text-warning"></i>Leaderboard</h6>
                        <p class="metric">Compete</p>
                         <p class="text-muted small mb-2">See top contributors</p>
                        <a href="<?php echo url('leaderboard'); ?>" class="stretched-link">View Leaderboard</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=3.1'); ?>"></script>
</body>
</html>
