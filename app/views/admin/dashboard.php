<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($role); ?> Dashboard - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.1'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
</head>
<body>
    <?php $activePage = 'dashboard'; ?>
    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo url('admin/dashboard'); ?>" class="nav-link"><i class="fa fa-house"></i>Dashboard</a>
            </li>
            <li class="<?php echo ($activePage === 'pending_notes') ? 'active' : ''; ?>">
                <a href="<?php echo url('admin/pending_notes'); ?>" class="nav-link"><i class="fa fa-file-lines"></i>Pending Notes</a>
            </li>
            <li class="<?php echo ($activePage === 'manage_resources') ? 'active' : ''; ?>">
                <a href="<?php echo url('admin/manage_resources'); ?>" class="nav-link"><i class="fa fa-folder"></i>Manage Resources</a>
            </li>
            
            <?php if ($role === 'admin') { ?>
                <li class="<?php echo ($activePage === 'users') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/users'); ?>" class="nav-link"><i class="fa fa-users"></i>Users</a>
                </li>

                <li class="<?php echo ($activePage === 'active_users') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/active_users'); ?>" class="nav-link"><i class="fa fa-fire"></i>Active Users</a>
                </li>

                <li class="<?php echo ($activePage === 'analytics') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/analytics'); ?>" class="nav-link"><i class="fa fa-chart-column"></i>Analytics</a>
                </li>

                <li class="<?php echo ($activePage === 'reports') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/reports'); ?>" class="nav-link"><i class="fa fa-file-invoice"></i>Platform Reports</a>
                </li>

            <?php } ?>

            <li class="<?php echo ($activePage === 'browse_notes') ? 'active' : ''; ?>">
                <a href="<?php echo url('home/dashboard'); ?>" class="nav-link"><i class="fa fa-book-open"></i>Browse Notes</a>
            </li>
            <li class="<?php echo ($activePage === 'browse_resources') ? 'active' : ''; ?>">
                <a href="<?php echo url('resources'); ?>" class="nav-link"><i class="fa fa-download"></i>Browse Resources</a>
            </li>
            <li class="<?php echo ($activePage === 'my_notes') ? 'active' : ''; ?>">
                <a href="<?php echo url('note/my_notes'); ?>" class="nav-link"><i class="fa fa-upload"></i>Uploaded Notes</a>
            </li>
            <li class="<?php echo ($activePage === 'settings') ? 'active' : ''; ?>">
                <a href="<?php echo url('settings'); ?>" class="nav-link"><i class="fa fa-gear"></i>Settings</a>
            </li>
        </ul>
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
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
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
                        <a href="<?php echo url('admin/pending_notes'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>

                <!-- Total Resources -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📘 Total Resources</h6>
                        <p class="metric"><?php echo $totalResources; ?></p>
                        <a href="<?php echo url('admin/manage_resources'); ?>" class="stretched-link">View All</a>
                    </div>
                </div>

                <!-- Total Users (Admin Only) -->
                <?php if ($role === 'admin') { ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>👥 Total Users</h6>
                        <p class="metric"><?php echo $userCount; ?></p>
                        <a href="<?php echo url('admin/users'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>
                <?php } ?>

                <!-- Trending Resources -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>🔥 Trending Resources</h6>
                        <p class="metric"><?php echo count($trendingResources); ?> Resources</p>
                        <a href="<?php echo url('admin/resource_analytics'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>


                <!-- Trending Courses -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📚 Trending Courses</h6>
                        <p class="metric"><?php echo count($trendingSubjects); ?> Courses</p>
                        <a href="<?php echo url('admin/analytics'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>

                <!-- Active Users -->
                <?php if ($role === 'admin') { ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>🔥 Active Users</h6>
                        <p class="metric"><?php echo count($activeUsers); ?> Users</p>
                        <a href="<?php echo url('admin/active_users'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>

                <!-- Reports -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>📑 Reports</h6>
                        <p class="metric">Generate</p>
                        <a href="<?php echo url('admin/reports'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=3.1'); ?>"></script>
</body>
</html>
