<?php
// Helper for ranks
function getTitleIcon($rank, $points) {
    if ($rank <= 5) return ["Titan", "👑"];
    if ($rank <= 10) return ["Champion", "🏆"];
    if ($rank <= 30) return ["Master", "⚔️"];
    if ($rank <= 50) return ["Crystal", "💎"];
    if ($points >= 500) return ["Bronze", "🥉"];
    if ($points >= 200) return ["Silver", "🥈"];
    if ($points >= 100) return ["Gold", "🥇"];
    return ["Player", "🎖️"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Leaderboard - StudyHub</title>

<!-- Bootstrap + Fonts + Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="<?php echo asset('css/home-style.css?v=4.0.4'); ?>">
<link rel="stylesheet" href="<?php echo asset('css/leaderboard.css?v=3.0.2'); ?>">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="<?php echo url('home/dashboard'); ?>">StudyHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>">Notes</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('resources'); ?>">Resources</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link active text-white" href="<?php echo url('leaderboard'); ?>">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('upload'); ?>">Upload Notes</a></li>
      </ul>

      <div class="nav-right-group">

      <form class="search-container me-3" method="GET" action="<?php echo url('leaderboard'); ?>">
        <input class="form-control rounded-pill" type="search" placeholder="Search user..." name="q" value="<?php echo htmlspecialchars($search ?? ''); ?>">
        <button class="search-icon-btn" type="submit"><i class="fa fa-search"></i></button>
      </form>

        <?php if ($role === 'student' || $role === 'admin' || $role === 'moderator') { 
            $dashboardLink = ($role === 'student') ? url('user/dashboard') : url('admin/dashboard');
        ?>
          <a href="<?php echo $dashboardLink; ?>" class="name text-white fw-bold" data-fullname="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>">
              👤 <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>
          </a>
        <?php } ?>
      
        <!-- Points Badge -->
        <div class="position-relative d-inline-block">
          <span id="pointsBadge"
                class="badge shadow-sm px-3 py-2 modern-points-badge">
            ⭐ <?php echo isset($_SESSION['points']) ? intval($_SESSION['points']) : 0; ?> pts
          </span>
        </div>
      </div>
  </div>
</nav>

<!-- Main Content -->
<main class="container my-4">
    <!-- Header & Toggle -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-dark mb-2">Leaderboard</h2>
        
        <!-- Dropdown for Mobile -->
        <div class="d-block d-md-none mb-2">
            <div class="dropdown">
                <button class="btn btn-white border rounded-pill shadow-sm dropdown-toggle px-4 fw-bold text-primary" type="button" data-bs-toggle="dropdown">
                    <i class="fa fa-calendar-alt me-2"></i>
                    <?php 
                        if ($period === 'last') echo "Last Month";
                        elseif ($period === 'all' || $period === 'search') echo "All Time";
                        else echo "This Month";
                    ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-center shadow-lg border-0 rounded-4 mt-2">
                    <li><a class="dropdown-item <?php echo ($period === 'current') ? 'active' : ''; ?>" href="<?php echo url('leaderboard'); ?>?period=current">This Month</a></li>
                    <li><a class="dropdown-item <?php echo ($period === 'last') ? 'active' : ''; ?>" href="<?php echo url('leaderboard'); ?>?period=last">Last Month</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item <?php echo ($period === 'all' || $period === 'search') ? 'active' : ''; ?>" href="<?php echo url('leaderboard'); ?>?period=all">All Time</a></li>
                </ul>
            </div>
        </div>

        <!-- Pills for Desktop -->
        <div class="d-none d-md-flex justify-content-center">
            <div class="nav-pills-container bg-white p-1 rounded-pill shadow-sm border">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 <?php echo ($period === 'current') ? 'active' : ''; ?>" href="<?php echo url('leaderboard'); ?>?period=current">This Month</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 <?php echo ($period === 'last') ? 'active' : ''; ?>" href="<?php echo url('leaderboard'); ?>?period=last">Last Month</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 <?php echo ($period === 'all' || $period === 'search') ? 'active' : ''; ?>" href="<?php echo url('leaderboard'); ?>?period=all">All Time</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php if ($period === 'search'): ?>
            <p class="mt-2 text-muted small">Showing results for: "<?php echo htmlspecialchars($search); ?>"</p>
        <?php endif; ?>
    </div>
    
    <div class="leaderboard-list">
        <?php
        $rank = 1;
        if (empty($players)) {
            echo "<div class='text-center p-5 bg-white rounded-4 shadow-sm'>
                    <i class='fa fa-users fa-3x text-muted opacity-25 mb-3 d-block'></i>
                    <h5 class='text-secondary'>No players found for this period.</h5>
                    <p class='text-muted small'>Be the first to earn points this month!</p>
                  </div>";
        } else {
            foreach ($players as $player):
                $points = intval($player['points']);
                list($title, $icon) = getTitleIcon($rank, $points);
                
                // Medal logic
                $medal = "";
                $cardClass = "player-card-modern";
                if ($rank === 1) { $medal = "🥇"; $cardClass .= " top-1"; }
                elseif ($rank === 2) { $medal = "🥈"; $cardClass .= " top-2"; }
                elseif ($rank === 3) { $medal = "🥉"; $cardClass .= " top-3"; }
            ?>
            <div class="<?php echo $cardClass; ?> d-flex align-items-center justify-content-between p-3 mb-2 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center gap-3 overflow-hidden">
                    <div class="rank-circle d-flex align-items-center justify-content-center flex-shrink-0">
                        <?php echo $medal ?: $rank; ?>
                    </div>
                    <div class="avatar-circle flex-shrink-0">
                        <?php echo strtoupper(substr($player['name'], 0, 1)); ?>
                    </div>
                    <div class="player-details text-truncate">
                        <div class="fw-bold text-dark text-truncate"><?php echo htmlspecialchars($player['name']); ?></div>
                        <div class="small text-muted text-uppercase ls-1" style="font-size: 0.7rem;">
                            <?php echo $icon . ' ' . $title; ?>
                        </div>
                    </div>
                </div>
                <div class="points-section text-end flex-shrink-0 ms-2">
                    <div class="fw-800 text-primary h5 mb-0"><?php echo number_format($points); ?></div>
                    <div class="small text-muted" style="font-size: 0.65rem;">POINTS</div>
                </div>
            </div>
            <?php $rank++; endforeach; 
        }
        ?>
    </div>
</main>

<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>"></script>

</body>
</html>
