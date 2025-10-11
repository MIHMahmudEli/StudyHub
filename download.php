<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$noteId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

// Get file info + uploader
$query = "SELECT id, file_path, title, uploader_id FROM notes WHERE id = ? AND status = 'approved'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $noteId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("File not found or not approved.");
}

$note = $result->fetch_assoc();
$file = $note['file_path'];
$uploaderId = $note['uploader_id'];

// 1. Update downloads count
$update = $conn->prepare("UPDATE notes SET downloads = downloads + 1 WHERE id = ?");
$update->bind_param("i", $noteId);
$update->execute();

// 2. Log event
    date_default_timezone_set('Asia/Dhaka');
    $timestamp = date('Y-m-d h:i:s');
    $eventType = 'download';

    $event = $conn->prepare("INSERT INTO events (user_id, note_id, `type`, `at`)  VALUES (?, ?, ?, ?) ");
    $event->bind_param("iiss", $userId, $noteId, $eventType, $timestamp);
    $event->execute();

    if ($event->error) {
    die("Execute failed: " . $event->error);
    }


// 3. Award points to downloader (+1 per download)
$points = $conn->prepare("UPDATE users SET points = points + 1 WHERE id = ?");
$points->bind_param("i", $userId);
$points->execute();

// 4. Award points to uploader (+2 when their note is downloaded)
if ($uploaderId && $uploaderId != $userId) { 
    // prevent giving points twice if uploader downloads their own file
    $ownerPoints = $conn->prepare("UPDATE users SET points = points + 2 WHERE id = ?");
    $ownerPoints->bind_param("i", $uploaderId);
    $ownerPoints->execute();
    $ownerPoints->close();
}

// 5. Download file
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
