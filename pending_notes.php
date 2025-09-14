<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: index.php#login");
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
    <title>Pending Notes - Admin</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"> <!-- sidebar/topbar styles -->
    <link rel="stylesheet" href="assets/css/manage_users.css"> <!-- table styling -->
    <link rel="stylesheet" href="assets/css/pending_notes.css">
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
            <li class="active"><a href="pending_notes.php"><i class="fa fa-file"></i> <span>Notes</span></a></li>

            <?php if ($role === 'admin') { ?>
                <li><a href="manage_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <?php } ?>

            <li><a href="trending_subjects.php"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>

            <?php if ($role === 'admin') { ?>
                <li><a href="#"><i class="fa fa-file-alt"></i> <span>Reports</span></a></li>
            <?php } ?>
            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li><a href="show_uploaded.php"><i class="fa fa-upload"></i> <span>Uploaded Notes</span></a></li>
            <li><a href="settings.php"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php" ><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
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
                <h2>Pending Notes</h2>
            </div>
            <div class="topbar-right">
                <span class="role"><?php echo $role ?></span>
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </header>

        <!-- Notes Table -->
        <section class="content">
            <h2>Notes Awaiting Approval</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Uploader</th>
                            <th>Uploaded At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pendingNotes->num_rows > 0): ?>
                            <?php while ($note = $pendingNotes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($note['title']); ?></td>
                                <td><?php echo htmlspecialchars($note['uploader']); ?></td>
                                <td><?php echo $note['created_at']; ?></td>
                                <td>
                                    <a class="promote" href="approve_note.php?id=<?php echo $note['id']; ?>">Approve</a>
                                    <a class="delete" href="reject_note.php?id=<?php echo $note['id']; ?>">Reject</a>
                                    <a class="btn" href="preview_note.php?id=<?php echo $note['id']; ?>">Preview</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center; padding:20px; color:#777;">
                                    No pending notes 🎉
                                </td>
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
