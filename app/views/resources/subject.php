<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($subject); ?> Resources - StudyHub</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo asset('css/home-style.css?v=4.0.4'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .term-card {
        border-radius: 25px;
        border: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .term-card:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .mid-term-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
    }
    .final-term-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: #fff;
    }
    .term-icon {
        font-size: 4rem;
        opacity: 0.2;
        position: absolute;
        bottom: -10px;
        right: -10px;
    }
</style>
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
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>">Notes</a></li>
        <li class="nav-item"><a class="nav-link active text-white fw-bold" href="<?php echo url('resources'); ?>">Resources</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('leaderboard'); ?>">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('upload'); ?>">Upload Notes</a></li>
      </ul>

      <div class="nav-right-group">

        <form class="search-container" method="GET" action="<?php echo url('resources'); ?>">
          <input class="form-control rounded-pill" type="search" placeholder="Search subjects..." name="q">
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

<main class="container my-3">
    <!-- Premium Header Section -->
    <!-- <div class="row mb-4 justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="selection-badge mb-2">
                <span class="badge bg-primary-subtle text-primary text-uppercase fw-bold px-3 py-2 rounded-pill" style="font-size: 0.75rem; letter-spacing: 1.5px;">
                    <i class="fa fa-graduation-cap me-1"></i> Subject Selection
                </span>
            </div>
            <h2 class="fw-800 text-dark mb-2" style="font-size: 2.25rem;"><?php echo htmlspecialchars($subject); ?></h2>
            <p class="text-secondary mx-auto mb-0" style="max-width: 550px; font-size: 0.95rem;">
                Explore high-quality resources tailored for your exams. Select a term below for Mid or Final curriculum materials.
            </p>
            <div class="header-divider mx-auto mt-3" style="width: 60px; height: 4px;"></div>
        </div>
    </div> -->

    <div class="row justify-content-center g-4 mt-0">
        <!-- Mid Term Card -->
        <div class="col-md-5 col-lg-4">
            <a href="<?php echo url('resources/list'); ?>?subject=<?php echo urlencode($subject); ?>&term=mid" class="text-decoration-none group">
                <div class="card h-100 term-card mid-term-vibrant border-0 shadow-lg p-4 text-center position-relative overflow-hidden">
                    <div class="card-body py-5 z-index-1">
                        <div class="icon-blob mb-4 mx-auto">
                            <i class="fa fa-file-circle-check fa-3x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Mid Term</h2>
                        <p class="opacity-90 mb-0">Master your midterms with curated lectures, assignments, and sample papers.</p>
                    </div>
                    <!-- Decorative Elements -->
                    <div class="term-blob-1"></div>
                    <div class="term-blob-2"></div>
                </div>
            </a>
        </div>

        <!-- Final Term Card -->
        <div class="col-md-5 col-lg-4">
            <a href="<?php echo url('resources/list'); ?>?subject=<?php echo urlencode($subject); ?>&term=final" class="text-decoration-none group">
                <div class="card h-100 term-card final-term-vibrant border-0 shadow-lg p-4 text-center position-relative overflow-hidden">
                    <div class="card-body py-5 z-index-1">
                        <div class="icon-blob mb-4 mx-auto">
                            <i class="fa fa-trophy fa-3x"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Final Term</h2>
                        <p class="opacity-90 mb-0">Prepare for success with comprehensive final examination resources and guides.</p>
                    </div>
                    <!-- Decorative Elements -->
                    <div class="term-blob-3"></div>
                    <div class="term-blob-4"></div>
                </div>
            </a>
        </div>
    </div>
</main>

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
    }
    
    .ls-2 { letter-spacing: 2px; }
    .fw-800 { font-weight: 800; }
    .z-index-1 { position: relative; z-index: 1; }
    .opacity-90 { opacity: 0.9; }

    .header-divider {
        width: 80px;
        height: 6px;
        background: linear-gradient(to right, #3498db, #2ecc71);
        border-radius: 10px;
    }

    .term-card {
        border-radius: 35px;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        color: #fff;
    }

    .term-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 30px 60px rgba(0,0,0,0.15) !important;
    }

    .mid-term-vibrant {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .final-term-vibrant {
        background: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    }

    .icon-blob {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.5s ease;
    }

    .term-card:hover .icon-blob {
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(10deg);
    }

    /* Decorative Shapes */
    .term-blob-1, .term-blob-2, .term-blob-3, .term-blob-4 {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        z-index: 0;
    }

    .term-blob-1 { width: 150px; height: 150px; top: -50px; right: -50px; }
    .term-blob-2 { width: 100px; height: 100px; bottom: -20px; left: -20px; }
    .term-blob-3 { width: 120px; height: 120px; top: -30px; left: -30px; }
    .term-blob-4 { width: 180px; height: 180px; bottom: -60px; right: -60px; }

    .term-card h2 {
        font-size: 2.2rem;
        letter-spacing: -1px;
    }

    /* Mobile Enhancements for Compactness */
    @media (max-width: 768px) {
        .main-content {
            margin-top: 2rem !important;
        }
        .container.my-5 {
            margin-top: 1.5rem !important;
        }
        .term-card {
            padding: 1.5rem !important;
            border-radius: 25px !important;
        }
        .term-card .card-body {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
        .term-card h2 {
            font-size: 1.5rem !important;
            margin-bottom: 0.75rem !important;
        }
        .term-card p {
            font-size: 0.85rem !important;
            line-height: 1.4;
        }
        .icon-blob {
            width: 70px !important;
            height: 70px !important;
            margin-bottom: 1.25rem !important;
        }
        .icon-blob i {
            font-size: 2rem !important;
        }
        /* Tighten row spacing */
        .row.justify-content-center.g-5 {
            --bs-gutter-x: 1rem !important;
            --bs-gutter-y: 1.25rem !important;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>" defer></script>
</body>
</html>
