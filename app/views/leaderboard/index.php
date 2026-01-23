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
<title>Leaderboard - Notes Hub</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <a class="navbar-brand fw-bold text-white" href="<?php echo url('home/dashboard'); ?>">Notes Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('home/dashboard'); ?>?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link active text-white" href="<?php echo url('leaderboard'); ?>">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo url('resources'); ?>">Resources</a></li>
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
<main class="container my-5">
    <h2 class="text-center mb-4">Leaderboard</h2>
    
    <div class="d-flex flex-column">
        <?php
        $rank = 1;
        if (empty($players)) {
            echo "<div class='text-center p-4'>No players found.</div>";
        } else {
            foreach ($players as $player):
                $points = intval($player['points']);
                list($title, $icon) = getTitleIcon($rank, $points);
            ?>
            <div class="player-card">
                <div class="d-flex align-items-center">
                    <div class="rank-badge"><?php echo $rank; ?>.</div>
                    <div class="player-info">
                        <div class="player-name"><?php echo htmlspecialchars($player['name']); ?></div>
                        <div class="player-title"><?php echo $icon . ' ' . $title; ?></div>
                    </div>
                </div>
                <div class="trophy-count"><?php echo $points; ?> 🏆</div>
            </div>
            <?php $rank++; endforeach; 
        }
        ?>
    </div>
</main>

<script src="<?php echo asset('js/home-script.js?v=4.0.3'); ?>"></script>

</body>
</html>
