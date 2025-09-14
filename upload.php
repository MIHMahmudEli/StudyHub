<?php
session_start();
include("includes/db.php");

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = "";

// Handle upload
if (isset($_POST['upload'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $course_code = trim($_POST['course_code']); // hidden field
    $uploader = $_SESSION['user_id'];

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = $_FILES['file']['name'];
        $tmp_name  = $_FILES['file']['tmp_name'];
        $target_dir = "assets/uploads/";
        $file_path = $target_dir . basename($file_name);
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

        if (move_uploaded_file($tmp_name, $file_path)) {
            $stmt = $conn->prepare("
                INSERT INTO notes (uploader_id, title, description, subject, course_code, file_path, file_type)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("issssss", $uploader, $title, $description, $subject, $course_code, $file_path, $file_type);

            if ($stmt->execute()) {
                $message = "✅ Note uploaded successfully!";
            } else {
                $message = "❌ Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "❌ Failed to move uploaded file.";
        }
    } else {
        $message = "⚠️ Please select a file to upload.";
    }
}

// Load courses JSON
$courses = json_decode(file_get_contents("assets/data/courses.json"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Notes - StudyHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/upload-style.css">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <div class="upload-container">
        <h2>Upload a Note</h2>

        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form method="POST" action="upload.php" enctype="multipart/form-data" class="upload-form" id="upload-form">
            <div class="form-group">
                <i class="fas fa-heading"></i>
                <input type="text" name="title" placeholder="Note title" required>
            </div>

            <div class="form-group">
                <i class="fas fa-align-left"></i>
                <textarea name="description" placeholder="Description"></textarea>
            </div>

            <div class="form-group">
                <i class="fas fa-book"></i>
                <input type="text" id="subject-input" name="subject" placeholder="Course/Subject" required autocomplete="off">
                <input type="hidden" name="course_code" id="course-code">
                <div id="suggestions" class="suggestions"></div>
            </div>

            <div class="form-group file-input">
                <input type="file" name="file" id="file" required>
                <span id="file-name">No file chosen</span>
            </div>

            <button type="submit" name="upload" class="btn">Upload</button>
        </form>

        <p><a href="home.php">⬅ Back to Home</a></p>
    </div>

    <script>
        // Pass courses data from PHP to JS
        const courses = <?php echo json_encode($courses); ?>;
    </script>
    <script src="assets/js/course-autocomplete.js"></script>
</body>
</html>
