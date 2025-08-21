<?php
session_start();
include("includes/db.php"); // connect to database

// Make sure the user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

if(isset($_POST['upload'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $subject = $_POST['subject'];
    $tags = $_POST['tags'];
    $uploader = $_SESSION['user_id'];

    // Handle file upload
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
        $file_name = $_FILES['file']['name'];
        $tmp_name  = $_FILES['file']['tmp_name'];
        $target_dir = "assets/uploads/";
        $file_path = $target_dir . basename($file_name);

        // Move file to uploads folder
        if(move_uploaded_file($tmp_name, $file_path)){
            // Save note info into database
            $sql = "INSERT INTO notes (uploader_id, title, description, subject, tags, file_path) 
                    VALUES ('$uploader', '$title', '$description', '$subject', '$tags', '$file_path')";

            if(mysqli_query($conn, $sql)){
                $message = "Note uploaded successfully!";
            } else {
                $message = "Database error: " . mysqli_error($conn);
            }
        } else {
            $message = "Failed to move uploaded file.";
        }
    } else {
        $message = "Please select a file to upload.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Notes - StudyHub</title>
</head>
<body>
    <h2>Upload a Note</h2>

    <!-- Show success or error message -->
    <?php if(isset($message)) echo "<p>$message</p>"; ?>

    <!-- Upload form -->
    <form method="POST" action="upload.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Note title" required><br><br>
        <textarea name="description" placeholder="Description"></textarea><br><br>
        <input type="text" name="subject" placeholder="Subject"><br><br>
        <input type="text" name="tags" placeholder="Tags (comma separated)"><br><br>
        <input type="file" name="file" required><br><br>
        <button type="submit" name="upload">Upload</button>
    </form>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</body>
</html>
