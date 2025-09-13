<?php
session_start();
include("includes/db.php");

// Allow only admin or moderator
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: index.php#login");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$noteId = intval($_GET['id']);
$stmt = $conn->prepare("SELECT title, description, subject, course_code, file_path, file_type, created_at 
                        FROM notes WHERE id=?");
$stmt->bind_param("i", $noteId);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();

if (!$note) {
    echo "Note not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Note - <?php echo htmlspecialchars($note['title']); ?></title>
    <link rel="stylesheet" href="assets/css/note_preview.css">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="note-title"><?php echo htmlspecialchars($note['title']); ?></h1>
            <div class="note-meta">
                <p><strong>Course:</strong> <?php echo htmlspecialchars($note['subject']); ?></p>
                <p><strong>Course Code:</strong> <?php echo htmlspecialchars($note['course_code']); ?></p>
                <p><strong>Uploaded:</strong> <?php echo $note['created_at']; ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($note['description'])); ?></p>
            </div>
            <a href="pending_notes.php" class="btn-back">← Back to Pending Notes</a>
        </div>

        <!-- Main Content -->
        <div class="note-preview">
            <?php
            $filePath = htmlspecialchars($note['file_path']);
            $fileType = strtolower($note['file_type']);

            if (in_array($fileType, ['pdf'])) {
                echo "<embed src='$filePath' type='application/pdf'>";
            } elseif (in_array($fileType, ['jpg','jpeg','png','gif'])) {
                echo "<img src='$filePath' alt='Note Preview'>";
            } elseif (in_array($fileType, ['doc','docx'])) {
                echo "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . urlencode($filePath) . "'></iframe>";
            } else {
                echo "<p>Preview not available. <a href='$filePath' target='_blank'>Download</a></p>";
            }
            ?>
        </div>
    </div>
    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
