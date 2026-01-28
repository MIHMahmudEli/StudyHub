<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>StudyHub</title>

<!-- All assets using asset() helper -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="<?php echo asset('css/home-style.css?v=4.0.4'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Logic needs to be careful with script paths -->
<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>" defer></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="<?php echo url('home/dashboard'); ?>">StudyHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link <?php echo !$isBookmarksView ? 'active' : ''; ?> text-white" href="<?php echo url('home/dashboard'); ?>">Notes</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('resources'); ?>">Resources</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $isBookmarksView ? 'active' : ''; ?> text-white" href="<?php echo url('home/dashboard'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('leaderboard'); ?>">Leaderboard</a></li>
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
    <?php if ($isBookmarksView): ?>
        <?php if (!empty($notes) || !empty($bookmarkedResources) || !empty($bookmarkedSubjects)): ?>
            <!-- Tabs -->
            <ul class="nav nav-pills mb-4 gap-2 justify-content-center" id="bookmarkTabs" role="tablist">
                <?php if (!empty($notes)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 border" id="notes-tab" data-bs-toggle="pill" data-bs-target="#pills-notes" type="button" role="tab" aria-controls="pills-notes" aria-selected="true"><i class="fa fa-file-alt me-2"></i>Notes (<?php echo count($notes); ?>)</button>
                </li>
                <?php endif; ?>
                <?php if (!empty($bookmarkedResources)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo empty($notes) ? 'active' : ''; ?> rounded-pill px-4 border" id="resources-tab" data-bs-toggle="pill" data-bs-target="#pills-resources" type="button" role="tab" aria-controls="pills-resources" aria-selected="<?php echo empty($notes) ? 'true' : 'false'; ?>"><i class="fa fa-book me-2"></i>Files (<?php echo count($bookmarkedResources); ?>)</button>
                </li>
                <?php endif; ?>
                <?php if (!empty($bookmarkedSubjects)): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo (empty($notes) && empty($bookmarkedResources)) ? 'active' : ''; ?> rounded-pill px-4 border" id="subjects-tab" data-bs-toggle="pill" data-bs-target="#pills-subjects" type="button" role="tab" aria-controls="pills-subjects" aria-selected="<?php echo (empty($notes) && empty($bookmarkedResources)) ? 'true' : 'false'; ?>"><i class="fa fa-folder-open me-2"></i>Subjects (<?php echo count($bookmarkedSubjects); ?>)</button>
                </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content" id="bookmarkTabsContent">
                <!-- Notes Pane -->
                <?php if (!empty($notes)): ?>
                <div class="tab-pane fade show active" id="pills-notes" role="tabpanel" aria-labelledby="notes-tab">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                        <?php foreach ($notes as $row): ?>
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
                                <h5 class="card-title text-truncate"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($row['subject']); ?></p>
                                <div class="note-rating">
                                    <?php
                                    $rating = round($row['avg_rating']);
                                    for ($k=1; $k<=5; $k++) echo $k <= $rating ? "★" : "☆";
                                    ?>
                                </div>
                                </div>
                            </a>

                            <div class="card-footer note-actions">
                                <button class="btn btn-sm btn-danger bookmark-btn rounded-3" style="width: 60px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;" data-id="<?php echo $row['id']; ?>" data-type="note" data-url="<?php echo url('home/bookmark'); ?>" title="Remove Bookmark">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <a href="<?php echo url('note/download'); ?>?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success rounded-3" style="width: 60px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;" title="Download">
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>

                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Resources (Files) Pane -->
                <?php if (!empty($bookmarkedResources)): ?>
                <div class="tab-pane fade <?php echo empty($notes) ? 'show active' : ''; ?>" id="pills-resources" role="tabpanel" aria-labelledby="resources-tab">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                         <?php foreach ($bookmarkedResources as $res): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm note-card position-relative">
                                <a href="<?php echo url('note/download'); ?>?id=<?php echo $res['id']; ?>&type=resource" class="text-decoration-none text-dark">
                                    <div class="card-body text-center py-4">
                                        <div class="note-file mb-3">
                                            <?php 
                                            $ext = strtolower($res['file_type']);
                                            if ($ext === 'pdf') echo '📕';
                                            elseif (in_array($ext, ['doc','docx'])) echo '📝';
                                            elseif (in_array($ext, ['ppt','pptx'])) echo '📊';
                                            else echo '📁';
                                            ?>
                                        </div>
                                        <h5 class="card-title text-truncate"><?php echo htmlspecialchars($res['title']); ?></h5>
                                        <p class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($res['subject']); ?></p>
                                        <div class="text-muted small"><?php echo htmlspecialchars($res['course_code']); ?></div>
                                    </div>
                                </a>

                                <div class="card-footer note-actions">
                                    <button class="btn btn-sm btn-danger bookmark-btn rounded-3" style="width: 60px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;" data-id="<?php echo $res['id']; ?>" data-type="resource" data-url="<?php echo url('resources/bookmark'); ?>" title="Remove Bookmark">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="<?php echo url('note/download'); ?>?id=<?php echo $res['id']; ?>&type=resource" class="btn btn-sm btn-success rounded-3" style="width: 60px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;" title="Download">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Subjects Pane -->
                <?php if (!empty($bookmarkedSubjects)): ?>
                <div class="tab-pane fade <?php echo (empty($notes) && empty($bookmarkedResources)) ? 'show active' : ''; ?>" id="pills-subjects" role="tabpanel" aria-labelledby="subjects-tab">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                         <?php foreach ($bookmarkedSubjects as $sub): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm note-card position-relative">
                                <a href="<?php echo url('resources/subject'); ?>?subject=<?php echo urlencode($sub['subject']); ?>" class="text-decoration-none text-dark">
                                    <div class="card-body text-center py-4">
                                        <div class="note-file mb-3" style="font-size: 2.5rem; color: #6366f1;">
                                            <i class="fa fa-folder"></i>
                                        </div>
                                        <h5 class="card-title text-truncate"><?php echo htmlspecialchars($sub['subject']); ?></h5>
                                        <p class="card-subtitle mb-2 text-muted"><?php echo $sub['resource_count']; ?> Resources</p>
                                    </div>
                                </a>

                                <div class="card-footer note-actions">
                                    <button class="btn btn-sm btn-danger bookmark-btn rounded-3" style="width: 60px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem;" data-id="<?php echo htmlspecialchars($sub['subject']); ?>" data-type="subject" data-url="<?php echo url('resources/bookmark'); ?>" title="Remove Bookmark">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="<?php echo url('resources/subject'); ?>?subject=<?php echo urlencode($sub['subject']); ?>" class="btn btn-sm btn-primary rounded-3">Browse</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
             <div class="d-flex flex-column justify-content-center align-items-center text-center no-content w-100">
                <div class="mb-4"><i class="fa fa-bookmark fa-5x text-primary animate__animated animate__bounce"></i></div>
                <h3 class="mb-3 text-secondary fw-semibold">No bookmarks yet!</h3>
                <p class="text-muted fs-5 mb-4" style="max-width: 500px;">
                    You haven’t bookmarked any notes or resources yet. Start exploring!
                </p>
                <div class="d-flex gap-3">
                    <a href="<?php echo url('home/dashboard'); ?>" class="btn btn-lg btn-primary shadow-lg rounded-pill px-4">Browse Notes</a>
                    <a href="<?php echo url('resources'); ?>" class="btn btn-lg btn-outline-primary shadow-sm rounded-pill px-4">Browse Resources</a>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Normal Dashboard View (Non-Bookmarks) -->
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
                    <?php if (!$alreadyBookmarked): ?>
                        <button class="btn btn-sm btn-primary bookmark-btn" data-id="<?php echo $row['id']; ?>" data-type="note" data-url="<?php echo url('home/bookmark'); ?>">🔖 Bookmark</button>
                    <?php endif; ?>
                    <a href="<?php echo url('note/download'); ?>?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">⬇️ Download</a>
                </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="d-flex flex-column justify-content-center align-items-center text-center no-content w-100">
            <div class="mb-4"><i class="fa fa-folder-open fa-5x text-muted opacity-25"></i></div>
            <h3 class="mb-3 text-secondary fw-semibold">No notes found.</h3>
            <p class="text-muted fs-5 mb-4">
                It looks like there are no notes matching your search.
            </p>
        </div>
        <?php endif; ?>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
