<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php#login");
    exit();
}

$role = $_SESSION['role'] ?? 'student';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$noteId = intval($_GET['id']);
$homeLink = $_GET['track'] ?? false;

// Fetch note + author info
$stmt = $conn->prepare("
    SELECT n.title, n.description, n.subject, n.course_code, n.file_path, n.file_type, n.created_at,
           n.avg_rating, u.name AS author_name
    FROM notes n
    LEFT JOIN users u ON n.uploader_id = u.id
    WHERE n.id = ?
");
$stmt->bind_param("i", $noteId);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();

if (!$note) {
    echo "Note not found.";
    exit();
}

// Back link
$backLink = (($role === 'admin' || $role === 'moderator') && !$homeLink)
    ? "pending_notes.php" : "home.php";

// Fetch user's previous rating
$userRating = 0;
$userComment = '';
if (isset($_SESSION['user_id'])) {
    $uid = intval($_SESSION['user_id']);
    $check = $conn->prepare("SELECT rating, comment FROM reviews WHERE user_id=? AND note_id=?");
    $check->bind_param("ii", $uid, $noteId);
    $check->execute();
    $check->bind_result($userRating, $userComment);
    $check->fetch();
    $check->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Note - <?php echo htmlspecialchars($note['title']); ?></title>
    <link rel="stylesheet" href="assets/css/note_preview.css">
    <link rel="stylesheet" href="assets/css/rate-note.css">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <script src="assets/js/rate-note.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="note-title"><?php echo htmlspecialchars($note['title']); ?></h1>
            <div class="note-meta">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($note['author_name'] ?? 'Unknown'); ?></p>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($note['subject']); ?></p>
                <p><strong>Course Code:</strong> <?php echo htmlspecialchars($note['course_code']); ?></p>
                <p><strong>Uploaded:</strong> <?php echo $note['created_at']; ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($note['description'])); ?></p>
                <p class="avg-rating">⭐ Average Rating: <?php echo number_format($note['avg_rating'], 1); ?> / 5</p>
            </div>
            <a href="<?php echo $backLink; ?>" class="btn-back">← Back</a>
        </div>

        <!-- Main Content -->
        <div class="note-preview">
            <?php
            $filePath = htmlspecialchars($note['file_path']);
            $fileType = strtolower($note['file_type']);

            if ($fileType === 'pdf') {
                echo "<embed src='$filePath' type='application/pdf'>";
            } elseif (in_array($fileType, ['jpg','jpeg','png','gif'])) {
                echo "<img src='$filePath' alt='Note Preview'>";
            } elseif (in_array($fileType, ['doc','docx'])) {
                echo "<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" . urlencode($filePath) . "'></iframe>";
            } else {
                echo "<p class='no-preview'>Preview not available. 
                <a href='$filePath' target='_blank' class='download-link'>Download</a></p>";
            }
            ?>

            <!-- Rating Section
            <div class="rating-section">
                <h3>Rate this Note</h3>
                <div class="stars" id="ratingStars" data-note-id="<?php echo $noteId; ?>">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        $class = ($i <= $userRating) ? "star filled" : "star";
                        echo "<span class='$class' data-value='$i'>&#9733;</span>";
                    }
                    ?>
                </div>
                <textarea id="ratingComment" placeholder="Add an optional comment..."><?php echo htmlspecialchars($userComment); ?></textarea>
                <button id="submitRating">Submit Rating</button>
                <p id="ratingMsg"></p>
            </div> -->
        </div>
    </div>
    <script src="assets/js/admin_dashboard.js"></script>
    <script src="assets/js/rate-note.js"></script>
</body>
</html>
