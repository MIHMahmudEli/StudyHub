<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Notes - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
</head>
<body>

    <?php $activePage = 'pending_notes'; ?>
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
                <h5 class="mb-0 fw-semibold d-none d-sm-block">Pending Notes Review</h5>
                <h5 class="mb-0 fw-semibold d-block d-sm-none">Pending</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark"><?php echo ucfirst($role ?? 'Admin'); ?></span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
                </a>
            </div>
    </header>

    <!-- Toast Notification System -->
    <?php if (!empty($message) || !empty($error)): ?>
    <div class="toast-notification" id="pendingToast">
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
            <button type="button" class="toast-close" onclick="closePendingToast()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-progress">
            <div class="toast-progress-bar" style="background: <?php echo !empty($message) ? 'linear-gradient(90deg, #10b981 0%, #059669 100%)' : 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)'; ?>"></div>
        </div>
    </div>
    <?php endif; ?>

        <div class="container-fluid">
            <?php if (empty($notes)): ?>
                <div class="alert alert-info text-center py-5">
                    <i class="fa fa-folder-open fa-3x mb-3"></i>
                    <h4>No valid pending notes found.</h4>
                    <p>Good job! All notes have been reviewed.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive shadow-sm rounded bg-white p-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th class="d-none d-md-table-cell">Subject</th>
                                <th class="d-none d-md-table-cell">Uploader</th>
                                <th class="d-none d-md-table-cell">Date</th>
                                <th>File</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notes as $row): ?>
                                <tr>
                                    <td style="max-width: 200px;">
                                        <div class="fw-bold text-truncate"><?php echo htmlspecialchars($row['title']); ?></div>
                                        <small class="text-muted d-block text-truncate"><?php echo htmlspecialchars($row['course_code']); ?></small>
                                    </td>
                                    <td class="d-none d-md-table-cell"><span class="badge bg-secondary"><?php echo htmlspecialchars($row['subject']); ?></span></td>
                                    <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($row['uploader_name']); ?></td>
                                    <td class="d-none d-md-table-cell"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <?php 
                                        $icon = 'fa-file';
                                        $type = strtolower($row['file_type']);
                                        if ($type == 'pdf') $icon = 'fa-file-pdf text-danger';
                                        elseif (in_array($type, ['jpg','png'])) $icon = 'fa-file-image text-success';
                                        ?>
                                        <a href="<?php echo url('preview/note'); ?>?id=<?php echo $row['id']; ?>&track=admin" class="text-decoration-none btn btn-light btn-sm shadow-sm">
                                            <i class="fa <?php echo $icon; ?> fa-lg"></i><span class="d-none d-sm-inline ms-1">Preview</span>
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="<?php echo url('admin/note/approve'); ?>?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" title="Approve"><i class="fa fa-check"></i></a>
                                            <a href="<?php echo url('admin/note/reject'); ?>?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Reject"><i class="fa fa-times"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
    <script>
        function closePendingToast() {
            const toast = document.getElementById('pendingToast');
            if (toast) {
                toast.classList.add('toast-hide');
                setTimeout(() => { toast.remove(); }, 400);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const pendingToast = document.getElementById('pendingToast');
            if (pendingToast) {
                setTimeout(() => { pendingToast.classList.add('toast-show'); }, 100);
                setTimeout(() => { closePendingToast(); }, 5000);
                const progressBar = pendingToast.querySelector('.toast-progress-bar');
                if(progressBar) {
                    progressBar.style.animation = 'progress 5s linear forwards';
                }
            }
        });
    </script>
</body>
</html>
