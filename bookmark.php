<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Login required";
    exit;
}

$userId = $_SESSION['user_id'];
$noteId = intval($_POST['note_id']);

// Toggle bookmark
$query = "SELECT * FROM bookmarks WHERE user_id = ? AND note_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userId, $noteId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove bookmark
    $delete = $conn->prepare("DELETE FROM bookmarks WHERE user_id = ? AND note_id = ?");
    $delete->bind_param("ii", $userId, $noteId);
    $delete->execute();
    echo "removed";
} else {
    // Add bookmark
    $insert = $conn->prepare("INSERT INTO bookmarks (user_id, note_id) VALUES (?, ?)");
    $insert->bind_param("ii", $userId, $noteId);
    $insert->execute();
    echo "added";
}
?>
