<?php
session_start();
include("includes/db.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit();
}
$role = $_SESSION['role'];

$userId = intval($_SESSION['user_id']);
$message = "";
$error = "";

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    $noteId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM notes WHERE id=? AND uploader_id=?");
    $stmt->bind_param("ii", $noteId, $userId);
    if ($stmt->execute()) {
        $message = "Note deleted successfully.";
    } else {
        $error = "Failed to delete note.";
    }
}

// --- Handle Update ---
if (isset($_POST['update_note'])) {
    $noteId = intval($_POST['note_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $course_code = trim($_POST['course_code']);

    $stmt = $conn->prepare("UPDATE notes 
                            SET title=?, description=?, subject=?, course_code=? 
                            WHERE id=? AND uploader_id=?");
    $stmt->bind_param("ssssii", $title, $description, $subject, $course_code, $noteId, $userId);
    if ($stmt->execute()) {
        $message = "Note updated successfully.";
    } else {
        $error = "Failed to update note.";
    }
}

// --- Fetch User’s Notes ---
$stmt = $conn->prepare("SELECT id, title, subject, course_code, description, status, created_at 
                        FROM notes WHERE uploader_id=? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
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
    <link rel="stylesheet" href="assets/css/admin_dashboard.css?v=3.0">
    <link rel="stylesheet" href="assets/css/show_uploaded.css?v=3.0">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <?php if ($role === 'student') { ?>
                <li><a href="user_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
                <li><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
                <li><a href="upload.php" class="nav-link"><i class="fa fa-upload me-2"></i>Upload Notes</a></li>
                <li><a href="leaderboard.php" class="nav-link"><i class="fa fa-trophy me-2"></i>Leaderboard</a></li>
                <li class="active"><a href="show_uploaded.php" class="nav-link"><i class="fa fa-file me-2"></i>Uploaded Notes</a></li>
                <li><a href="profile.php" class="nav-link"><i class="fa fa-user me-2"></i>Profile</a></li>
                
            <?php } else { ?>
                <!-- Admin/Moderator Sidebar -->
                <li><a href="admin_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a href="pending_notes.php" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
                <?php if ($role === 'admin') { ?>
                    <li><a href="manage_users.php" class="nav-link"><i class="fa fa-users me-2"></i>Users</a></li>
                <?php } ?>
                <li><a href="trending_subjects.php" class="nav-link"><i class="fa fa-chart-bar me-2"></i>Analytics</a></li>
                <?php if ($role === 'admin') { ?>
                    <li><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
                <?php } ?>
                <li><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
                <li class="active"><a href="show_uploaded.php" class="nav-link"><i class="fa fa-upload me-2"></i>Uploaded Notes</a></li>
                <li><a href="settings.php" class="nav-link"><i class="fa fa-cog me-2"></i>Settings</a></li>
            <?php } ?>
        </ul>
        <div class="logout mt-auto px-3 pb-3">
            <a href="logout.php" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </div>
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
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </header>

        <!-- Messages -->
        <div class="container mb-3">
            <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        </div>

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
                            <div class="card note-card p-3 h-100">
                                <form method="post">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <div class="mb-2">
                                        <label class="form-label">Title:</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($note['title']); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Subject:</label>
                                        <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($note['subject']); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Course Code:</label>
                                        <input type="text" name="course_code" class="form-control" value="<?php echo htmlspecialchars($note['course_code']); ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Description:</label>
                                        <textarea name="description" class="form-control"><?php echo htmlspecialchars($note['description']); ?></textarea>
                                    </div>
                                    <p><strong>Status:</strong> <?php echo htmlspecialchars($note['status']); ?></p>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="submit" name="update_note" class="btn btn-primary">💾 Save</button>
                                        <a href="show_uploaded.php?delete=<?php echo $note['id']; ?>" onclick="return confirm('Are you sure you want to delete this note?');" class="btn btn-danger">🗑️ Delete</a>
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
    <script src="assets/js/admin_dashboard.js?v=3.0"></script>
</body>
</html>
