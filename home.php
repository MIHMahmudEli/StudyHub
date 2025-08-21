<?php
session_start();
include("includes/db.php"); // DB connection

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$userId = intval($_SESSION['user_id']);

// Handle search or bookmark filter
// Handle search or bookmark filter
$searchTerm = '';
$query = "";

$userId = intval($_SESSION['user_id']);

// Check if search term exists in GET
if (isset($_GET['bookmarks'])) {
    // Show only bookmarked notes
    $query = "SELECT n.id, n.title, n.subject, n.avg_rating, n.file_type
              FROM notes n
              INNER JOIN bookmarks b ON n.id = b.note_id
              WHERE b.user_id = $userId";

    if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
        $searchTerm = mysqli_real_escape_string($conn, trim($_GET['q']));
        $query .= " AND (n.title LIKE '%$searchTerm%' OR n.subject LIKE '%$searchTerm%')";
    }

    $query .= " ORDER BY n.created_at DESC";

} elseif (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    // Regular search
    $searchTerm = mysqli_real_escape_string($conn, trim($_GET['q']));
    $query = "SELECT id, title, subject, avg_rating, file_type 
              FROM notes 
              WHERE title LIKE '%$searchTerm%' OR subject LIKE '%$searchTerm%' 
              ORDER BY created_at DESC";

} else {
    // Show all notes
    $query = "SELECT id, title, subject, avg_rating, file_type 
              FROM notes 
              ORDER BY created_at DESC";
}


$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes Hub</title>
    <link rel="stylesheet" href="assets/css/home-style.css">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <script src="assets/js/home-script.js" defer></script>
</head>
<body>
<header>
    <section class="nav-center">
        <a href="home.php">🏠 Home</a>
        <a href="home.php?bookmarks=1" class="<?php echo isset($_GET['bookmarks']) ? 'active-link' : ''; ?>">🔖 Bookmark</a>
        <a href="#">📅 Event</a>
        <a href="leaderboard.php">🏆 Leaderboard</a>
        <a href="upload.php">📘 Upload Notes</a>
    </section>
    <section class="nav-right">
        <form method="GET" action="home.php" class="search-form">
            <input type="text" name="q" id="searchBox" placeholder="🔍 Search notes..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
            <?php if (isset($_GET['bookmarks'])): ?>
                <input type="hidden" name="bookmarks" value="1">
            <?php endif; ?>
        </form>
        <a href="profile.php">
            👤 Hello, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Guest"; ?>
        </a>
        <p>⭐ <?php echo isset($_SESSION['points']) ? intval($_SESSION['points']) : 0; ?> pts</p>
    </section>
</header>

<main>
    <div class="notes-grid">
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="note-card">
                    <div class="note-file">
                        <?php 
                        $type = strtolower($row['file_type']);
                        if ($type === 'pdf') echo "📘";
                        elseif (in_array($type, ['jpg','jpeg','png'])) echo "🖼️";
                        else echo "📘";
                        ?>
                    </div>
                    <h3 class="note-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="note-subject"><?php echo htmlspecialchars($row['subject']); ?></p>
                    <div class="note-rating">
                        <?php
                        $rating = round($row['avg_rating']);
                        for ($i=1; $i<=5; $i++) echo $i <= $rating ? "★" : "☆";
                        ?>
                    </div>
                    <?php if (!$isAdmin): ?>
                        <div class="note-actions">
                            <button class="bookmark-btn" data-id="<?php echo $row['id']; ?>">🔖 Bookmark</button>
                            <a href="download.php?id=<?php echo $row['id']; ?>" class="download-btn">⬇️ Download</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-notes">No notes found.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
