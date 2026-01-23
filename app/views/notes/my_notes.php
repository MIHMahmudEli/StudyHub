<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Uploaded Notes - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.1'); ?>">
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
    <?php $activePage = 'my_notes'; ?>
    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <?php if ($role === 'student') { ?>
                <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
                    <a href="<?php echo url('user/dashboard'); ?>" class="nav-link"><i class="fa fa-house"></i>Dashboard</a>
                </li>
                <li class="<?php echo ($activePage === 'browse_notes') ? 'active' : ''; ?>">
                    <a href="<?php echo url('home/dashboard'); ?>" class="nav-link"><i class="fa fa-book-open"></i>Browse Notes</a>
                </li>
                <li class="<?php echo ($activePage === 'upload') ? 'active' : ''; ?>">
                    <a href="<?php echo url('upload'); ?>" class="nav-link"><i class="fa fa-upload"></i>Upload Notes</a>
                </li>
                <li class="<?php echo ($activePage === 'leaderboard') ? 'active' : ''; ?>">
                    <a href="<?php echo url('leaderboard'); ?>" class="nav-link"><i class="fa fa-trophy"></i>Leaderboard</a>
                </li>
                <li class="<?php echo ($activePage === 'my_notes') ? 'active' : ''; ?>">
                    <a href="<?php echo url('note/my_notes'); ?>" class="nav-link"><i class="fa fa-file"></i>Uploaded Notes</a>
                </li>
                <li class="<?php echo ($activePage === 'profile') ? 'active' : ''; ?>">
                    <a href="<?php echo url('profile'); ?>" class="nav-link"><i class="fa fa-user"></i>Profile</a>
                </li>
            <?php } else { ?>
                <!-- Admin/Moderator Sidebar -->
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

                <?php } ?>

                <li class="<?php echo ($activePage === 'browse_notes') ? 'active' : ''; ?>">
                    <a href="<?php echo url('home/dashboard'); ?>" class="nav-link"><i class="fa fa-book-open"></i>Browse Notes</a>
                </li>
                <li class="<?php echo ($activePage === 'browse_resources') ? 'active' : ''; ?>">
                    <a href="<?php echo url('resources'); ?>" class="nav-link"><i class="fa fa-download"></i>Browse Resources</a>
                </li>
                <li class="<?php echo ($activePage === 'my_notes') ? 'active' : ''; ?>">
                    <a href="<?php echo url('note/my_notes'); ?>" class="nav-link"><i class="fa fa-file-invoice"></i>Uploaded Notes</a>
                </li>
                <li class="<?php echo ($activePage === 'settings') ? 'active' : ''; ?>">
                    <a href="<?php echo url('settings'); ?>" class="nav-link"><i class="fa fa-gear"></i>Settings</a>
                </li>
            <?php } ?>
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
                <h5 class="mb-0 fw-semibold">📘 My Uploaded Notes</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark"><?php echo ucfirst($role); ?></span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">Logout</a>
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
                                            <a href="<?php echo url('preview/note'); ?>?id=<?php echo $note['id']; ?>&track=my_notes" target="_blank" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Preview"><i class="fa fa-eye text-info"></i></a>
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
    <script src="<?php echo asset('js/admin_dashboard.js?v=3.1'); ?>"></script>
</body>
</html>
