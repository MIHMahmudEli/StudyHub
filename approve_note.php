<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: index.php#login");
    exit();
}

if (isset($_GET['id'])) {
    $noteId = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE notes SET status='approved' WHERE id=?");
    $stmt->bind_param("i", $noteId);
    $stmt->execute();
}

header("Location: pending_notes.php");
exit();
