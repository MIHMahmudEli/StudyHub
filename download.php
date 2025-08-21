<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$noteId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

// Get file info
$query = "SELECT id, file_path, title FROM notes WHERE id = ? AND status = 'approved'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $noteId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("File not found or not approved.");
}

$note = $result->fetch_assoc();
$file = $note['file_path'];

// 1. Update downloads count
$update = $conn->prepare("UPDATE notes SET downloads = downloads + 1 WHERE id = ?");
$update->bind_param("i", $noteId);
$update->execute();

// 2. Log event
$event = $conn->prepare("INSERT INTO events (user_id, note_id, type) VALUES (?, ?, 'download')");
$event->bind_param("ii", $userId, $noteId);
$event->execute();

// 3. Award points (+5 per download)
$points = $conn->prepare("UPDATE users SET points = points + 5 WHERE id = ?");
$points->bind_param("i", $userId);
$points->execute();

// 4. Download file
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Content-Length: ' . filesize($file));
    flush();
    readfile($file);
    exit;
} else {
    die("File missing on server.");
}
?>
