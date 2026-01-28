<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Notes - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/upload-style.css?v=3.1'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
</head>
<body>

<div class="container py-5">
    <div class="upload-card mx-auto shadow-lg p-4 rounded-4">
        <h2 class="text-center mb-4 fw-bold text-primary">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload a Note
        </h2>

        <?php if (isset($error)): ?>
            <div id="upload-message" class="alert alert-danger text-center fw-medium"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div id="upload-message" class="alert alert-success text-center fw-medium"><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo url('note/store'); ?>" enctype="multipart/form-data" id="upload-form">
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
                <input type="text" class="form-control" id="subject-input" name="subject" placeholder="Course/Subject" required autocomplete="off" 
                       data-url="<?php echo asset('data/courses.json'); ?>">
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
            <a href="<?php echo url('home/dashboard'); ?>" class="text-decoration-none text-secondary fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Notes
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/upload-script.js?v3.0'); ?>"></script>
<script src="<?php echo asset('js/course-autocomplete.js?v3.0'); ?>"></script>

</body>
</html>
