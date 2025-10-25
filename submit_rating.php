<?php
session_start();
include("includes/db.php");
header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to rate notes.']);
    exit();
}

// Read POST data
$noteId = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$userId = intval($_SESSION['user_id']);

// Validate input
if ($noteId <= 0 || $rating <= 0) {
    error_log("Rating submission error: note_id=$noteId, rating=$rating, user_id=$userId");
    echo json_encode(['success' => false, 'message' => 'Invalid request. Missing or invalid note_id or rating.']);
    exit();
}

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5.']);
    exit();
}

try {
    // Check if already rated
    $checkStmt = $conn->prepare("SELECT id FROM reviews WHERE user_id = ? AND note_id = ?");
    $checkStmt->bind_param("ii", $userId, $noteId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $updateStmt = $conn->prepare("UPDATE reviews SET rating = ?, updated_at = NOW() WHERE user_id = ? AND note_id = ?");
        $updateStmt->bind_param("iii", $rating, $userId, $noteId);
        $updateStmt->execute();
        $updateStmt->close();
        $message = 'Rating updated successfully!';
    } else {
        $insertStmt = $conn->prepare("INSERT INTO reviews (note_id, user_id, rating, created_at) VALUES (?, ?, ?, NOW())");
        $insertStmt->bind_param("iii", $noteId, $userId, $rating);
        $insertStmt->execute();
        $insertStmt->close();
        $message = 'Rating submitted successfully!';
    }
    $checkStmt->close();

    // Get updated average rating
    $avgStmt = $conn->prepare("SELECT ROUND(AVG(rating), 1) AS avg_rating FROM reviews WHERE note_id = ?");
    $avgStmt->bind_param("i", $noteId);
    $avgStmt->execute();
    $avgRow = $avgStmt->get_result()->fetch_assoc();
    $newAvg = $avgRow['avg_rating'] ?? 0.0;
    $avgStmt->close();

    // Update the notes table with new average rating
    $updateNoteStmt = $conn->prepare("UPDATE notes SET avg_rating = ? WHERE id = ?");
    $updateNoteStmt->bind_param("di", $newAvg, $noteId);
    $updateNoteStmt->execute();
    $updateNoteStmt->close();

    echo json_encode(['success' => true, 'message' => $message, 'new_avg' => $newAvg]);

} catch (Exception $e) {
    error_log("Database error in submit_rating.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred. Please try again.']);
}
?>