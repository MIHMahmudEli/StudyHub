<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Active Users - StudyHub Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Custom Styles -->
<link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.1'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<style>
    .user-card-table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .table thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 1rem;
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem;
        color: #334155;
    }
    .rank-badge {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .rank-1 { background-color: #fee2e2; color: #ef4444; }
    .rank-2 { background-color: #ffedd5; color: #f97316; }
    .rank-3 { background-color: #fef9c3; color: #eab308; }
</style>
</head>
<body>
<?php $activePage = 'active_users'; ?>
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
        
        <?php if (isset($role) && $role === 'admin') { ?>
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

            <li class="<?php echo ($activePage === 'awards') ? 'active' : ''; ?>">
                <a href="<?php echo url('admin/awards'); ?>" class="nav-link"><i class="fa fa-award"></i>Awards & Certificates</a>
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

<!-- Main -->
<main class="main-content flex-grow-1">
    <!-- Topbar -->
    <header class="topbar d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold">Active Users</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <section class="container-fluid py-2 px-3 px-lg-5">
        
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-2 gap-3">
            <!-- <div class="d-none d-md-block">
                <h4 class="fw-bold text-dark mb-1">Most Active Users</h4>
                <p class="text-muted small mb-0">Top users by activity.</p>
            </div> -->
            
             <!-- Tabs -->
            <ul class="nav nav-pills w-100 w-md-auto d-flex gap-2 justify-content-between bg-white p-1 rounded-pill shadow-sm border" id="activeUserTabs" role="tablist">
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link w-100 active rounded-pill fw-medium py-2 small-mobile" id="data-today-tab" data-bs-toggle="pill" data-bs-target="#data-today" type="button" role="tab">Today</button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link w-100 rounded-pill fw-medium py-2 small-mobile" id="data-all-tab" data-bs-toggle="pill" data-bs-target="#data-all" type="button" role="tab">All Time</button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="activeUserTabsContent">
            
            <!-- Today Tab -->
            <div class="tab-pane fade show active" id="data-today" role="tabpanel">
                <div class="user-card-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>User Name</th>
                                    <th>Activity</th>
                                    <th class="d-none d-md-table-cell">Last Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($todayActive)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fa fa-clock fa-2x mb-3"></i>
                                            <p>No activity recorded today.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($todayActive as $index => $u): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            $rank = $index + 1;
                                            $rankClass = 'rank-' . $rank;
                                            ?>
                                            <div class="rank-badge <?php echo $rankClass; ?>">
                                                <?php echo $rank; ?>
                                            </div>
                                        </td>
                                        <td onclick="document.getElementById('m-time-today-<?php echo $index; ?>').classList.toggle('d-none')" style="cursor: pointer;">
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($u['name']); ?> <i class="fa fa-caret-down text-muted ms-1 d-md-none" style="font-size: 0.7rem;"></i></div>
                                            <!-- Mobile Toggle Time -->
                                            <div id="m-time-today-<?php echo $index; ?>" class="small text-muted d-none d-md-none mt-1 bg-light p-1 rounded">
                                                <i class="fa fa-clock me-1"></i> <?php echo date('h:i A', strtotime($u['last_active'])); ?> (Today)
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 small-badge">
                                                <?php echo number_format($u['activity']); ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted d-none d-md-table-cell">
                                            <div class="text-dark fw-medium"><?php echo date('h:i A', strtotime($u['last_active'])); ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Today</div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- All Time Tab -->
            <div class="tab-pane fade" id="data-all" role="tabpanel">
                <div class="user-card-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>User Name</th>
                                    <th>Activity</th>
                                    <th class="d-none d-md-table-cell">Last Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($allTimeActive)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fa fa-history fa-2x mb-3"></i>
                                            <p>No activity data available.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allTimeActive as $index => $u): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            $rank = $index + 1;
                                            $rankClass = 'rank-' . $rank;
                                            ?>
                                            <div class="rank-badge <?php echo $rankClass; ?>">
                                                <?php echo $rank; ?>
                                            </div>
                                        </td>
                                        <td onclick="document.getElementById('m-time-all-<?php echo $index; ?>').classList.toggle('d-none')" style="cursor: pointer;">
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($u['name']); ?> <i class="fa fa-caret-down text-muted ms-1 d-md-none" style="font-size: 0.7rem;"></i></div>
                                            <!-- Mobile Toggle Time -->
                                            <div id="m-time-all-<?php echo $index; ?>" class="small text-muted d-none d-md-none mt-1 bg-light p-1 rounded">
                                                <i class="fa fa-clock me-1"></i> <?php echo date('M d, Y', strtotime($u['last_active'])); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 small-badge">
                                                <?php echo number_format($u['activity']); ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted d-none d-md-table-cell">
                                            <div class="text-dark fw-medium"><?php echo date('M d, Y', strtotime($u['last_active'])); ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?php echo date('h:i A', strtotime($u['last_active'])); ?></div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/admin_dashboard.js?v=3.1'); ?>"></script>
</body>
</html>
