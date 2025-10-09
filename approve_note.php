<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: main_index.php#login");
    exit();
}

if (isset($_GET['id'])) {
    $noteId = intval($_GET['id']);

    // Approve note
    $stmt = $conn->prepare("UPDATE notes SET status='approved' WHERE id=?");
    $stmt->bind_param("i", $noteId);
    if ($stmt->execute()) {
        //  Get uploader of this note
        $q = $conn->prepare("SELECT uploader_id FROM notes WHERE id=?");
        $q->bind_param("i", $noteId);
        $q->execute();
        $q->bind_result($uploaderId);
        $q->fetch();
        $q->close();

        if ($uploaderId) {
            //  Award +5 points to uploader
            $p = $conn->prepare("UPDATE users SET points = points + 5 WHERE id=?");
            $p->bind_param("i", $uploaderId);
            $p->execute();
            $p->close();

            // upload event
            $type = 'upload';
            $event = $conn->prepare("INSERT INTO events (user_id, `type`) VALUES (?, ?)");
            $event->bind_param("is", $_SESSION['user_id'], $type);
            $event->execute();
        }
    }
    $stmt->close();
}

header("Location: pending_notes.php");
exit();
