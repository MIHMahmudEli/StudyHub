<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: main_index.php#login");
    exit();
}

$role = $_SESSION['role'];

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

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/admin_dashboard.css?v=3.0">
<link rel="stylesheet" href="assets/css/manage_users.css?v=3.0">
<link rel="stylesheet" href="assets/css/trending_subjects.css?v=3.0">
<link rel="icon" type="image/svg+xml" href="favicon.svg">

</head>
<body>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
    </div>
    <ul class="nav flex-column px-2">
        <li><a href="admin_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a href="pending_notes.php" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
        <?php if($role==='admin'): ?>
            <li><a href="manage_users.php" class="nav-link"><i class="fa fa-users me-2"></i>Users</a></li>
        <?php endif; ?>
        <li class="active"><a href="trending_subjects.php" class="nav-link"><i class="fa fa-chart-bar me-2"></i>Analytics</a></li>
        <?php if($role==='admin'): ?>
            <li><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
        <?php endif; ?>
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
        <?php foreach($subjectData as $subj): ?>
        <h4 class="mb-3"><?php echo htmlspecialchars($subj['subject']); ?>
            <small class="text-muted">(<?php echo $subj['total_notes']; ?> notes)</small>
        </h4>

        <!-- Desktop Table -->
        <div class="d-none d-md-block table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Title</th>
                        <th>Uploader</th>
                        <th>Downloads</th>
                        <th>Rating ⭐</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($subj['notes'] as $note): ?>
                    <tr class="note-row">
                        <td><?php echo htmlspecialchars($note['title']); ?></td>
                        <td><?php echo htmlspecialchars($note['uploader']); ?></td>
                        <td><?php echo $note['downloads']; ?></td>
                        <td><?php echo $note['avg_rating']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="d-md-none">
            <?php foreach($subj['notes'] as $note): ?>
            <div class="card mb-2 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><?php echo htmlspecialchars($note['title']); ?></h6>
                    <p class="mb-1"><i class="fa fa-user me-1"></i><?php echo htmlspecialchars($note['uploader']); ?></p>
                    <p class="mb-1"><i class="fa fa-download me-1"></i><?php echo $note['downloads']; ?> Downloads</p>
                    <p class="mb-0"><i class="fa fa-star me-1"></i><?php echo $note['avg_rating']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endforeach; ?>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/admin_dashboard.js?v=3.0"></script>
</body>
</html>
