<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Course Resources - Notes Hub</title>

<!-- All assets using asset() helper -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo asset('css/home-style.css?v=4.0.4'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    .subject-card {
        border-radius: 20px;
        border: none;
        transition: all 0.3s ease;
        background: #fff;
        overflow: hidden;
    }
    .subject-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .subject-icon {
        width: 60px;
        height: 60px;
        background: #f8fafc;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #6366f1;
        margin-bottom: 20px;
    }
    .card-footer {
        background: transparent;
        border-top: 1px solid #f1f5f9;
        padding: 1.25rem;
    }
</style>
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
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('leaderboard'); ?>">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link active text-white fw-bold" href="<?php echo url('resources'); ?>">Resources</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('upload'); ?>">Upload Notes</a></li>
      </ul>

      <div class="nav-right-group">

        <form class="search-container" method="GET" action="<?php echo url('resources'); ?>">
          <input class="form-control rounded-pill" type="search" placeholder="Search subjects..." name="q" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
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
          <div id="pointsTooltip" class="modern-tooltip shadow-lg rounded-4 p-3 bg-white border border-2 border-warning">
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

<main class="container my-5">


    <div class="row g-4">
        <?php if (!empty($subjects)): ?>
            <?php foreach ($subjects as $sub): ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 shadow-sm subject-card">
                        <div class="card-body p-4 text-center">
                            <div class="subject-icon mx-auto">
                                <i class="fa fa-folder-open"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($sub['subject']); ?></h5>
                            <p class="text-muted small mb-0"><?php echo $sub['resource_count']; ?> resources available</p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?php echo url('resources/subject'); ?>?subject=<?php echo urlencode($sub['subject']); ?>" class="btn btn-primary rounded-pill px-4">Browse Resources</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa fa-folder-open fa-4x text-muted mb-3 opacity-25"></i>
                <h4 class="text-secondary">No subjects found with approved resources.</h4>
                <a href="<?php echo url('home/dashboard'); ?>" class="btn btn-primary mt-3">Back to Notes</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>" defer></script>
</body>
</html>
