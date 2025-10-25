<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit();
}

$role = $_SESSION['role'] ?? 'student';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$noteId = intval($_GET['id']);
$homeLink = $_GET['track'] ?? false;

$stmt = $conn->prepare("
    SELECT n.title, n.description, n.subject, n.course_code, n.file_path, n.file_type, n.created_at,
           u.name AS author_name, n.downloads
    FROM notes n
    LEFT JOIN users u ON n.uploader_id = u.id
    WHERE n.id = ?
");
$stmt->bind_param("i", $noteId);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$note) {
    echo "Note not found.";
    exit();
}

$backLink = (($role === 'admin' || $role === 'moderator') && !$homeLink) ? "pending_notes.php" : "home.php";

// Detect mobile
$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
$isMobile = preg_match('/android|iphone|ipad|ipod/', $userAgent);

$filePath = htmlspecialchars($note['file_path']);
$fileType = strtolower($note['file_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($note['title']); ?> - Note Preview</title>
<link rel="icon" type="image/svg+xml" href="favicon.svg">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/note_preview.css?v=3.2">
<style>
  /* Force reading mode on mobile */
  <?php if ($isMobile): ?>
  body { overflow: hidden; }
  body.reading-mode { overflow: auto; }
  body.reading-mode .sidebar { display: none; }
  body.reading-mode .main-view { width: 100%; height: 100vh; }
  body.reading-mode .preview-frame, body.reading-mode .preview-image { width: 100%; height: 100%; border-radius: 0; }
  <?php endif; ?>
</style>
</head>
<body class="<?php echo $isMobile ? 'reading-mode' : ''; ?>">

<div class="preview-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="note-card">
            <h1><?php echo htmlspecialchars($note['title']); ?></h1>
            <div class="info-item"><i class="fas fa-user"></i> <?php echo htmlspecialchars($note['author_name'] ?? 'Unknown'); ?></div>
            <div class="info-item">
                <i class="fas fa-book"></i>
                <span class="badge-info"><?php echo htmlspecialchars($note['subject']); ?></span>
                <span class="badge-info"><?php echo htmlspecialchars($note['course_code']); ?></span>
            </div>
            <div class="info-item"><i class="fas fa-calendar-alt"></i> Uploaded: <?php echo date('F j, Y', strtotime($note['created_at'])); ?></div>
            <div class="info-item"><i class="fas fa-download"></i> Downloads: <?php echo intval($note['downloads']); ?></div>
            <div class="desc">
                <h4>Description:</h4>
                <p><?php echo nl2br(htmlspecialchars($note['description'])); ?></p>
            </div>
        </div>
        <div class="buttons">
            <a href="<?php echo $filePath; ?>" download class="download-btn"><i class="fas fa-download"></i> Download</a>
            <button class="read-btn" id="toggleReading"><i class="fas fa-book-reader"></i> Reading Mode</button>
        </div>
    </aside>

    <!-- Main Preview Area -->
    <main class="main-view" id="mainView">
        <?php
        if ($fileType === 'pdf') {
            echo "<embed src='$filePath#toolbar=1&navpanes=0' type='application/pdf' class='preview-frame'>";
        } elseif (in_array($fileType, ['jpg','jpeg','png','gif'])) {
            echo "<img src='$filePath' class='preview-image' alt='Note Image'>";
        } elseif (in_array($fileType, ['doc','docx'])) {
            echo "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . urlencode($filePath) . "' class='preview-frame'></iframe>";
        } else {
            echo "<div class='loading-msg'>Preview not available for this file type.</div>";
        }
        ?>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Focus preview on load
    const frame = document.querySelector('.preview-frame, .preview-image');
    if (frame) {
        frame.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Toggle reading mode
    const toggleBtn = document.getElementById('toggleReading');
    toggleBtn.addEventListener('click', function() {
        document.body.classList.toggle('reading-mode');
        this.innerHTML = document.body.classList.contains('reading-mode') 
            ? '<i class="fas fa-times"></i> Exit Reading Mode' 
            : '<i class="fas fa-book-reader"></i> Reading Mode';
    });
});
</script>
</body>
</html>
