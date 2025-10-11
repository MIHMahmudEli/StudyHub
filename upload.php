<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit;
}

$message = "";

// Handle upload
if (isset($_POST['upload'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $course_code = trim($_POST['course_code']);
    $uploader = $_SESSION['user_id'];

    if (isset($_FILES['file'])) {
        $fileError = $_FILES['file']['error'];
        if ($fileError === UPLOAD_ERR_INI_SIZE || $fileError === UPLOAD_ERR_FORM_SIZE) {
            $message = "⚠️ File is too large! Maximum upload size is 40 MB.";
        } elseif ($fileError !== UPLOAD_ERR_OK) {
            $message = "❌ File upload error. Code: " . $fileError;
        } else {
            $file_name = $_FILES['file']['name'];
            $tmp_name  = $_FILES['file']['tmp_name'];
            $target_dir = "assets/uploads/";
            $file_path = $target_dir . basename($file_name);
            $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

            if ($_FILES['file']['size'] > 40 * 1024 * 1024) {
                $message = "⚠️ File exceeds 40 MB limit!";
            } else {
                if (move_uploaded_file($tmp_name, $file_path)) {
                    date_default_timezone_set('Asia/Dhaka');
                    $created_at = date('Y-m-d H:i:s');

                    $stmt = $conn->prepare("
                        INSERT INTO notes (uploader_id, title, description, subject, course_code, file_path, file_type, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param("isssssss", $uploader, $title, $description, $subject, $course_code, $file_path, $file_type, $created_at);

                    if ($stmt->execute()) {
                        $message = "✅ Note uploaded successfully! Awaiting admin approval.";
                        $timestamp = date('Y-m-d h:i:s');
                        $type = 'upload';
                        $event = $conn->prepare("INSERT INTO events (user_id, `type`, `at`) VALUES (?, ?, ?)");
                        $event->bind_param("iss", $_SESSION['user_id'], $type, $timestamp);
                        $event->execute();
                    } else {
                        $message = "❌ Database error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "❌ Failed to move uploaded file.";
                }
            }
        }
    } else {
        $message = "⚠️ Please select a file to upload.";
    }
}

$courses = json_decode(file_get_contents("assets/data/courses.json"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Notes - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/upload-style.css?v=3.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>

<div class="container py-5">
    <div class="upload-card mx-auto shadow-lg p-4 rounded-4">
        <h2 class="text-center mb-4 fw-bold text-primary"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload a Note</h2>

        <?php if ($message): ?>
            <div class="alert alert-info text-center fw-medium"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="upload.php" enctype="multipart/form-data" id="upload-form">
            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold"><i class="fa fa-heading me-2"></i>Title</label>
                <input type="text" class="form-control" name="title" placeholder="Enter note title" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fa fa-align-left me-2"></i>Description</label>
                <textarea class="form-control" name="description" placeholder="Write a short description" rows="3"></textarea>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold"><i class="fa fa-book me-2"></i>Subject / Course</label>
                <input type="text" class="form-control" id="subject-input" name="subject" placeholder="Course/Subject" required autocomplete="off">
                <input type="hidden" name="course_code" id="course-code">
                <div id="suggestions" class="suggestions"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fa fa-file-arrow-up me-2"></i>Upload File</label>
                <input type="file" class="form-control" name="file" id="file" required>
                <div class="form-text" id="file-name">No file chosen</div>
            </div>

            <button type="submit" name="upload" class="btn btn-gradient w-100 py-2 fw-semibold">
                <i class="fa-solid fa-upload me-1"></i> Upload
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="home.php" class="text-decoration-none text-secondary fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/upload-script.js?v3.0"></script>
<script src="assets/js/course-autocomplete.js?v3.0"></script>

</body>
</html>
