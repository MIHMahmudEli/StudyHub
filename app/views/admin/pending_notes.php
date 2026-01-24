<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Notes - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.5'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
</head>
<body>

    <?php $activePage = 'pending_notes'; ?>
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
    <script src="<?php echo asset('js/admin_dashboard.js?v=3.1'); ?>"></script>
</body>
</html>
