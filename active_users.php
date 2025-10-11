<?php
session_start();
include("includes/db.php");

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: main_index.php#login");
    exit();
}

// Fetch top 20 active users (activity count = events)
$users = $conn->query("
    SELECT u.id, u.name, u.email, u.role, COUNT(e.id) AS activity, MAX(e.at) AS last_active
    FROM users u
    LEFT JOIN events e ON u.id = e.user_id
    GROUP BY u.id
    ORDER BY activity DESC
    LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Active Users - Admin</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/admin_dashboard.css">
<link rel="stylesheet" href="assets/css/manage_users.css">
<link rel="stylesheet" href="assets/css/trending_subjects.css">

</head>
<body>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
    </div>
    <ul class="nav flex-column px-2">
        <li class="nav-item"><a href="admin_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a href="pending_notes.php" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
        <li><a href="manage_users.php" class="nav-link active"><i class="fa fa-users me-2"></i>Users</a></li>
        <li class="nav-item"><a href="active_users.php" class="nav-link"><i class="fa fa-users me-2"></i>Analytics</a></li>
        <li><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
        <li><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
        <li><a href="show_uploaded.php" class="nav-link"><i class="fa fa-upload me-2"></i>Uploaded Notes</a></li>
        <li><a href="settings.php" class="nav-link"><i class="fa fa-cog me-2"></i>Settings</a></li>
    </ul>
    <div class="logout px-3 mt-auto pb-3">
        <a href="logout.php" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    </div>
</aside>

<!-- Main Content -->
<main class="main-content flex-grow-1">
    <!-- Topbar -->
    <header class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0">
                <i class="fa fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-semibold">Most Active Users</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark">Admin</span>
            <a href="admin_dashboard.php" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left me-1"></i>Back</a>
        </div>
    </header>

    <section class="container py-4">
        <h4 class="mb-3">Top 20 Active Users</h4>

        <!-- Desktop Table -->
        <div class="d-none d-md-block table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Activity Count</th>
                        <th>Last Active</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->num_rows > 0): ?>
                        <?php while ($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo $u['role']; ?></td>
                            <td><?php echo $u['activity']; ?></td>
                            <td>
                                <?php 
                                if ($u['last_active']) {
                                    echo date('Y-m-d h:i:s A', strtotime($u['last_active']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No activity found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="d-md-none">
            <?php if ($users->num_rows > 0): ?>
                <?php
                // Reset pointer to fetch rows again
                $users->data_seek(0);
                while ($u = $users->fetch_assoc()):
                ?>
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($u['name']); ?> <span class="badge bg-secondary"><?php echo $u['role']; ?></span></h6>
                        <p class="mb-1"><i class="fa fa-envelope me-1"></i><?php echo htmlspecialchars($u['email']); ?></p>
                        <p class="mb-1"><i class="fa fa-list me-1"></i>Activity: <?php echo $u['activity']; ?></p>
                        <p class="mb-0"><i class="fa fa-clock me-1"></i>Last Active: 
                            <?php 
                            if ($u['last_active']) {
                                echo date('Y-m-d h:i:s A', strtotime($u['last_active']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No activity found</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<script s
