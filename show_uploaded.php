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
        $message = " Note deleted successfully.";
    } else {
        $error = " Failed to delete note.";
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
        $message = " Note updated successfully.";
    } else {
        $error = " Failed to update note.";
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
    <link rel="stylesheet" href="assets/css/admin_dashboard.css"><!-- sidebar & header -->
    <link rel="stylesheet" href="assets/css/show_uploaded.css"><!-- new styles -->
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Admin/Moderator Sidebar -->
        <?php if (in_array($_SESSION['role'], ['admin', 'moderator'])) { ?>
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav">
            <li><a href="admin_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="pending_notes.php"><i class="fa fa-file"></i> <span>Notes</span></a></li>

            <?php if ($role === 'admin') { ?>
                <li><a href="manage_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <?php } ?>

            <li><a href="trending_subjects.php"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>

            <?php if ($role === 'admin') { ?>
                <li><a href="#"><i class="fa fa-file-alt"></i> <span>Reports</span></a></li>
            <?php } ?>

            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li class="active"><a href="show_uploaded.php"><i class="fa fa-upload"></i> <span>Uploaded Notes</span></a></li>
            <li><a href="settings.php"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php" ><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
        <?php } else { ?>
        <!-- Student Sidebar -->
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav">
            <li><a href="user_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li><a href="upload.php"><i class="fa fa-upload"></i> <span>Upload Notes</span></a></li>
            <li><a href="leaderboard.php"><i class="fa fa-trophy"></i> <span>Leaderboard</span></a></li>
            <li><a href="profile.php"><i class="fa fa-user"></i> <span>Profile</span></a></li>
            <li class="active"><a href="show_uploaded.php"><i class="fa fa-file"></i> <span>My Uploaded Notes</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
        <?php } ?>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-toggle">
                    <i class="fa fa-bars"></i>
                </div>
                <h2>📘 My Uploaded Notes</h2>
            </div>
            <div class="topbar-right">
                <span class="role"><?php echo ucfirst($_SESSION['role']); ?></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </header>

        <!-- Messages -->
        <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Notes Section -->
        <section class="notes-grid">
            <?php if (empty($notes)) { ?>
                <p class="no-notes">You haven’t uploaded any notes yet.</p>
            <?php } else { ?>
                <?php foreach ($notes as $note) { ?>
                    <div class="note-card">
                        <form method="post">
                            <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">

                            <label>Title:</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>">

                            <label>Subject:</label>
                            <input type="text" name="subject" value="<?php echo htmlspecialchars($note['subject']); ?>">

                            <label>Course Code:</label>
                            <input type="text" name="course_code" value="<?php echo htmlspecialchars($note['course_code']); ?>">

                            <label>Description:</label>
                            <textarea name="description"><?php echo htmlspecialchars($note['description']); ?></textarea>

                            <p><strong>Status:</strong> <?php echo htmlspecialchars($note['status']); ?></p>

                            <div class="actions">
                                <button type="submit" name="update_note" class="save-btn">💾 Save</button>
                                <a href="show_uploaded.php?delete=<?php echo $note['id']; ?>" onclick="return confirm('Are you sure you want to delete this note?');" class="delete-btn">🗑️ Delete</a>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            <?php } ?>
        </section>
    </main>

    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
