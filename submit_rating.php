<?php
session_start();
include("includes/db.php");

// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "You must be logged in to rate.";
    header("Location: preview_note.php?id=" . intval($_POST['note_id']));
    exit();
}

// Check POST data
if (!isset($_POST['note_id'], $_POST['rating'])) {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: preview_note.php?id=" . intval($_POST['note_id']));
    exit();
}

$noteId = intval($_POST['note_id']);
$rating = intval($_POST['rating']);
$userId = intval($_SESSION['user_id']);

// Validate rating
if ($rating < 1 || $rating > 5) {
    $_SESSION['error_msg'] = "Invalid rating value.";
    header("Location: preview_note.php?id=" . $noteId);
    exit();
}

// Check if user already rated
$stmt = $conn->prepare("SELECT id FROM reviews WHERE user_id=? AND note_id=?");
$stmt->bind_param("ii", $userId, $noteId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // User already rated, do nothing
    $stmt->close();
    $_SESSION['success_msg'] = "You have already rated this note.";
} else {
    // Insert new rating
    $stmt->close();
    $insert = $conn->prepare("INSERT INTO reviews (note_id, user_id, rating) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $noteId, $userId, $rating);
    $insert->execute();
    $insert->close();

    // Avg rating is auto-updated by your triggers
    $_SESSION['success_msg'] = "Rating submitted successfully.";
}

// Redirect back to the note preview page
header("Location: preview_note.php?id=" . $noteId);
exit();
