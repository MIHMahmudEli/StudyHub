<?php
session_start();
include("includes/db.php"); // DB connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Get search term if any
$searchTerm = '';
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $searchTerm = mysqli_real_escape_string($conn, trim($_GET['q']));
}

// Fetch users sorted by points descending, optionally filtered
if ($searchTerm !== '') {
    $query = "SELECT id, name, points FROM users 
              WHERE name LIKE '%$searchTerm%' 
              ORDER BY points DESC";
} else {
    $query = "SELECT id, name, points FROM users ORDER BY points DESC";
}

$result = mysqli_query($conn, $query);

// Collect results
$players = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = $row;
    }
}

// Function to get title and icon based on rank/points
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leaderboard - Notes Hub</title>
<link rel="stylesheet" href="assets/css/home-style.css">
<link rel="stylesheet" href="assets/css/leaderboard.css">
<link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
<header>
    <section class="nav-center">
        <a href="home.php">🏠 Home</a>
        <a href="home.php?bookmarks=1">🔖 Bookmark</a>
        <a href="leaderboard.php" class="active-link">🏆 Leaderboard</a>
        <a href="#">📅 Event</a>
        <a href="upload.php">📘 Upload Notes</a>
    </section>

    <!-- Nav right with search -->
    <section class="nav-right">
        <form method="GET" action="leaderboard.php" class="search-form">
            <input type="text" name="q" id="searchBox" placeholder="🔍 Search user..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>

        <a href="profile.php">
            👤 Hello, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Guest"; ?>
        </a>
        <p>⭐ <?php echo isset($_SESSION['points']) ? intval($_SESSION['points']) : 0; ?> pts</p>
    </section>
</header>

<main>
    <div class="leaderboard">
        <h2>Leaderboard</h2>

        <?php 
        $rank = 1;
        foreach ($players as $player): 
            $points = intval($player['points']);
            list($title, $icon) = getTitleIcon($rank, $points);
        ?>
        <div class="player-card rank-<?php echo $rank; ?>">
            <div class="rank-badge"><?php echo $rank; ?>.</div>
            <div class="player-info">
                <div class="player-top">
                    <span class="player-name"><?php echo htmlspecialchars($player['name']); ?></span>
                </div>
                <div class="player-title">
                    <span class="title-icon"><?php echo $icon; ?></span>
                    <?php echo $title; ?>
                </div>
            </div>
            <div class="trophy-count">
                <span><?php echo $points; ?></span> <span class="trophy-icon">🏆</span>
            </div>
        </div>
        <?php 
            $rank++;
        endforeach; 
        ?>
    </div>
</main>
</body>
</html>
