<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Notes Hub</title>

<!-- All assets using asset() helper -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="<?php echo asset('css/home-style.css?v=4.0.4'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Logic needs to be careful with script paths -->
<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>" defer></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="<?php echo url('home/dashboard'); ?>">Notes Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link <?php echo !$isBookmarksView ? 'active' : ''; ?> text-white" href="<?php echo url('home/dashboard'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $isBookmarksView ? 'active' : ''; ?> text-white" href="<?php echo url('home/dashboard'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('leaderboard'); ?>">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('resources'); ?>">Resources</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('upload'); ?>">Upload Notes</a></li>
      </ul>

      <div class="nav-right-group">

      <form class="search-container me-3" method="GET" action="<?php echo url('home/dashboard'); ?>">
        <input class="form-control rounded-pill" type="search" placeholder="Search notes..." name="q" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
        <?php if ($isBookmarksView): ?><input type="hidden" name="bookmarks" value="1"><?php endif; ?>
        <button class="search-icon-btn" type="submit"><i class="fa fa-search"></i></button>
      </form>

        <?php if ($role === 'student' || $role === 'admin' || $role === 'moderator') { 
            $dashboardPath = ($role === 'student') ? 'user/dashboard' : 'admin/dashboard';
        ?>
          <a href="<?php echo url($dashboardPath); ?>" class="name text-white fw-bold" data-fullname="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
              👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?>
          </a>
        <?php } ?>
      


        <!-- Points Badge -->
        <div class="position-relative d-inline-block">
          <span id="pointsBadge"
                class="badge shadow-sm px-3 py-2 modern-points-badge">
            ⭐ <?php echo isset($_SESSION['points']) ? intval($_SESSION['points']) : 0; ?> pts
          </span>

          <!-- Custom Tooltip -->
          <div id="pointsTooltip" class="modern-tooltip shadow-lg rounded-4 p-3 bg-white border border-2" style="border-color: #f59e0b !important;">
            <h6 class="fw-bold text-dark mb-2">🌟 How to Earn Points</h6>
            <ul class="list-unstyled small text-secondary mb-0">
              <li>📥 <b>Download a note</b> → +1 point</li>
              <li>📤 <b>Upload a note</b> → +5 points</li>
              <li>👥 <b>Someone downloads your note</b> → +2 points</li>
            </ul>
          </div>
        </div>
      </div>
  </div>
</nav>

<!-- Main Content -->
<main class="container my-5">
  <?php if ($isBookmarksView): ?>
    <div class="mb-4 p-3 rounded-4 shadow-sm bg-white d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <i class="fa fa-bookmark fa-2x text-primary"></i>
            <h4 class="mb-0 fw-bold text-dark d-none d-md-block">Your Bookmarked Notes</h4>
            <h4 class="mb-0 fw-bold text-dark d-block d-md-none">Bookmarks</h4>
        </div>
        <span class="badge bg-primary fs-6 py-2 px-3 shadow-sm number-of-bookmarks d-none d-md-inline-block">
            <?php echo count($notes); ?> <?php echo count($notes) === 1 ? 'bookmark' : 'bookmarks'; ?>
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
    <?php if (!empty($notes)): ?>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($notes as $row): ?>
          <?php $alreadyBookmarked = $row['bookmarked'] ? true : false; ?>
          <div class="col">
            <div class="card h-100 shadow-sm note-card position-relative">
              <a href="<?php echo url('preview/note'); ?>?id=<?php echo $row['id']; ?>&track=true" class="text-decoration-none text-dark">
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
                    for ($k=1; $k<=5; $k++) echo $k <= $rating ? "★" : "☆";
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
                <a href="<?php echo url('note/download'); ?>?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">⬇️ Download</a>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="d-flex flex-column justify-content-center align-items-center text-center no-content w-100">
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
              <a href="<?php echo url('home/dashboard'); ?>" class="btn btn-lg btn-primary shadow-lg rounded-pill px-5">Browse Notes</a>
          <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
