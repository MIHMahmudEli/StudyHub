<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users - StudyHub Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Custom Styles -->
<link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
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
    .role-badge {
        font-weight: 500;
        padding: 0.35em 0.8em;
        border-radius: 6px;
        font-size: 0.8rem;
    }
    .role-admin { background-color: #fee2e2; color: #991b1b; }
    .role-moderator { background-color: #fef3c7; color: #92400e; }
    .role-student { background-color: #e0f2fe; color: #075985; }
    
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background-color: #fff;
    }
    .btn-action:hover {
        background-color: #f1f5f9;
        transform: translateY(-2px);
    }
    .dropdown-menu {
        border-radius: 12px;
        padding: 8px;
        min-width: 200px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
        animation: fadeIn 0.15s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dropdown-item {
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
        transition: all 0.1s;
    }
    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: #0f172a;
        transform: translateX(4px);
    }
    .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 8px;
        opacity: 0.8;
    }
    .dropdown-header {
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 700;
        padding: 8px 15px 4px;
    }
    .dropdown-divider {
        margin: 6px 0;
        border-top: 1px solid #e2e8f0;
    }
</style>
</head>
<body>
<?php $activePage = 'users'; ?>
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

<!-- Main -->
<main class="main-content flex-grow-1">
    <!-- Topbar -->
    <header class="topbar d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold d-none d-sm-block">User Management</h5>
            <h5 class="mb-0 fw-semibold d-block d-sm-none">Users</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                 <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <section class="container-fluid py-2 px-lg-5">
        
        <!-- Stats / Header -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-none d-md-block">
                <h4 class="fw-bold text-dark mb-1">Users</h4>
                <p class="text-muted small mb-0">Manage system users, moderators, and admins.</p>
            </div>
            
            <form method="get" class="row g-2 align-items-center w-100 w-md-auto">
                <div class="col-12">
                    <div class="input-group bg-white rounded-pill border shadow-sm overflow-hidden p-1">
                        <span class="input-group-text bg-transparent border-0 pe-1 ps-3"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-0 shadow-none ps-2" placeholder="Search users..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="font-size: 0.95rem;">
                        <button type="submit" class="btn btn-primary rounded-circle d-md-none m-1 shadow-sm" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-arrow-right"></i></button>
                        <button type="submit" class="btn btn-primary px-3 d-none d-md-block rounded-pill py-1 my-1 me-1 shadow-sm">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="user-card-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>User Info</th>
                            <th>Role</th>
                            <th class="d-none d-md-table-cell">Points</th>
                            <th class="d-none d-md-table-cell">Status</th>
                            <th class="d-none d-md-table-cell">Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa fa-users-slash fa-2x mb-3"></i>
                                    <p>No users found matching your search.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold flex-shrink-0" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                        </div>
                                        <div style="min-width: 0;">
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($u['name']); ?></div>
                                            <div class="small text-muted text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($u['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $roleClass = match($u['role']) { 
                                        'admin' => 'role-admin', 
                                        'moderator' => 'role-moderator', 
                                        default => 'role-student' 
                                    }; 
                                    ?>
                                    <span class="role-badge <?php echo $roleClass; ?>"><?php echo ucfirst($u['role']); ?></span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="text-dark fw-medium"><i class="fa fa-star text-warning small me-1"></i><?php echo number_format($u['points']); ?></span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php if ($u['verified']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted d-none d-md-table-cell">
                                    <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border btn-action" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v text-secondary"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li><h6 class="dropdown-header">Change Role</h6></li>
                                            <li>
                                                <form method="post" action="<?php echo url('admin/user/role'); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                    <input type="hidden" name="role" value="admin">
                                                    <button class="dropdown-item" <?php echo $u['role'] === 'admin' ? 'disabled' : ''; ?>><i class="fa fa-shield-halved text-danger me-2"></i> Make Admin</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="post" action="<?php echo url('admin/user/role'); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                    <input type="hidden" name="role" value="moderator">
                                                    <button class="dropdown-item" <?php echo $u['role'] === 'moderator' ? 'disabled' : ''; ?>><i class="fa fa-user-shield text-warning me-2"></i> Make Moderator</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="post" action="<?php echo url('admin/user/role'); ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                    <input type="hidden" name="role" value="student">
                                                    <button class="dropdown-item" <?php echo $u['role'] === 'student' ? 'disabled' : ''; ?>><i class="fa fa-user-graduate text-primary me-2"></i> Make Student</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="<?php echo url('admin/user/delete'); ?>?id=<?php echo $u['id']; ?>" class="dropdown-item text-danger" onclick="return confirm('Are you sure? This action cannot be undone.');">
                                                    <i class="fa fa-trash me-2"></i> Delete User
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
</body>
</html>
