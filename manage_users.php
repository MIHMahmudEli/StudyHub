<?php
session_start();
include("includes/db.php");

// Security: only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: main_index.php#login");
    exit();
}

$role = $_SESSION['role'];

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

// Fetch users
$users = $conn->query("SELECT id, name, email, role, points, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - StudyHub</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/admin_dashboard.css">
<link rel="stylesheet" href="assets/css/manage_users.css">
<link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap me-2"></i> StudyHub
    </div>
    <ul class="nav flex-column px-2">
        <li><a href="admin_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a href="pending_notes.php" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
        <li class="active"><a href="manage_users.php" class="nav-link"><i class="fa fa-users me-2"></i>Users</a></li>
        <li><a href="trending_subjects.php" class="nav-link"><i class="fa fa-chart-bar me-2"></i>Analytics</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
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
            <h5 class="mb-0 fw-semibold">Trending Subjects</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark"><?php echo $role; ?></span>
            <a href="admin_dashboard.php" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left me-1"></i>Back</a>
        </div>
    </header>

    <section class="container py-4">
        <!-- Desktop Table -->
        <div class="table-wrapper d-none d-md-block">
            <table class="table table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Points</th><th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($u = $users->fetch_assoc()): ?>
                    <tr class="user-row">
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo $u['role']; ?></td>
                        <td><?php echo $u['points']; ?></td>
                        <td><?php echo $u['created_at']; ?></td>
                    </tr>
                    <tr class="action-row d-none">
                        <td colspan="6" class="text-center">
                            <?php if ($u['role'] === 'student'): ?>
                                <a href="?promote=<?php echo $u['id']; ?>" class="btn btn-success btn-sm me-1" onclick="return confirm('Promote this user?')">Promote</a>
                            <?php endif; ?>
                            <?php if ($u['role'] !== 'admin'): ?>
                                <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="d-md-none">
            <?php
            $users_mobile = $conn->query("SELECT id, name, email, role, points, created_at FROM users ORDER BY created_at DESC");
            while($u = $users_mobile->fetch_assoc()):
            ?>
            <div class="card mb-2 shadow-sm">
                <div class="card-body">
                    <h6><?php echo htmlspecialchars($u['name']); ?> <small class="text-muted">(<?php echo $u['role']; ?>)</small></h6>
                    <p class="mb-1"><?php echo htmlspecialchars($u['email']); ?></p>
                    <p class="mb-1">Points: <?php echo $u['points']; ?></p>
                    <p class="mb-0">Joined: <?php echo $u['created_at']; ?></p>
                    <div class="mt-2">
                        <?php if ($u['role']==='student'): ?>
                            <a href="?promote=<?php echo $u['id']; ?>" class="btn btn-success btn-sm me-1">Promote</a>
                        <?php endif; ?>
                        <?php if ($u['role']!=='admin'): ?>
                            <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/manage_users.js"></script>
<script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
