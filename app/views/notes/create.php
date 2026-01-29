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

        <!-- Toast Notification (Overlay) -->
        <?php if (isset($error)): ?>
        <div class="toast-notification" id="uploadToast">
            <div class="toast-content">
                <div class="toast-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%)">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="toast-body">
                    <h6 class="toast-title mb-1" style="color: #991b1b">Error!</h6>
                    <p class="toast-message mb-0"><?= htmlspecialchars($error); ?></p>
                </div>
                <button type="button" class="toast-close" onclick="closeUploadToast()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-progress" style="background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%)"></div>
        </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
        <div class="toast-notification" id="uploadToast">
            <div class="toast-content">
                <div class="toast-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%)">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="toast-body">
                    <h6 class="toast-title mb-1" style="color: #065f46">Success!</h6>
                    <p class="toast-message mb-0"><?= htmlspecialchars($success); ?></p>
                </div>
                <button type="button" class="toast-close" onclick="closeUploadToast()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-progress" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%)"></div>
        </div>
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

<script>
    // Toast Notification System
    function closeUploadToast() {
        const toast = document.getElementById('uploadToast');
        if (toast) {
            toast.classList.add('toast-hide');
            setTimeout(() => {
                toast.remove();
            }, 400);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const uploadToast = document.getElementById('uploadToast');
        if (uploadToast) {
            // Show toast with animation
            setTimeout(() => {
                uploadToast.classList.add('toast-show');
            }, 100);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                closeUploadToast();
            }, 5000);
            
            // Progress bar animation
            const progressBar = uploadToast.querySelector('.toast-progress');
            if (progressBar) {
                progressBar.style.animation = 'progress 5s linear forwards';
            }
        }
    });
</script>

<style>
    /* Modern Toast Notification Styles */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 350px;
        max-width: 400px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fffe 100%);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transform: translateX(450px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .toast-notification.toast-show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .toast-notification.toast-hide {
        transform: translateX(450px);
        opacity: 0;
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        position: relative;
    }
    
    .toast-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .toast-body {
        flex: 1;
    }
    
    .toast-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    
    .toast-message {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }
    
    .toast-close {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(107, 114, 128, 0.1);
        border-radius: 8px;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    
    .toast-close:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #374151;
        transform: rotate(90deg);
    }
    
    .toast-progress {
        height: 4px;
        transform-origin: left;
        border-radius: 0 0 16px 16px;
    }
    
    @keyframes progress {
        from {
            transform: scaleX(1);
        }
        to {
            transform: scaleX(0);
        }
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .toast-notification {
            top: 10px;
            right: 10px;
            left: 10px;
            min-width: auto;
            max-width: none;
        }
        
        .toast-notification.toast-show {
            transform: translateY(0);
        }
        
        .toast-notification.toast-hide {
            transform: translateY(-150px);
        }
        
        .toast-notification {
            transform: translateY(-150px);
        }
    }
</style>

</body>
</html>
