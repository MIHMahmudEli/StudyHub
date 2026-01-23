<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($note['title']); ?> - Note Preview</title>
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo asset('css/note_preview.css?v=3.2'); ?>">
<style>
  /* Force reading mode on mobile */
  <?php 
  $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
  $isMobile = preg_match('/android|iphone|ipad|ipod/', $userAgent);
  if ($isMobile): ?>
  body { overflow: hidden; }
  body.reading-mode { overflow: auto; }
  body.reading-mode .sidebar { display: none; }
  body.reading-mode .main-view { width: 100%; height: 100vh; }
  body.reading-mode .preview-frame, body.reading-mode .preview-image { width: 100%; height: 100%; border-radius: 0; }
  <?php endif; ?>
</style>
</head>
<body class="<?php echo $isMobile ? 'reading-mode' : ''; ?>">

<div class="preview-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="note-card">
            <h1><?php echo htmlspecialchars($note['title']); ?></h1>
            <div class="info-item"><i class="fas fa-user"></i> <?php echo htmlspecialchars($note['author_name'] ?? 'Unknown'); ?></div>
            <div class="info-item">
                <i class="fas fa-book"></i>
                <span class="badge-info"><?php echo htmlspecialchars($note['subject']); ?></span>
                <span class="badge-info"><?php echo htmlspecialchars($note['course_code']); ?></span>
            </div>
            <div class="info-item"><i class="fas fa-calendar-alt"></i> Uploaded: <?php echo date('F j, Y', strtotime($note['created_at'])); ?></div>
            <div class="info-item"><i class="fas fa-download"></i> Downloads: <?php echo intval($note['downloads']); ?></div>
            <div class="info-item">
                <i class="fas fa-star text-warning"></i> Rating: <span class="avg-rating ms-1"><?php echo number_format($note['avg_rating'], 1); ?></span> / 5.0
            </div>
            <div class="desc">
                <h4>Description:</h4>
                <p><?php echo nl2br(htmlspecialchars($note['description'])); ?></p>
            </div>
            
            <div class="rating-section mt-3 pt-3 border-top">
                <h6>Rate this Note:</h6>
                <div class="stars-input" id="starsInput">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star rating-star <?php echo $i <= $userRating ? 'active' : ''; ?>" data-value="<?php echo $i; ?>"></i>
                    <?php endfor; ?>
                </div>
                <div class="rating-btn-container">
                    <button class="btn btn-primary btn-sm" id="submitRating" 
                            data-note-id="<?php echo $note['id']; ?>"
                            data-url="<?php echo url('note/rate'); ?>">
                        <i class="fas fa-star me-1"></i> Submit Rating
                    </button>
                </div>
                <div id="ratingMsg" class="rating-message mt-2" style="display:none; font-size: 0.85rem;"></div>
            </div>
        </div>
        <div class="buttons">
            <a href="<?php echo url('note/download'); ?>?id=<?php echo $note['id']; ?>" class="download-btn"><i class="fas fa-download"></i> Download</a>
            <button class="read-btn" id="toggleReading"><i class="fas fa-book-reader"></i> Reading Mode</button>
            
            <?php 
            if (isset($track) && $track === 'admin') {
                $backLink = url('admin/pending_notes');
            } elseif (isset($track) && $track === 'my_notes') {
                $backLink = url('note/my_notes');
            } elseif (isset($track) && $track === 'resource') {
                $backLink = url('resources');
            } else {
                $backLink = url('home/dashboard');
            }
            ?>
            <a href="<?php echo $backLink; ?>" class="back-btn" style="display:block; text-align:center; margin-top:10px; color:#666; text-decoration:none;"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </aside>

    <!-- Main Preview Area -->
    <main class="main-view" id="mainView">
        <?php
        $webPath = BASE_URL . '/' . $note['file_path']; 
        $fileType = strtolower($note['file_type']);
        
        if ($fileType === 'pdf') {
            echo "<embed src='$webPath#toolbar=1&navpanes=0' type='application/pdf' class='preview-frame'>";
        } elseif (in_array($fileType, ['jpg','jpeg','png','gif'])) {
            echo "<img src='$webPath' class='preview-image' alt='Note Image'>";
        } elseif (in_array($fileType, ['doc','docx'])) {
            // High-fidelity docx-preview container
            echo "<div id='docx-preview' class='preview-frame p-4 bg-secondary-subtle' style='overflow-y: auto; text-align: left;'>
                    <div class='text-center py-5' id='docx-loading'>
                        <div class='spinner-border text-primary' role='status'></div>
                        <p class='mt-2'>Rendering document with high fidelity...</p>
                    </div>
                  </div>";
        } else {
            echo "<div class='loading-msg'>Preview not available for this file type.</div>";
        }
        ?>
    </main>
</div>

<script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
<script src="https://unpkg.com/docx-preview/dist/docx-preview.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Focus preview on load
    const frame = document.querySelector('.preview-frame, .preview-image');
    if (frame) {
        frame.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Docx Rendering logic (High Fidelity)
    const docxContainer = document.getElementById('docx-preview');
    if (docxContainer) {
        const fileUrl = "<?php echo $webPath; ?>";
        fetch(fileUrl)
            .then(response => response.arrayBuffer())
            .then(arrayBuffer => {
                const options = {
                    debug: false,
                    experimental: true,
                    useMathMLPolyfill: true
                };
                docx.renderAsync(arrayBuffer, docxContainer, null, options)
                    .then(x => {
                        const loading = document.getElementById('docx-loading');
                        if (loading) loading.remove();
                        console.log("Docx rendered successfully");
                    })
                    .catch(err => {
                        console.error(err);
                        docxContainer.innerHTML = "<div class='alert alert-danger m-4'>Error rendering document. Please try downloading it.</div>";
                    });
            })
            .catch(err => {
                console.error(err);
                docxContainer.innerHTML = "<div class='alert alert-danger m-4'>Failed to fetch document.</div>";
            });
    }

    // Toggle reading mode
    const toggleBtn = document.getElementById('toggleReading');
    toggleBtn.addEventListener('click', function() {
        document.body.classList.toggle('reading-mode');
        this.innerHTML = document.body.classList.contains('reading-mode') 
            ? '<i class="fas fa-times"></i> Exit Reading Mode' 
            : '<i class="fas fa-book-reader"></i> Reading Mode';
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
<script src="<?php echo asset('js/rate-note.js'); ?>"></script>
</body>
</html>
