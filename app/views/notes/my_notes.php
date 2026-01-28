<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Uploaded Notes - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/show_uploaded.css?v=3.0'); ?>">

    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
    <style>
        .note-card-custom {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0,0,0,0.08);
            background: #fff;
            border-radius: 12px;
        }
        .note-card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
            border-color: #3b82f6 !important;
        }
        .note-card-custom .form-control {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .note-card-custom .form-control:focus {
            background-color: #fff;
            box-shadow: none;
            border-color: #3b82f6;
        }
    </style>
</head>
<body>
    <?php $activePage = 'my_notes'; ?>
    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap me-2"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column" id="sidebarAccordion">
            <?php if ($role === 'student') { ?>
                <!-- Core -->
                <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
                    <a href="<?php echo url('user/dashboard'); ?>" class="nav-link">
                        <div class="nav-link-content">
                            <i class="fa fa-home main-icon"></i><span>Dashboard</span>
                        </div>
                    </a>
                </li>

                <!-- Discovery -->
                <li>
                    <a href="#discoveryMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <div class="nav-link-content">
                            <i class="fa fa-compass main-icon"></i><span>Explore</span>
                        </div>
                        <i class="fa fa-chevron-right arrow-icon"></i>
                    </a>
                    <div class="collapse <?php echo in_array($activePage, ['browse_notes', 'browse_resources', 'leaderboard']) ? 'show' : ''; ?>" id="discoveryMenu" data-bs-parent="#sidebarAccordion">
                        <ul class="sub-menu">
                            <li><a href="<?php echo url('home/dashboard'); ?>" class="nav-link">Notes</a></li>
                            <li><a href="<?php echo url('resources'); ?>" class="nav-link">Resources</a></li>
                            <li><a href="<?php echo url('home/dashboard'); ?>?bookmarks=1" class="nav-link">Bookmarks</a></li>
                            <li><a href="<?php echo url('leaderboard'); ?>" class="nav-link">Leaderboard</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Uploads -->
                <li>
                    <a href="#uploadMenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true">
                        <div class="nav-link-content">
                            <i class="fa fa-cloud-upload-alt main-icon"></i><span>Manage</span>
                        </div>
                        <i class="fa fa-chevron-right arrow-icon"></i>
                    </a>
                    <div class="collapse show" id="uploadMenu" data-bs-parent="#sidebarAccordion">
                        <ul class="sub-menu">
                            <li><a href="<?php echo url('upload'); ?>" class="nav-link">Upload Notes</a></li>
                            <li class="active"><a href="<?php echo url('note/my_notes'); ?>" class="nav-link">My Notes</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Account -->
                <li>
                    <a href="<?php echo url('profile'); ?>" class="nav-link">
                        <div class="nav-link-content">
                            <i class="fa fa-user main-icon"></i><span>Profile</span>
                        </div>
                    </a>
                </li>
            <?php } else { ?>
                <!-- Admin/Moderator Sidebar -->
                <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/dashboard'); ?>" class="nav-link">
                        <div class="nav-link-content">
                            <i class="fa fa-home main-icon"></i><span>Dashboard</span>
                        </div>
                    </a>
                </li>

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

                <li>
                    <a href="#navigationMenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="true">
                        <div class="nav-link-content">
                            <i class="fa fa-compass main-icon"></i><span>Quick Links</span>
                        </div>
                        <i class="fa fa-chevron-right arrow-icon"></i>
                    </a>
                    <div class="collapse show" id="navigationMenu" data-bs-parent="#sidebarAccordion">
                        <ul class="sub-menu">
                            <li><a href="<?php echo url('home/dashboard'); ?>" class="nav-link">Browse Notes</a></li>
                            <li><a href="<?php echo url('resources'); ?>" class="nav-link">Browse Resources</a></li>
                            <li class="active"><a href="<?php echo url('note/my_notes'); ?>" class="nav-link">My Notes</a></li>
                        </ul>
                    </div>
                </li>

                <li class="<?php echo ($activePage === 'settings') ? 'active' : ''; ?>">
                    <a href="<?php echo url('settings'); ?>" class="nav-link">
                        <div class="nav-link-content">
                            <i class="fa fa-cog main-icon"></i><span>Settings</span>
                        </div>
                    </a>
                </li>
            <?php } ?>
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
                    <i class="fa fa-bars fa-lg"></i>
                </button>
                <h5 class="mb-0 fw-semibold d-none d-sm-block">📘 My Uploaded Notes</h5>
                <h5 class="mb-0 fw-semibold d-block d-sm-none">📘 My Notes</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm"><?php echo ucfirst($role); ?></span>
            </div>
        </header>

        <!-- Notes Grid -->
        <div class="container">
            <div class="row g-4">
                <?php if (empty($notes)) { ?>
                    <div class="col-12">
                        <p class="text-center text-muted py-4">You haven’t uploaded any notes yet.</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($notes as $note) { ?>
                        <div class="col-md-6 col-lg-4">
                             <!-- Using a unique ID for each form mainly isn't needed if not using JS extensively but harmless -->
                            <div class="card p-3 h-100 shadow-sm note-card-custom">
                                <form method="post" action="<?php echo url('note/update'); ?>">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-secondary small text-uppercase">Title</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($note['title']); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-secondary small text-uppercase">Subject</label>
                                        <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($note['subject']); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-secondary small text-uppercase">Course Code</label>
                                        <input type="text" name="course_code" class="form-control" value="<?php echo htmlspecialchars($note['course_code']); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-secondary small text-uppercase">Description</label>
                                        <textarea name="description" class="form-control" rows="2"><?php echo htmlspecialchars($note['description']); ?></textarea>
                                    </div>
                                    <p class="small text-muted mb-1 mt-3">Status: <span class="badge bg-secondary"><?php echo htmlspecialchars($note['status']); ?></span></p>
                                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">Save Changes</button>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo url('preview/note'); ?>?id=<?php echo $note['id']; ?>&track=my_notes" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Preview"><i class="fa fa-eye text-info"></i></a>
                                            <a href="<?php echo url('note/my_notes'); ?>?delete=<?php echo $note['id']; ?>" onclick="return confirm('Are you sure you want to delete this note?');" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Delete"><i class="fa fa-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
</body>
</html>
