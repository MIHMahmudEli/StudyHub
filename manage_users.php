<?php
session_start();
include("includes/db.php");

// Security: only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php#login");
    exit();
}

// Handle promote
if (isset($_GET['promote'])) {
    $uid = intval($_GET['promote']);
    $conn->query("UPDATE users SET role='moderator' WHERE id=$uid AND role='student'");
    header("Location: manage_users.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$uid AND role!='admin'");
    header("Location: manage_users.php");
    exit();
}

// Fetch users (exclude password)
$users = $conn->query("SELECT id, name, email, role, points, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - StudyHub</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"> <!-- keeps sidebar/topbar styles -->
    <link rel="stylesheet" href="assets/css/manage_users.css"> <!-- the new stylish CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <li class="active"><a href="manage_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <li><a href="trending_subjects.php"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>
            <li><a href="#"><i class="fa fa-file-alt"></i> <span>Reports</span></a></li>
            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li><a href="show_uploaded.php"><i class="fa fa-upload"></i> <span>Uploaded Notes</span></a></li>
            <li><a href="settings.php"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-toggle">
                    <i class="fa fa-bars"></i>
                </div>
                <h2>User Management</h2>
            </div>
            <div class="topbar-right">
                <span class="role">Admin</span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </header>

        <!-- User Table -->
        <section class="content">
            <h2>All Users</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Points</th><th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($u = $users->fetch_assoc()): ?>
                        <tr onclick="toggleActions(this)">
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo $u['role']; ?></td>
                            <td><?php echo $u['points']; ?></td>
                            <td><?php echo $u['created_at']; ?></td>
                        </tr>
                        <tr class="action-row">
                            <td colspan="6">
                                <?php if ($u['role'] === 'student'): ?>
                                    <a class="promote" href="?promote=<?php echo $u['id']; ?>" 
                                       onclick="return confirm('Promote this user to Moderator?')">Promote</a>
                                <?php endif; ?>
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <a class="delete" href="?delete=<?php echo $u['id']; ?>" 
                                       onclick="return confirm('Delete this user?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="assets/js/manage_users.js"></script>
    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
