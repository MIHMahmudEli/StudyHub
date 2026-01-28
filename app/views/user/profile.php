<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
    
    <style>
        .profile-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .profile-header {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            padding: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .profile-header h5 {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .form-control:focus {
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            border-color: #4299e1;
        }
        .btn-modern {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap me-2"></i> <span>StudyHub</span>
        </div>
    <ul class="nav flex-column" id="sidebarAccordion">
        <!-- Core -->

        <li>
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
            <div class="collapse" id="discoveryMenu" data-bs-parent="#sidebarAccordion">
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
            <a href="#uploadMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <div class="nav-link-content">
                    <i class="fa fa-cloud-upload-alt main-icon"></i><span>Manage</span>
                </div>
                <i class="fa fa-chevron-right arrow-icon"></i>
            </a>
            <div class="collapse" id="uploadMenu" data-bs-parent="#sidebarAccordion">
                <ul class="sub-menu">
                    <li><a href="<?php echo url('upload'); ?>" class="nav-link">Upload Notes</a></li>
                    <li><a href="<?php echo url('note/my_notes'); ?>" class="nav-link">My Notes</a></li>
                </ul>
            </div>
        </li>

        <!-- Account -->

        <li class="active">
            <a href="<?php echo url('profile'); ?>" class="nav-link">
                <div class="nav-link-content">
                    <i class="fa fa-user main-icon"></i><span>Profile</span>
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
                <h5 class="mb-0 fw-semibold">My Profile</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark text-uppercase"><?php echo $user['role']; ?></span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
                </a>
            </div>
        </header>

        <!-- Profile & Password Sections -->
        <div class="container py-2">
            <div class="row g-4">
                
                <?php if (!empty($message)): ?>
                <div class="col-12">
                     <div class="alert alert-success shadow-sm border-0 rounded-3"><?php echo $message; ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                <div class="col-12">
                     <div class="alert alert-danger shadow-sm border-0 rounded-3"><?php echo $error; ?></div>
                </div>
                <?php endif; ?>

                <!-- Profile Info -->
                <div class="col-md-6">
                    <div class="profile-card h-100">
                        <div class="profile-header">
                            <h5><i class="fa fa-user-circle me-2 text-primary"></i>Profile Info</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="post" action="<?php echo url('user/update_profile'); ?>">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Role</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['role']); ?>" disabled>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Points</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-warning text-dark border-warning">⭐</span>
                                        <input type="text" class="form-control border-warning" value="<?php echo intval($user['points']); ?>" disabled>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 btn-modern">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Password Update -->
                <div class="col-md-6">
                     <div class="profile-card h-100">
                        <div class="profile-header">
                            <h5><i class="fa fa-lock me-2 text-warning"></i>Security</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="post" action="<?php echo url('user/update_password'); ?>">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 text-white btn-modern">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
</body>
</html>
