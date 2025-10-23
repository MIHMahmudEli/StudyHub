<?php
session_start();
include("includes/db.php");
include("includes/redirect_helper.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    
    // Check if it's an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // AJAX request - return JSON error
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not authenticated']);
    } else {
        // Regular request - redirect
        redirect("main_index#login");
    }
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