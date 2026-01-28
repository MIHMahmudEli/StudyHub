<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($subject); ?> - <?php echo ucfirst($term); ?> Resources - StudyHub</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo asset('css/home-style.css?v=4.0.4'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .resource-list-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: #fff;
    }
    .resource-list-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .file-type-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .resource-list-card {
            padding: 0.75rem !important;
            border-radius: 12px !important;
        }
        .file-type-icon {
            width: 40px !important;
            height: 40px !important;
            font-size: 1.25rem !important;
            border-radius: 10px !important;
        }
        .resource-list-card h6 {
            font-size: 0.95rem !important;
            margin-bottom: 0.25rem !important;
        }
        .resource-list-card p.text-muted {
            font-size: 0.8rem !important;
        }
        .btn-download-responsive {
            width: 42px !important;
            height: 42px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }
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

<main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-nowrap gap-3">
        <div style="min-width: 0;">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-1 flex-nowrap overflow-hidden">
                <li class="breadcrumb-item text-truncate" style="max-width: 80px; flex-shrink: 1;"><a href="<?php echo url('resources'); ?>" class="text-decoration-none">Resources</a></li>
                <li class="breadcrumb-item text-truncate" style="max-width: 100px; flex-shrink: 1;"><a href="<?php echo url('resources/subject'); ?>?subject=<?php echo urlencode($subject); ?>" class="text-decoration-none"><?php echo htmlspecialchars($subject); ?></a></li>
                <li class="breadcrumb-item active flex-shrink-0" aria-current="page"><?php echo ucfirst($term); ?></li>
              </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0 text-truncate" style="min-width: 0;"><?php echo ucfirst($term); ?> Term Resources</h3>
        </div>
        <div class="text-end flex-shrink-0 d-none d-md-block">
            <span class="badge bg-primary px-3 py-2 rounded-pill"><?php echo count($resources); ?> Resources</span>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($resources)): ?>
            <?php foreach ($resources as $res): ?>
                <div class="col-12">
                    <div class="card resource-list-card shadow-sm p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="file-type-icon bg-primary-subtle text-primary">
                                <?php 
                                $ext = strtolower($res['file_type']);
                                if ($ext === 'pdf') echo '<i class="fa fa-file-pdf"></i>';
                                elseif (in_array($ext, ['doc','docx'])) echo '<i class="fa fa-file-word"></i>';
                                elseif (in_array($ext, ['ppt','pptx'])) echo '<i class="fa fa-file-powerpoint"></i>';
                                else echo '<i class="fa fa-file"></i>';
                                ?>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <h6 class="fw-bold mb-1 text-dark text-truncate"><?php echo htmlspecialchars($res['title']); ?></h6>
                                <p class="text-muted small mb-0 d-flex gap-3">
                                    <span class="text-truncate"><i class="fa fa-tag me-1"></i> <?php echo htmlspecialchars($res['course_code']); ?></span>
                                    <span class="d-none d-md-inline-block text-truncate"><i class="fa fa-user me-1"></i> <?php echo htmlspecialchars($res['uploader_name'] ?? 'System'); ?></span>
                                </p>
                            </div>
                            <div class="text-end">
                                <?php 
                                $isBookmarked = $res['bookmarked'] ?? false; 
                                $btnClass = $isBookmarked ? 'btn-danger' : 'btn-outline-primary';
                                ?>
                                <button class="btn <?php echo $btnClass; ?> bookmark-btn rounded-pill me-2 px-3 resource-bookmark-btn" 
                                        data-id="<?php echo $res['id']; ?>" 
                                        data-type="resource" 
                                        data-url="<?php echo url('resources/bookmark'); ?>"
                                        title="Bookmark"
                                        style="opacity: 0; transition: opacity 0.3s ease;">
                                    <i class="fa fa-bookmark"></i>
                                </button>

                                <a href="<?php echo url('note/download'); ?>?id=<?php echo $res['id']; ?>&type=resource" class="btn btn-success btn-download-responsive rounded-pill px-4" title="Download Resource">
                                    <i class="fa fa-download"></i> <span class="d-none d-md-inline ms-1">Download</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-4"><i class="fa fa-folder-open fa-4x text-muted opacity-25"></i></div>
                <h4 class="text-secondary">No resources found for this term yet.</h4>
                <p class="text-muted">Check back later or try another term.</p>
                <a href="<?php echo url('resources/subject'); ?>?subject=<?php echo urlencode($subject); ?>" class="btn btn-primary mt-2">Go Back</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>" defer></script>
</body>
</html>
