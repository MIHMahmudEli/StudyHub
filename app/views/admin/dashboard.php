<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($role); ?> Dashboard - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
</head>
<body>
    <?php $activePage = 'dashboard'; ?>
    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap me-2"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column" id="sidebarAccordion">
            <!-- Core -->

            <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo url('admin/dashboard'); ?>" class="nav-link">
                    <div class="nav-link-content">
                        <i class="fa fa-home main-icon"></i><span>Dashboard</span>
                    </div>
                </a>
            </li>

            <!-- Content -->

            <li>
                <a href="#contentMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                    <div class="nav-link-content">
                        <i class="fa fa-folder-open main-icon"></i><span>Management</span>
                    </div>
                    <i class="fa fa-chevron-right arrow-icon"></i>
                </a>
                <div class="collapse <?php echo in_array($activePage, ['pending_notes', 'manage_resources']) ? 'show' : ''; ?>" id="contentMenu" data-bs-parent="#sidebarAccordion">
                    <ul class="sub-menu">
                        <li class="<?php echo ($activePage === 'pending_notes') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/pending_notes'); ?>" class="nav-link">Pending Notes</a>
                        </li>
                        <li class="<?php echo ($activePage === 'manage_resources') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/manage_resources'); ?>" class="nav-link">Resources</a>
                        </li>
                    </ul>
                </div>
            </li>

            <?php if ($role === 'admin') { ?>
                <!-- Governance -->

                <li>
                    <a href="#governanceMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <div class="nav-link-content">
                            <i class="fa fa-shield-halved main-icon"></i><span>Control</span>
                        </div>
                        <i class="fa fa-chevron-right arrow-icon"></i>
                    </a>
                    <div class="collapse <?php echo in_array($activePage, ['users', 'active_users']) ? 'show' : ''; ?>" id="governanceMenu" data-bs-parent="#sidebarAccordion">
                        <ul class="sub-menu">
                            <li class="<?php echo ($activePage === 'users') ? 'active' : ''; ?>">
                                <a href="<?php echo url('admin/users'); ?>" class="nav-link">User List</a>
                            </li>
                            <li class="<?php echo ($activePage === 'active_users') ? 'active' : ''; ?>">
                                <a href="<?php echo url('admin/active_users'); ?>" class="nav-link">Active Sessions</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Insights -->

                <li>
                    <a href="#insightsMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <div class="nav-link-content">
                            <i class="fa fa-chart-line main-icon"></i><span>Analytics</span>
                        </div>
                        <i class="fa fa-chevron-right arrow-icon"></i>
                    </a>
                    <div class="collapse <?php echo in_array($activePage, ['analytics', 'resource_analytics', 'reports', 'awards']) ? 'show' : ''; ?>" id="insightsMenu" data-bs-parent="#sidebarAccordion">
                        <ul class="sub-menu">
                            <li class="<?php echo ($activePage === 'analytics') ? 'active' : ''; ?>">
                                <a href="<?php echo url('admin/analytics'); ?>" class="nav-link">Statistics</a>
                            </li>
                            <li class="<?php echo ($activePage === 'resource_analytics') ? 'active' : ''; ?>">
                                <a href="<?php echo url('admin/resource_analytics'); ?>" class="nav-link">Resources</a>
                            </li>
                            <li class="<?php echo ($activePage === 'reports') ? 'active' : ''; ?>">
                                <a href="<?php echo url('admin/reports'); ?>" class="nav-link">Reports</a>
                            </li>
                            <li class="<?php echo ($activePage === 'awards') ? 'active' : ''; ?>">
                                <a href="<?php echo url('admin/awards'); ?>" class="nav-link">Awards</a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php } ?>

            <!-- Navigation -->

            <li>
                <a href="#navigationMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                    <div class="nav-link-content">
                        <i class="fa fa-compass main-icon"></i><span>Quick Links</span>
                    </div>
                    <i class="fa fa-chevron-right arrow-icon"></i>
                </a>
                <div class="collapse <?php echo in_array($activePage, ['browse_notes', 'browse_resources', 'my_notes']) ? 'show' : ''; ?>" id="navigationMenu" data-bs-parent="#sidebarAccordion">
                    <ul class="sub-menu">
                        <li><a href="<?php echo url('home/dashboard'); ?>" class="nav-link">Browse Notes</a></li>
                        <li><a href="<?php echo url('resources'); ?>" class="nav-link">Browse Resources</a></li>
                        <li><a href="<?php echo url('note/my_notes'); ?>" class="nav-link">My Notes</a></li>
                    </ul>
                </div>
            </li>

            <!-- Account -->

            <li class="<?php echo ($activePage === 'settings') ? 'active' : ''; ?>">
                <a href="<?php echo url('settings'); ?>" class="nav-link">
                    <div class="nav-link-content">
                        <i class="fa fa-cog main-icon"></i><span>Settings</span>
                    </div>
                </a>
            </li>
            <li class="logout">
                <a href="<?php echo url('logout'); ?>" class="nav-link">
                    <div class="nav-link-content">
                        <i class="fa fa-sign-out-alt main-icon"></i><span>Logout</span>
                    </div>
                </a>
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
                <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
                </a>
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

                <!-- Awards -->
                <div class="col-md-6 col-lg-4">
                    <div class="card dash-card p-3">
                        <h6>🏆 Awards</h6>
                        <p class="metric">A & C</p>
                        <a href="<?php echo url('admin/awards'); ?>" class="stretched-link">View Details</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
</body>
</html>
