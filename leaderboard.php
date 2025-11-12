<?php
session_start();
include("includes/db.php"); // DB connection

if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit;
}

$userId = intval($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'student';
$searchTerm = '';

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $searchTerm = mysqli_real_escape_string($conn, trim($_GET['q']));
}

// Fetch users sorted by points descending
$query = "SELECT id, name, points FROM users " . 
         ($searchTerm ? "WHERE name LIKE '%$searchTerm%' " : "") . 
         "ORDER BY points DESC";

$result = mysqli_query($conn, $query);
$players = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = $row;
    }
}

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
<link rel="stylesheet" href="assets/css/home-style.css?v=4.0.4">
<link rel="stylesheet" href="assets/css/leaderboard.css?v=3.0.2">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="favicon.svg">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

</head>
<body class="bg-light">

<!-- Navbar (same as home.php) -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="home.php">Notes Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='home.php' ? 'active' : ''; ?>" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white <?php echo isset($_GET['bookmarks']) ? 'active' : ''; ?>" href="home.php?bookmarks=1">Bookmarks</a></li>
        <li class="nav-item"><a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='leaderboard.php' ? 'active' : ''; ?>" href="leaderboard.php">Leaderboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="upload.php">Upload Notes</a></li>
      </ul>

      <form class="d-flex me-3" method="GET" action="leaderboard.php">
        <input class="form-control me-2 rounded-pill" type="search" placeholder="Search user..." name="q" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button class="btn btn-outline-light rounded-pill" type="submit">Search</button>
      </form>

      <?php if ($role === 'student') { ?>
            <a href="user_dashboard.php" class="name me-3 text-white fw-bold">
                👤 Hello, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Guest"; ?>
            </a>
      <?php } else { ?>
            <a href="admin_dashboard.php" class="name me-3 text-white fw-bold">
                👤 Hello, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Guest"; ?>
            </a>
      <?php } ?>
      <!-- Points Badge -->
        <div class="position-relative d-inline-block">
          <span id="pointsBadge"
                class="badge bg-gradient text-dark fw-semibold shadow-sm px-3 py-2 modern-points-badge">
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

<!-- Main Content -->
<main class="container my-5">
    <h2 class="text-center mb-4">Leaderboard</h2>
    
    <div class="d-flex flex-column">
        <?php
        $rank = 1;
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
        <?php $rank++; endforeach; ?>
    </div>
</main>

<script src="assets/js/home-script.js?v=4.0.3"></script>

</body>
</html>
