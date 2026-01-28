<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>StudyHub — Learn, Share & Grow Together</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css?v3.0" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css?v3.0" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/index_html_style.css?v3.0'); ?>" />

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>" />
</head>

<body>
    <!-- Abstract Visuals -->
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>
    <div class="decor-circle dc-1"></div>
    <div class="decor-circle dc-2"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">🎓 StudyHub</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how">How it Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="<?php echo url('auth'); ?>" class="btn btn-primary px-4 py-2">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero container">
        <div class="fade-up active">
            <!-- <div class="badge-new">NEW VERSION 3.0 NOW LIVE</div> -->
            <h1>Learn, Share & <span class="highlight">Grow</span><br>Together</h1>
            <p class="text-muted mx-auto">
                Join thousands of students sharing high-quality academic notes and resources. 
                The ultimate platform for collaborative learning.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="<?php echo url('auth'); ?>#register" class="btn btn-primary">Start Collaborating</a>
                <a href="#features" class="btn btn-outline-light border-secondary px-4 py-3 rounded-4">Explore Community</a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="container py-5">
        <div class="text-center mb-5 fade-up">
            <h2 class="display-5 fw-bold mb-3">Designed for <span class="text-primary">Everyone</span></h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Experience a seamless academic collaboration environment designed for every role.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <div class="card-icon"><i class="fa fa-user-graduate"></i></div>
                    <h3 class="fw-bold h4 mb-3">Student</h3>
                    <p class="text-muted">Access thousands of lecture notes, slides, and exam resources. Share yours and earn badges.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <div class="card-icon" style="color: var(--secondary); background: rgba(139, 92, 246, 0.1);"><i class="fa fa-search-plus"></i></div>
                    <h3 class="fw-bold h4 mb-3">Moderator</h3>
                    <p class="text-muted">Maintain the highest quality of content. Review uploads and guide the community growth.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="feature-card">
                    <div class="card-icon" style="color: var(--accent); background: rgba(6, 182, 212, 0.1);"><i class="fa fa-user-shield"></i></div>
                    <h3 class="fw-bold h4 mb-3">Administrator</h3>
                    <p class="text-muted">Analyze trends, manage users, and generate comprehensive platform activity reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how" class="container py-5">
        <div class="text-center mb-5 fade-up">
            <h2 class="display-5 fw-bold">Simple <span class="highlight">Workflow</span></h2>
        </div>
        <div class="row g-0">
            <div class="col-md-4 step-item fade-up">
                <div class="step-box">
                    <div class="step-number">01</div>
                    <h4 class="fw-bold mb-3">Quick Register</h4>
                    <p class="text-muted">Create your account in seconds and verify your student status via OTP.</p>
                </div>
            </div>
            <div class="col-md-4 step-item fade-up">
                <div class="step-box">
                    <div class="step-number">02</div>
                    <h4 class="fw-bold mb-3">Share Knowledge</h4>
                    <p class="text-muted">Upload your best notes or slides and let them help others across the globe.</p>
                </div>
            </div>
            <div class="col-md-4 step-item fade-up">
                <div class="step-box">
                    <div class="step-number">03</div>
                    <h4 class="fw-bold mb-3">Level Up</h4>
                    <p class="text-muted">Gain reputation points, earn certifications, and climb the leaderboard.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Social & Contact -->
    <section id="contact" class="container py-5">
        <div class="text-center mb-5 fade-up">
            <h2 class="display-5 fw-bold mb-3">Join the <span class="text-primary">Circle</span></h2>
            <p class="text-muted">Stay updated with our latest resources and community announcements.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-6 fade-up">
                <a href="https://www.youtube.com/@studyhub991" target="_blank" class="social-card youtube h-100">
                    <i class="fab fa-youtube text-danger h2 mb-3"></i>
                    <h5 class="fw-bold mb-1">YouTube</h5>
                    <p class="small text-muted mb-0">Tutorials & More</p>
                </a>
            </div>
            <div class="col-lg-3 col-6 fade-up">
                <a href="https://t.me/studyhub991" target="_blank" class="social-card telegram h-100">
                    <i class="fab fa-telegram-plane text-info h2 mb-3"></i>
                    <h5 class="fw-bold mb-1">Telegram</h5>
                    <p class="small text-muted mb-0">Download Resources</p>
                </a>
            </div>
            <div class="col-lg-3 col-6 fade-up">
                <a href="mailto:studyhubteam.official@gmail.com" class="social-card h-100">
                    <i class="fa fa-envelope text-primary h2 mb-3"></i>
                    <h5 class="fw-bold mb-1">Email Us</h5>
                    <p class="small text-muted mb-0">Direct Support</p>
                </a>
            </div>
            <div class="col-lg-3 col-6 fade-up">
                <a href="https://fb.com/mihmahmudali" target="_blank" class="social-card h-100">
                    <i class="fab fa-facebook h2 mb-3" style="color: #1877F2;"></i>
                    <h5 class="fw-bold mb-1">Facebook</h5>
                    <p class="small text-muted mb-0">Developer Profile</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <div class="h3 fw-bold mb-4">🎓 StudyHub</div>
            <div class="text-muted small mb-4">
                Empowering peer-to-peer learning through collaboration and shared knowledge.
            </div>
            <hr class="border-secondary opacity-25">
            <p class="text-muted small mt-4">
                © <span id="year"></span> StudyHub. All rights reserved. <br>
                Crafted for academic excellence.
            </p>
        </div>
    </footer>

    <button id="topBtn"><i class="fa fa-arrow-up"></i></button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/index_html_script.js?v3.0'); ?>"></script>
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
