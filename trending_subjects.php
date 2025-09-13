<?php
session_start();
include("includes/db.php");

// Only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php#login");
    exit();
}

// Fetch top subjects (10)
$subjects = $conn->query("
    SELECT subject, COUNT(*) as total_notes
    FROM notes
    WHERE status='approved'
    GROUP BY subject
    ORDER BY total_notes DESC
    LIMIT 10
");

// For each subject, also get notes
$subjectData = [];
while ($row = $subjects->fetch_assoc()) {
    $subj = $row['subject'];
    $notes = $conn->query("
        SELECT n.title, n.downloads, n.avg_rating, u.name AS uploader
        FROM notes n
        LEFT JOIN users u ON n.uploader_id = u.id
        WHERE n.subject='$subj' AND n.status='approved'
        ORDER BY n.downloads DESC
        LIMIT 5
    ");
    $row['notes'] = $notes->fetch_all(MYSQLI_ASSOC);
    $subjectData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trending Subjects - Admin</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"> <!-- sidebar/topbar -->
    <link rel="stylesheet" href="assets/css/manage_users.css"> <!-- table styling -->
    <link rel="stylesheet" href="assets/css/trending_subjects.css">
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
            <li class="active"><a href="trending_subjects.php"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>
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
                <h2>Trending Subjects</h2>
            </div>
            <div class="topbar-right">
                <span class="role">Admin</span>
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </header>

        <section class="content">
            <?php foreach ($subjectData as $subj): ?>
                <h2 style="margin:20px 0; color:#333;">
                    <?php echo htmlspecialchars($subj['subject']); ?> 
                    <span style="font-size:14px; color:#666;">(<?php echo $subj['total_notes']; ?> notes)</span>
                </h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Uploader</th>
                                <th>Downloads</th>
                                <th>Rating ⭐</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($subj['notes'] as $note): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($note['title']); ?></td>
                                <td><?php echo htmlspecialchars($note['uploader']); ?></td>
                                <td><?php echo $note['downloads']; ?></td>
                                <td><?php echo $note['avg_rating']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
