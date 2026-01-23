<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users - StudyHub Admin</title>
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
        <?php } ?>

        <li class="<?php echo ($activePage === 'active_users') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/active_users'); ?>" class="nav-link"><i class="fa fa-fire"></i>Active Users</a>
        </li>

        <li class="<?php echo ($activePage === 'analytics') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/analytics'); ?>" class="nav-link"><i class="fa fa-chart-column"></i>Analytics</a>
        </li>

        <li class="<?php echo ($activePage === 'reports') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/reports'); ?>" class="nav-link"><i class="fa fa-file-invoice"></i>Platform Reports</a>
        </li>

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
    <header class="topbar d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold">User Management</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </header>

    <!-- Content -->
    <section class="container-fluid py-4 px-lg-5">
        
        <!-- Stats / Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Users</h4>
                <p class="text-muted small mb-0">Manage system users, moderators, and admins.</p>
            </div>
            
            <form method="get" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="user-card-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>User Info</th>
                            <th>Role</th>
                            <th>Points</th>
                            <th>Status</th>
                            <th>Joined</th>
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
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($u['name']); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($u['email']); ?></div>
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
                                <td>
                                    <span class="text-dark fw-medium"><i class="fa fa-star text-warning small me-1"></i><?php echo number_format($u['points']); ?></span>
                                </td>
                                <td>
                                    <?php if ($u['verified']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted">
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
<script src="<?php echo asset('js/admin_dashboard.js?v=3.1'); ?>"></script>
</body>
</html>
