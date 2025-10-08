<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noteId = intval($_POST['note_id']);
    $userId = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);

    if ($rating < 1 || $rating > 5) {
        die("Invalid rating.");
    }

    // Check if already reviewed
    $exists = $conn->query("SELECT id FROM reviews WHERE note_id=$noteId");
    if ($exists->num_rows > 0) {
        $conn->query("UPDATE reviews SET rating=$rating, created_at=NOW() 
                      WHERE note_id=$noteId AND user_id=$userId");
    } else {
        $conn->query("INSERT INTO reviews (note_id, user_id, rating, comment) 
                      VALUES ($noteId, $userId, $rating, '$comment')");
    }

    // Redirect back
    header("Location: note_preview.php");
    exit();
}
?>
