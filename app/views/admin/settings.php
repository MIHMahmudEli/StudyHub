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
    .settings-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .settings-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .settings-header {
        background: linear-gradient(to right, #f8fafc, #fff);
        padding: 20px 25px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .settings-header h5 {
        margin: 0;
        color: #1e293b;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        font-size: 0.95rem;
    }
    .form-control:focus {
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
    }
    .btn-modern {
        padding: 12px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        <div class="row g-4">
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
                <div class="toast-progress" style="background: <?php echo !empty($message) ? 'linear-gradient(90deg, #10b981 0%, #059669 100%)' : 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)'; ?>"></div>
            </div>
            <?php endif; ?>
                
            <!-- Update Name -->
            <div class="col-lg-6 col-md-12">
                <div class="settings-card h-100 d-flex flex-column">
                    <div class="settings-header">
                        <h5><i class="fa fa-user-pen text-primary me-2"></i>Update Name</h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <form method="post" action="<?php echo url('settings/update_name'); ?>" class="d-flex flex-column h-100">
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">New Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-modern mt-auto">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="col-lg-6 col-md-12">
                <div class="settings-card h-100 d-flex flex-column">
                     <div class="settings-header">
                        <h5><i class="fa fa-shield-halved text-warning me-2"></i>Change Password</h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <form method="post" action="<?php echo url('settings/update_password'); ?>" class="d-flex flex-column h-100">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                                <div class="form-text text-muted small">
                                    <i class="fa fa-info-circle me-1"></i> Min 8 chars, uppercase, lowercase, number.
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 text-white btn-modern mt-auto">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Demote Moderators -->
            <?php if ($role === 'admin') { ?>
            <div class="col-12">
                <div class="settings-card">
                     <div class="settings-header">
                        <h5><i class="fa fa-users-gear text-danger me-2"></i>Manage Moderators</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($moderators)) { ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-user-slash fa-2x mb-2"></i>
                                <p>No moderators found.</p>
                            </div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table align-middle table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 text-secondary text-uppercase small fw-bold ps-4">Name</th>
                                            <th class="border-0 text-secondary text-uppercase small fw-bold">Email</th>
                                            <th class="border-0 text-secondary text-uppercase small fw-bold text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        <?php foreach ($moderators as $mod) { ?>
                                            <tr>
                                                <td class="ps-4 fw-medium"><?php echo htmlspecialchars($mod['name']); ?></td>
                                                <td class="text-muted"><?php echo htmlspecialchars($mod['email']); ?></td>
                                                <td class="text-center">
                                                    <form method="post" action="<?php echo url('settings/demote'); ?>" class="d-inline">
                                                        <input type="hidden" name="mod_id" value="<?php echo $mod['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-modern px-3">
                                                            <i class="fa fa-user-minus me-1"></i> Demote
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
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
            const progressBar = settingsToast.querySelector('.toast-progress');
            if(progressBar) {
                progressBar.style.animation = 'progress 5s linear forwards';
            }
        }
    });
</script>

<style>
    /* Modern Toast Notification Styles */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 350px;
        max-width: 400px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fffe 100%);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transform: translateX(450px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .toast-notification.toast-show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .toast-notification.toast-hide {
        transform: translateX(450px);
        opacity: 0;
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        position: relative;
    }
    
    .toast-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .toast-body {
        flex: 1;
    }
    
    .toast-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    
    .toast-message {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }
    
    .toast-close {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(107, 114, 128, 0.1);
        border-radius: 8px;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    
    .toast-close:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #374151;
        transform: rotate(90deg);
    }
    
    .toast-progress {
        height: 4px;
        transform-origin: left;
        border-radius: 0 0 16px 16px;
    }
    
    @keyframes progress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }
    
    @keyframes bounceIn {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @media (max-width: 768px) {
        .toast-notification {
            top: 10px; right: 10px; left: 10px;
            min-width: auto; max-width: none;
            transform: translateY(-150px);
        }
        .toast-notification.toast-show {
            transform: translateY(0);
        }
        .toast-notification.toast-hide {
            transform: translateY(-150px);
        }
    }
</style>
</body>
</html>
