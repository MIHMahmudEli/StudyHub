<?php
session_start();
include("includes/db.php");

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php#login");
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
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"> <!-- sidebar/topbar -->
    <link rel="stylesheet" href="assets/css/manage_users.css"> <!-- buttons + table style -->
    <link rel="stylesheet" href="assets/css/trending_subjects.css"> <!-- nice table styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav">
            <li><a href="admin_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="pending_notes.php"><i class="fa fa-file"></i> <span>Notes</span></a></li>
            <li><a href="manage_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <li class="active"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>
            <li><a href="#"><i class="fa fa-file-alt"></i> <span>Reports</span></a></li>
            <li><a href="settings.php"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-toggle">
                    <i class="fa fa-bars"></i>
                </div>
                <h2>Most Active Users</h2>
            </div>
            <div class="topbar-right">
                <span class="role">Admin</span>
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </header>

        <!-- Active Users Table -->
        <section class="content">
            <h2>Top 20 Active Users</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
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
                                <td><?php echo $u['last_active'] ?: 'N/A'; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-message">No activity found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
