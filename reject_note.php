<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: main_index.php#login");
    exit();
}

if (isset($_GET['id'])) {
    $noteId = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE notes SET status='rejected' WHERE id=?");
    $stmt->bind_param("i", $noteId);
    $stmt->execute();
}

header("Location: pending_notes.php");
exit();
