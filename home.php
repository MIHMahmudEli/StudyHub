<?php
session_start();
include("includes/db.php");
include("includes/redirect_helper.php");

if (!isset($_SESSION['user_id'])) {
    redirect("main_index#login");
    exit;
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$userId = intval($_SESSION['user_id']);
$role = $_SESSION['role'];

$searchTerm = '';
$query = "";
$isBookmarksView = isset($_GET['bookmarks']);

if ($isBookmarksView) {
    $query = "
        SELECT n.id, n.title, n.subject, n.avg_rating, n.file_type, u.name AS author_name,
               b.user_id AS bookmarked
        FROM notes n
        INNER JOIN bookmarks b ON n.id = b.note_id AND b.user_id = $userId
        LEFT JOIN users u ON n.uploader_id = u.id
    ";
    if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
        $searchTerm = mysqli_real_escape_string($conn, trim($_GET['q']));
        $query .= " AND (n.title LIKE '%$searchTerm%' OR n.subject LIKE '%$searchTerm%' OR u.name LIKE '%$searchTerm%')";
    }
    $query .= " ORDER BY n.created_at DESC";
} elseif (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $searchTerm = mysqli_real_escape_string($conn, trim($_GET['q']));
    $query = "
        SELECT n.id, n.title, n.subject, n.avg_rating, n.file_type, u.name AS author_name,
               (SELECT 1 FROM bookmarks WHERE user_id=$userId AND note_id=n.id) AS bookmarked
        FROM notes n
        LEFT JOIN users u ON n.uploader_id = u.id
        WHERE (n.title LIKE '%$searchTerm%' OR n.subject LIKE '%$searchTerm%' OR u.name LIKE '%$searchTerm%')
          AND n.status = 'approved'
        ORDER BY n.created_at DESC
    ";
} else {
    $query = "
        SELECT n.id, n.title, n.subject, n.avg_rating, n.file_type, u.name AS author_name,
               (SELECT 1 FROM bookmarks WHERE user_id=$userId AND note_id=n.id) AS bookmarked
        FROM notes n
        LEFT JOIN users u ON n.uploader_id = u.id
        WHERE n.status = 'approved'
        ORDER BY n.created_at DESC
    ";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notes Hub</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/home-style.css?v=4.0.3">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="favicon.svg">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="assets/js/home-script.js?v=4.0.3" defer></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="<?php echo url('home.php'); ?>">Notes Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link <?php echo !$isBookmarksView ? 'active' : ''; ?> text-white" href="<?php echo url('home.php'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $isBookmarksView ? 'active' : ''; ?> text-white" href="<?php echo url('home.php'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('leaderboard.php'); ?>">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('upload.php'); ?>">Upload Notes</a></li>
      </ul>

      <form class="d-flex me-3" method="GET" action="<?php echo url('home.php'); ?>">
        <input class="form-control me-2 rounded-pill" type="search" placeholder="Search notes..." name="q" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <?php if ($isBookmarksView): ?><input type="hidden" name="bookmarks" value="1"><?php endif; ?>
        <button class="btn btn-outline-light rounded-pill" type="submit">Search</button>
      </form>

      <?php if ($role === 'student') { ?>
        <a href="<?php echo url('user_dashboard.php'); ?>" class="name me-3 text-white fw-bold" data-fullname="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
            👤 Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </a>
      <?php } elseif ($role === 'admin' || $role === 'moderator') { ?>
        <a href="<?php echo url('admin_dashboard.php'); ?>" class="name me-3 text-white fw-bold" data-fullname="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
            👤 Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </a>
      <?php } ?>

      <span class="badge bg-warning text-dark">⭐ <?php echo isset($_SESSION['points']) ? intval($_SESSION['points']) : 0; ?> pts</span>
    </div>
  </div>
</nav>

<!-- Main Content -->
<main class="container my-5">
  <?php if ($isBookmarksView): ?>
    <div class="mb-4 p-3 rounded-4 shadow-sm bg-white d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <i class="fa fa-bookmark fa-2x text-primary"></i>
            <h4 class="mb-0 fw-bold text-dark">Your Bookmarked Notes</h4>
        </div>
        <span class="badge bg-primary fs-6 py-2 px-3 shadow-sm number-of-bookmarks">
            <?php echo $result ? mysqli_num_rows($result) : 0; ?> <?php echo mysqli_num_rows($result) === 1 ? 'bookmark' : 'bookmarks'; ?>
        </span>
    </div>
  <?php endif; ?>

  <!-- 🦴 Skeleton Loader -->
  <div id="skeleton-loader" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php for ($i=0; $i<8; $i++): ?>
      <div class="col">
        <div class="card h-100 shadow-sm placeholder-card">
          <div class="card-body text-center py-4">
            <div class="placeholder-glow mb-3">
              <div class="placeholder rounded-circle bg-secondary" style="width:60px; height:60px; margin:auto;"></div>
            </div>
            <div class="placeholder-glow">
              <span class="placeholder col-8"></span>
            </div>
            <div class="placeholder-glow mt-2">
              <span class="placeholder col-6"></span>
            </div>
            <div class="placeholder-glow mt-3">
              <span class="placeholder col-5"></span>
            </div>
          </div>
        </div>
      </div>
    <?php endfor; ?>
  </div>

  <!-- Actual Note Results -->
  <div id="note-results" style="display:none;">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <?php $alreadyBookmarked = $row['bookmarked'] ? true : false; ?>
          <div class="col">
            <div class="card h-100 shadow-sm note-card position-relative">
              <a href="<?php echo url('preview_note.php'); ?>?id=<?php echo $row['id']; ?>&track=true" class="text-decoration-none text-dark">
                <div class="card-body text-center py-4">
                  <div class="note-file mb-3">
                    <?php 
                    $type = strtolower($row['file_type']);
                    if ($type === 'pdf') echo "📘";
                    elseif (in_array($type, ['jpg','jpeg','png'])) echo "🖼️";
                    else echo "📄";
                    ?>
                  </div>
                  <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                  <p class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($row['subject']); ?></p>
                  <div class="note-rating">
                    <?php
                    $rating = round($row['avg_rating']);
                    for ($i=1; $i<=5; $i++) echo $i <= $rating ? "★" : "☆";
                    ?>
                  </div>
                </div>
              </a>

              <!-- Hover Actions -->
              <div class="card-footer note-actions">
                <?php if ($isBookmarksView): ?>
                  <button class="btn btn-sm btn-danger bookmark-btn" data-id="<?php echo $row['id']; ?>">Remove Bookmark</button>
                <?php else: ?>
                  <?php if (!$alreadyBookmarked): ?>
                    <button class="btn btn-sm btn-primary bookmark-btn" data-id="<?php echo $row['id']; ?>">🔖 Bookmark</button>
                  <?php endif; ?>
                <?php endif; ?>
                <a href="<?php echo url('download.php'); ?>?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">⬇️ Download</a>
              </div>

            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 d-flex flex-column justify-content-center align-items-center text-center no-content">
          <div class="mb-4"><i class="fa fa-bookmark fa-5x text-primary animate__animated animate__bounce"></i></div>
          <h3 class="mb-3 text-secondary fw-semibold">
            <?php echo $isBookmarksView ? 'No bookmarks yet!' : 'No notes found.'; ?>
          </h3>
          <p class="text-muted fs-5 mb-4" style="max-width: 500px;">
            <?php echo $isBookmarksView 
                ? 'You haven’t bookmarked any notes yet. Start exploring and bookmark your favorites!' 
                : 'It looks like there are no notes matching your search. Try a different keyword or browse popular notes.'; ?>
          </p>
          <?php if ($isBookmarksView): ?>
              <a href="<?php echo url('home.php'); ?>" class="btn btn-lg btn-primary shadow-lg">Browse Notes</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>
</body>
</html>
