<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Settings - StudyHub</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Custom Styles -->
<link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<style>
    .settings-wrapper {
        max-width: 850px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    .settings-card {
        background: #ffffff;
        border-radius: 30px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 20px 60px -20px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        position: relative;
        margin-bottom: 40px;
    }

    .settings-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.15);
    }

    .settings-header {
        padding: 35px 45px;
        background: #ffffff;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .settings-header-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .bg-blue-soft { background: #eff6ff; color: #3b82f6; }
    .bg-amber-soft { background: #fffbeb; color: #f59e0b; }
    .bg-rose-soft { background: #fff1f2; color: #f43f5e; }

    .settings-header h5 {
        margin: 0;
        font-weight: 800;
        color: #1e293b;
        font-size: 1.35rem;
        letter-spacing: -0.02em;
    }

    .card-body-custom {
        padding: 45px;
    }

    .form-group-custom {
        margin-bottom: 30px;
    }

    .form-label-custom {
        font-size: 0.85rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
        display: block;
    }

    .input-wrapper-custom {
        position: relative;
    }

    .input-wrapper-custom i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
        transition: color 0.3s;
    }

    .form-control-custom {
        background-color: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 18px;
        padding: 15px 20px 15px 55px;
        font-size: 1rem;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control-custom:focus {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .form-control-custom:focus + i {
        color: #3b82f6;
    }

    .btn-premium {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: white;
        border: none;
        border-radius: 18px;
        padding: 18px 35px;
        font-weight: 700;
        font-size: 1rem;
        width: 100%;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 10px;
        box-shadow: 0 10px 25px -5px rgba(30, 41, 59, 0.3);
    }

    .btn-premium:hover {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.4);
    }

    /* Moderator List Styling */
    .mod-card-item {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }

    .mod-card-item:hover {
        transform: scale(1.01);
        background: #ffffff;
        box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.05);
    }

    .mod-avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
        flex-shrink: 0;
    }

    .mod-details {
        flex: 1;
        min-width: 0;
        margin-left: 15px;
    }

    .btn-action-demote {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #fee2e2;
        color: #ef4444;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .btn-action-demote:hover {
        background: #ef4444;
        color: white;
        transform: rotate(90deg) scale(1.1);
    }

    @media (max-width: 768px) {
        .settings-header { padding: 20px 25px; }
        .card-body-custom { padding: 20px; }
        .settings-card { border-radius: 25px; margin-bottom: 25px; }
        .settings-header h5 { font-size: 1.15rem; }
        .settings-header-icon { width: 42px; height: 42px; font-size: 1.25rem; }
        
        .mod-card-item { padding: 15px; }
        .mod-avatar-circle { width: 40px; height: 40px; font-size: 1rem; }
    }

    @media (max-width: 480px) {
        .container-fluid { padding-left: 15px !important; padding-right: 15px !important; }
        .settings-header { padding: 18px 20px; gap: 12px; }
        .card-body-custom { padding: 18px; }
        .mod-card-item { gap: 10px; }
        .btn-action-demote { width: 38px; height: 38px; }
    }
</style>
</head>
<body>
<?php $activePage = 'settings'; ?>
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
    <header class="topbar d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold">Settings</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </header>

    <!-- Settings Sections -->
    <section class="container-fluid py-4 px-lg-5">
        <div class="settings-wrapper">
            <!-- Toast Notification System -->
            <?php if (!empty($message) || !empty($error)): ?>
            <div class="toast-notification" id="settingsToast">
                <div class="toast-content">
                    <div class="toast-icon" style="background: <?php echo !empty($message) ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; ?>">
                        <i class="fas <?php echo !empty($message) ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    </div>
                    <div class="toast-body">
                        <h6 class="toast-title mb-1" style="color: <?php echo !empty($message) ? '#065f46' : '#991b1b'; ?>">
                            <?php echo !empty($message) ? 'Success!' : 'Error!'; ?>
                        </h6>
                        <p class="toast-message mb-0">
                            <?php echo !empty($message) ? htmlspecialchars($message) : htmlspecialchars($error); ?>
                        </p>
                    </div>
                    <button type="button" class="toast-close" onclick="closeSettingsToast()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-progress">
                    <div class="toast-progress-bar" style="background: <?php echo !empty($message) ? 'linear-gradient(90deg, #10b981 0%, #059669 100%)' : 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)'; ?>"></div>
                </div>
            </div>
            <?php endif; ?>
                
            <!-- Update Name Card -->
            <div class="settings-card">
                <div class="settings-header">
                    <div class="settings-header-icon bg-blue-soft">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5>Profile Identity</h5>
                        <p class="text-muted small mb-0">Manage how you appear on the platform</p>
                    </div>
                </div>
                <div class="card-body-custom">
                    <form method="post" action="<?php echo url('settings/update_name'); ?>">
                        <div class="form-group-custom">
                            <label class="form-label-custom">New Display Name</label>
                            <div class="input-wrapper-custom">
                                <input type="text" class="form-control-custom" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" placeholder="Enter new name" required>
                                <i class="fa fa-signature"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-premium">
                            <i class="fa fa-check-circle"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="settings-card">
                <div class="settings-header">
                    <div class="settings-header-icon bg-amber-soft">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5>Security & Privacy</h5>
                        <p class="text-muted small mb-0">Keep your account safe and secure</p>
                    </div>
                </div>
                <div class="card-body-custom">
                    <form method="post" action="<?php echo url('settings/update_password'); ?>">
                        <div class="row">
                            <div class="col-12 col-md-6 form-group-custom">
                                <label class="form-label-custom">Current Password</label>
                                <div class="input-wrapper-custom">
                                    <input type="password" name="current_password" class="form-control-custom" placeholder="••••••••" required>
                                    <i class="fa fa-shield-alt"></i>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 form-group-custom">
                                <label class="form-label-custom">New Password</label>
                                <div class="input-wrapper-custom">
                                    <input type="password" name="new_password" class="form-control-custom" placeholder="••••••••" required>
                                    <i class="fa fa-key"></i>
                                </div>
                            </div>
                            <div class="col-12 form-group-custom">
                                <label class="form-label-custom">Confirm New Password</label>
                                <div class="input-wrapper-custom">
                                    <input type="password" name="confirm_password" class="form-control-custom" placeholder="••••••••" required>
                                    <i class="fa fa-check-double"></i>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-premium">
                            <i class="fa fa-shield-check"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Demote Moderators -->
            <?php if ($role === 'admin') { ?>
            <div class="settings-card">
                <div class="settings-header">
                    <div class="settings-header-icon bg-rose-soft">
                        <i class="fa fa-user-shield"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5>Moderation Team</h5>
                        <p class="text-muted small mb-0">Manage moderator accounts and access</p>
                    </div>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($moderators)) { ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fa fa-users-slash fa-3x text-light"></i>
                            </div>
                            <h6 class="text-muted fw-bold">No moderators found.</h6>
                        </div>
                    <?php } else { ?>
                        <div class="mod-container">
                            <?php foreach ($moderators as $mod) { ?>
                                <div class="mod-card-item">
                                    <div class="mod-avatar-circle">
                                        <?php echo strtoupper(substr($mod['name'], 0, 1)); ?>
                                    </div>
                                    <div class="mod-details">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 1.05rem;"><?php echo htmlspecialchars($mod['name']); ?></div>
                                        <div class="text-muted small text-truncate"><?php echo htmlspecialchars($mod['email']); ?></div>
                                    </div>
                                    <form method="post" action="<?php echo url('settings/demote'); ?>" class="ms-2 ms-sm-3">
                                        <input type="hidden" name="mod_id" value="<?php echo $mod['id']; ?>">
                                        <button type="submit" class="btn-action-demote" title="Demote" onclick="return confirm('Demote this moderator?')">
                                            <i class="fa fa-user-minus"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>

<script>
    function closeSettingsToast() {
        const toast = document.getElementById('settingsToast');
        if (toast) {
            toast.classList.add('toast-hide');
            setTimeout(() => {
                toast.remove();
            }, 400);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const settingsToast = document.getElementById('settingsToast');
        if (settingsToast) {
            // Show with animation
            setTimeout(() => {
                settingsToast.classList.add('toast-show');
            }, 100);

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                closeSettingsToast();
            }, 5000);
            
            // Start progress animation
            const progressBar = settingsToast.querySelector('.toast-progress-bar');
            if(progressBar) {
                progressBar.style.animation = 'progress 5s linear forwards';
            }
        }
    });
</script>

</body>
</html>
