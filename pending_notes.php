<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: main_index.php#login");
    exit();
}
$role = $_SESSION['role'];

// Fetch pending notes
$pendingNotes = $conn->query("SELECT n.id, n.title, n.created_at, u.name AS uploader 
                              FROM notes n
                              LEFT JOIN users u ON n.uploader_id = u.id
                              WHERE n.status='pending'
                              ORDER BY n.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Notes - Admin</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=3.0">
    <link rel="stylesheet" href="assets/css/pending_notes.css?v=3.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <style>
        .table-responsive { margin-top: 20px; }
        .action-btn { margin-right: 5px; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="admin_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
            <li class="nav-item active"><a href="pending_notes.php" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
            <?php if ($role === 'admin') { ?>
            <li class="nav-item"><a href="manage_users.php" class="nav-link"><i class="fa fa-users me-2"></i>Users</a></li>
            <?php } ?>
            <li class="nav-item"><a href="trending_subjects.php" class="nav-link"><i class="fa fa-chart-bar me-2"></i>Analytics</a></li>
            <?php if ($role === 'admin') { ?>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
            <?php } ?>
            <li class="nav-item"><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
            <li class="nav-item"><a href="show_uploaded.php" class="nav-link"><i class="fa fa-upload me-2"></i>Uploaded Notes</a></li>
            <li class="nav-item"><a href="settings.php" class="nav-link"><i class="fa fa-cog me-2"></i>Settings</a></li>
        </ul>
        <div class="logout mt-auto px-3 pb-3">
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
            <h5 class="mb-0 fw-semibold">Pending Notes</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark"><?php echo $role; ?></span>
            <a href="admin_dashboard.php" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left me-1"></i>Back</a>
        </div>
    </header>

        <!-- Notes Table -->
        <div class="container">
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Title</th>
                            <th>Uploader</th>
                            <th>Uploaded At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pendingNotes->num_rows > 0): ?>
                            <?php while ($note = $pendingNotes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($note['title']); ?></td>
                                <td><?php echo htmlspecialchars($note['uploader']); ?></td>
                                <td><?php echo date('d M Y, H:i', strtotime($note['created_at'])); ?></td>
                                <td class="text-center">
                                    <a href="approve_note.php?id=<?php echo $note['id']; ?>" class="btn btn-success btn-sm action-btn"><i class="fa fa-check"></i> Approve</a>
                                    <a href="reject_note.php?id=<?php echo $note['id']; ?>" class="btn btn-danger btn-sm action-btn"><i class="fa fa-times"></i> Reject</a>
                                    <a href="preview_note.php?id=<?php echo $note['id']; ?>" class="btn btn-info btn-sm action-btn"><i class="fa fa-eye"></i> Preview</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No pending notes 🎉</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin_dashboard.js?v=3.0"></script>
</body>
</html>
