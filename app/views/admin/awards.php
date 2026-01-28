<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Awards & Certificates - StudyHub Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Dancing+Script:wght@400;500&family=Allura&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
    <style>
        .award-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            border: 1px solid #f1f5f9;
        }
        .award-card:hover { transform: translateY(-5px); }
        .award-header {
            background: #1e293b;
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            border-bottom: 4px solid #d4af37;
        }
        .award-header i { font-size: 2.5rem; margin-bottom: 0.5rem; color: #d4af37; }
        .rank-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 35px;
            height: 35px;
            background: #d4af37;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 800;
            font-size: 1rem;
        }
        .user-avatar {
            width: 65px;
            height: 65px;
            background: #fdfbf7;
            color: #1e293b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: -32px auto 10px;
            border: 3px solid #d4af37;
            position: relative;
            z-index: 2;
            font-family: Georgia, serif;
        }
        .btn-action {
            border-radius: 4px;
            font-weight: 700;
            padding: 10px 15px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .category-title {
            font-weight: 800;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 15px;
            color: #1e293b;
        }
        .category-title::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: #d4af37;
        }
        /* Hidden Template for PDF */
        #certTemplate { 
            position: absolute; 
            left: -9999px; 
            top: 0;
            width: 1122px; /* Fixed A4 Landscape Width */
        }
    </style>
</head>
<body>
    <?php $activePage = 'awards'; ?>
<!-- Sidebar -->
<aside class="sidebar admin-sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap me-2"></i> <span>StudyHub</span>
    </div>
    <ul class="nav flex-column" id="sidebarAccordion">
        <!-- Core -->

        <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/dashboard'); ?>" class="nav-link">
                <div class="nav-link-content">
                    <i class="fa fa-home main-icon"></i><span>Dashboard</span>
                </div>
            </a>
        </li>

        <!-- Content -->

        <li>
            <a href="#contentMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <div class="nav-link-content">
                    <i class="fa fa-folder-open main-icon"></i><span>Management</span>
                </div>
                <i class="fa fa-chevron-right arrow-icon"></i>
            </a>
            <div class="collapse <?php echo in_array($activePage, ['pending_notes', 'manage_resources']) ? 'show' : ''; ?>" id="contentMenu" data-bs-parent="#sidebarAccordion">
                <ul class="sub-menu">
                    <li class="<?php echo ($activePage === 'pending_notes') ? 'active' : ''; ?>">
                        <a href="<?php echo url('admin/pending_notes'); ?>" class="nav-link">Pending Notes</a>
                    </li>
                    <li class="<?php echo ($activePage === 'manage_resources') ? 'active' : ''; ?>">
                        <a href="<?php echo url('admin/manage_resources'); ?>" class="nav-link">Resources</a>
                    </li>
                </ul>
            </div>
        </li>

        <?php if ($role === 'admin') { ?>
            <!-- Governance -->

            <li>
                <a href="#governanceMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                    <div class="nav-link-content">
                        <i class="fa fa-shield-halved main-icon"></i><span>Control</span>
                    </div>
                    <i class="fa fa-chevron-right arrow-icon"></i>
                </a>
                <div class="collapse <?php echo in_array($activePage, ['users', 'active_users']) ? 'show' : ''; ?>" id="governanceMenu" data-bs-parent="#sidebarAccordion">
                    <ul class="sub-menu">
                        <li class="<?php echo ($activePage === 'users') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/users'); ?>" class="nav-link">User List</a>
                        </li>
                        <li class="<?php echo ($activePage === 'active_users') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/active_users'); ?>" class="nav-link">Active Sessions</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Insights -->

            <li>
                <a href="#insightsMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                    <div class="nav-link-content">
                        <i class="fa fa-chart-line main-icon"></i><span>Analytics</span>
                    </div>
                    <i class="fa fa-chevron-right arrow-icon"></i>
                </a>
                <div class="collapse <?php echo in_array($activePage, ['analytics', 'resource_analytics', 'reports', 'awards']) ? 'show' : ''; ?>" id="insightsMenu" data-bs-parent="#sidebarAccordion">
                    <ul class="sub-menu">
                        <li class="<?php echo ($activePage === 'analytics') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/analytics'); ?>" class="nav-link">Statistics</a>
                        </li>
                        <li class="<?php echo ($activePage === 'resource_analytics') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/resource_analytics'); ?>" class="nav-link">Resources</a>
                        </li>
                        <li class="<?php echo ($activePage === 'reports') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/reports'); ?>" class="nav-link">Reports</a>
                        </li>
                        <li class="<?php echo ($activePage === 'awards') ? 'active' : ''; ?>">
                            <a href="<?php echo url('admin/awards'); ?>" class="nav-link">Awards</a>
                        </li>
                    </ul>
                </div>
            </li>
        <?php } ?>

        <!-- Navigation -->

        <li>
            <a href="#navigationMenu" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <div class="nav-link-content">
                    <i class="fa fa-compass main-icon"></i><span>Quick Links</span>
                </div>
                <i class="fa fa-chevron-right arrow-icon"></i>
            </a>
            <div class="collapse <?php echo in_array($activePage, ['browse_notes', 'browse_resources', 'my_notes']) ? 'show' : ''; ?>" id="navigationMenu" data-bs-parent="#sidebarAccordion">
                <ul class="sub-menu">
                    <li><a href="<?php echo url('home/dashboard'); ?>" class="nav-link">Browse Notes</a></li>
                    <li><a href="<?php echo url('resources'); ?>" class="nav-link">Browse Resources</a></li>
                    <li><a href="<?php echo url('note/my_notes'); ?>" class="nav-link">My Notes</a></li>
                </ul>
            </div>
        </li>

        <!-- Account -->

        <li class="<?php echo ($activePage === 'settings') ? 'active' : ''; ?>">
            <a href="<?php echo url('settings'); ?>" class="nav-link">
                <div class="nav-link-content">
                    <i class="fa fa-cog main-icon"></i><span>Settings</span>
                </div>
            </a>
        </li>
        <li class="logout">
            <a href="<?php echo url('logout'); ?>" class="nav-link">
                <div class="nav-link-content">
                    <i class="fa fa-sign-out-alt main-icon"></i><span>Logout</span>
                </div>
            </a>
        </li>
    </ul>
</aside>

    <main class="main-content">
        <header class="topbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
                <h5 class="mb-0 fw-semibold">Awards & Recognition Center</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
                </a>
            </div>
        </header>

        <div class="container-fluid py-2">
            
            <!-- TOP 3 STUDENTS -->
            <h4 class="category-title"><i class="fa fa-star text-warning"></i> Top 3 Students (Academic Excellence)</h4>
            <div class="row g-4 mb-5">
                <?php foreach($topStudents as $index => $user): ?>
                <div class="col-md-4">
                    <div class="card award-card h-100">
                        <div class="award-header">
                            <span class="rank-badge"><?php echo $index + 1; ?></span>
                            <i class="fa <?php echo $index === 0 ? 'fa-crown' : ($index === 1 ? 'fa-medal' : 'fa-award'); ?>"></i>
                            <h6 class="mb-0 mt-1 font-monospace small">HONORARY AWARD</h6>
                        </div>
                        <div class="user-avatar shadow-sm">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                        <div class="card-body text-center pt-0">
                            <h5 class="fw-bold mb-1" style="font-family: Georgia, serif;"><?php echo htmlspecialchars($user['name']); ?></h5>
                            <p class="text-muted small mb-3"><?php echo number_format($user['points']); ?> XP Points Earned</p>
                            
                            <div class="d-grid gap-2">
                                <button onclick="sendAward(<?php echo $user['id']; ?>, 'student', <?php echo $index+1; ?>, '<?php echo htmlspecialchars($user['name']); ?>')" class="btn btn-dark btn-action">
                                    <i class="fa fa-envelope-open-text"></i> Generate & Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- TOP 3 CONTRIBUTORS -->
            <h4 class="category-title"><i class="fa fa-upload text-warning"></i> Top 3 Contributors (Knowledge Sharing)</h4>
            <div class="row g-4">
                <?php foreach($topContributors as $index => $user): ?>
                <div class="col-md-4">
                    <div class="card award-card h-100">
                        <div class="award-header">
                            <span class="rank-badge"><?php echo $index + 1; ?></span>
                            <i class="fa fa-feather-pointed"></i>
                            <h6 class="mb-0 mt-1 font-monospace small">EXCEPTIONAL SERVICE</h6>
                        </div>
                        <div class="user-avatar shadow-sm">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                        <div class="card-body text-center pt-0">
                            <h5 class="fw-bold mb-1" style="font-family: Georgia, serif;"><?php echo htmlspecialchars($user['name']); ?></h5>
                            <p class="text-muted small mb-3"><?php echo $user['note_count']; ?> Approved Notebooks</p>
                            
                            <div class="d-grid gap-2">
                                <button onclick="sendAward(<?php echo $user['id']; ?>, 'contributor', <?php echo $index+1; ?>, '<?php echo htmlspecialchars($user['name']); ?>')" class="btn btn-dark btn-action">
                                    <i class="fa fa-envelope-open-text"></i> Generate & Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- BULLETPROOF SVG RENDERER (Off-stage High-Priority Container) -->
    <div id="certGenerator-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -99999; opacity: 0; background: #fff; display: flex; align-items: center; justify-content: center; pointer-events: none; visibility: hidden;">
        <div id="certTemplate" style="width: 842pt; height: 595pt; min-width: 842pt; min-height: 595pt; background: #fff; position: relative; overflow: hidden; line-height: normal; display: block; font-family: 'Times New Roman', Georgia, serif;">
            <!-- Parchment Texture -->
            <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: #fff9f0;"></div>

            <!-- ORNATE SVG BORDER SYSTEM -->
            <svg style="position: absolute; top:0; left:0; width: 100%; height: 100%;" viewBox="0 0 842 595" preserveAspectRatio="none">
                <defs>
                    <!-- Gold Gradient -->
                    <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#f4e5c2;stop-opacity:1" />
                        <stop offset="50%" style="stop-color:#d4af37;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#b8860b;stop-opacity:1" />
                    </linearGradient>
                    
                    <!-- Decorative Pattern -->
                    <pattern id="goldPattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="10" cy="10" r="1.5" fill="#d4af37" opacity="0.3"/>
                    </pattern>
                </defs>
                
                <!-- Outer decorative border -->
                <rect x="15" y="15" width="812" height="565" fill="url(#goldPattern)" stroke="url(#goldGrad)" stroke-width="3" />
                
                <!-- Main ornate frame -->
                <rect x="22" y="22" width="798" height="551" fill="none" stroke="url(#goldGrad)" stroke-width="16" />
                <rect x="30" y="30" width="782" height="535" fill="none" stroke="#c9a961" stroke-width="1.5" />
                
                <!-- Inner decorative border -->
                <rect x="40" y="40" width="762" height="515" fill="none" stroke="#b8860b" stroke-width="2" />
                
                <!-- Corner Ornaments (Baroque Style) -->
                <!-- Top Left Corner -->
                <path d="M 50 50 Q 50 80, 70 85 Q 55 85, 50 95 M 50 50 Q 80 50, 85 70 Q 85 55, 95 50" 
                      fill="none" stroke="url(#goldGrad)" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="50" cy="50" r="4" fill="#d4af37"/>
                <path d="M 45 65 Q 52 68, 58 65 Q 55 72, 52 75 Q 49 72, 45 65" fill="#c9a961" opacity="0.7"/>
                <path d="M 65 45 Q 68 52, 65 58 Q 72 55, 75 52 Q 72 49, 65 45" fill="#c9a961" opacity="0.7"/>
                
                <!-- Top Right Corner -->
                <path d="M 792 50 Q 792 80, 772 85 Q 787 85, 792 95 M 792 50 Q 762 50, 757 70 Q 757 55, 747 50" 
                      fill="none" stroke="url(#goldGrad)" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="792" cy="50" r="4" fill="#d4af37"/>
                <path d="M 797 65 Q 790 68, 784 65 Q 787 72, 790 75 Q 793 72, 797 65" fill="#c9a961" opacity="0.7"/>
                <path d="M 777 45 Q 774 52, 777 58 Q 770 55, 767 52 Q 770 49, 777 45" fill="#c9a961" opacity="0.7"/>
                
                <!-- Bottom Left Corner -->
                <path d="M 50 545 Q 50 515, 70 510 Q 55 510, 50 500 M 50 545 Q 80 545, 85 525 Q 85 540, 95 545" 
                      fill="none" stroke="url(#goldGrad)" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="50" cy="545" r="4" fill="#d4af37"/>
                <path d="M 45 530 Q 52 527, 58 530 Q 55 523, 52 520 Q 49 523, 45 530" fill="#c9a961" opacity="0.7"/>
                <path d="M 65 550 Q 68 543, 65 537 Q 72 540, 75 543 Q 72 546, 65 550" fill="#c9a961" opacity="0.7"/>
                
                <!-- Bottom Right Corner -->
                <path d="M 792 545 Q 792 515, 772 510 Q 787 510, 792 500 M 792 545 Q 762 545, 757 525 Q 757 540, 747 545" 
                      fill="none" stroke="url(#goldGrad)" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="792" cy="545" r="4" fill="#d4af37"/>
                <path d="M 797 530 Q 790 527, 784 530 Q 787 523, 790 520 Q 793 523, 797 530" fill="#c9a961" opacity="0.7"/>
                <path d="M 777 550 Q 774 543, 777 537 Q 770 540, 767 543 Q 770 546, 777 550" fill="#c9a961" opacity="0.7"/>
                
                <!-- Decorative Side Flourishes -->
                <!-- Left Side -->
                <path d="M 40 200 Q 45 210, 40 220 M 40 240 Q 45 250, 40 260 M 40 280 Q 45 290, 40 300 M 40 320 Q 45 330, 40 340 M 40 360 Q 45 370, 40 380" 
                      fill="none" stroke="#c9a961" stroke-width="1" opacity="0.5"/>
                      
                <!-- Right Side -->
                <path d="M 802 200 Q 797 210, 802 220 M 802 240 Q 797 250, 802 260 M 802 280 Q 797 290, 802 300 M 802 320 Q 797 330, 802 340 M 802 360 Q 797 370, 802 380" 
                      fill="none" stroke="#c9a961" stroke-width="1" opacity="0.5"/>
            </svg>

            <!-- Content Area -->
            <div style="position: relative; z-index: 10; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 46pt 65pt 54pt 65pt; text-align: center;">
                
                <!-- Header Section -->
                <div style="width: 100%;">
                    <div style="margin-bottom: 5pt;">
                        <span style="font-size: 21pt; font-weight: bold; color: #1e293b; letter-spacing: 4.5pt; text-transform: uppercase; padding-bottom: 2pt;">STUDYHUB</span>
                        <div style="width: 330px; height: 2pt; background: linear-gradient(to right, transparent, #c9a961, transparent); margin: 0 auto 7pt auto;"></div>
                    </div>
                    <div style="color: #c9a961; font-size: 11pt; letter-spacing: 5.5pt; text-transform: uppercase; font-weight: bold; margin-bottom: 14pt;">CERTIFICATE OF ACHIEVEMENT</div>
                </div>

                <!-- Main Content Section -->
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; position: relative;">
                    <h1 style="color: #1e293b; font-size: 64pt; margin: 0 0 7pt 0; font-weight: bold; letter-spacing: 7pt; text-transform: uppercase;">CERTIFICATE</h1>
                    
                    <div style="width: 330px; height: 2pt; background: linear-gradient(to right, transparent, #c9a961, transparent); margin: 0 auto 7pt auto;"></div>
                    <h2 style="color: #2d3748; font-size: 20pt; margin: 0 0 20pt 0; font-weight: normal; font-style: italic;">of Professional Excellence</h2>
                    
                    <p style="color: #64748b; font-size: 13pt; margin-bottom: 9pt; font-style: italic;">This prestigious award is proudly presented to</p>
                    
                    <div style="margin-bottom: 16pt;">
                        <h3 id="pdfName" style="color: #1e293b; font-size: 38pt; margin: 0; font-weight: bold; border-bottom: 2.5pt solid #1e293b; display: inline-block; padding-bottom: 3pt; min-width: 370pt;">Name Lanun</h3>
                    </div>
                    
                    <!-- Achievement Details Box with enhanced border -->
                    <div style="position: relative; margin-bottom: 14pt; max-width: 630pt; background: linear-gradient(135deg, #fdfbf7 0%, #f9f5eb 100%); padding: 16pt 28pt; border-radius: 11pt; border: 2.5pt solid #c9a961; box-shadow: inset 0 0 18pt rgba(201, 169, 97, 0.15);">
                        <!-- Corner decorations for the box -->
                        <div style="position: absolute; top: -2pt; left: -2pt; width: 23pt; height: 23pt; border-top: 2.5pt solid #d4af37; border-left: 2.5pt solid #d4af37;"></div>
                        <div style="position: absolute; top: -2pt; right: -2pt; width: 23pt; height: 23pt; border-top: 2.5pt solid #d4af37; border-right: 2.5pt solid #d4af37;"></div>
                        <div style="position: absolute; bottom: -2pt; left: -2pt; width: 23pt; height: 23pt; border-bottom: 2.5pt solid #d4af37; border-left: 2.5pt solid #d4af37;"></div>
                        <div style="position: absolute; bottom: -2pt; right: -2pt; width: 23pt; height: 23pt; border-bottom: 2.5pt solid #d4af37; border-right: 2.5pt solid #d4af37;"></div>
                        
                        <p id="pdfRank" style="color: #b8860b; font-size: 26pt; font-weight: bold; margin: 0 0 5pt 0; letter-spacing: 1.8pt;">1ST POSITION</p>
                        <p id="pdfTitle" style="color: #1e293b; font-size: 17pt; font-weight: bold; margin: 0 0 9pt 0; letter-spacing: 0.9pt;">TOP STUDENT</p>
                        <p id="pdfDesc" style="color: #475569; font-size: 11.5pt; font-weight: 600; line-height: 1.5; margin: 0;">For demonstrating exceptional academic commitment and outstanding learning performance on the StudyHub platform.</p>
                    </div>
                </div>

                <!-- Footer Section -->
                <div style="width: 100%; display: flex; justify-content: space-between; align-items: flex-end;">
                    <div style="width: 220pt; text-align: left;">
                        <div>
                            <div style="font-family:'Dancing Script',cursive;font-size:20px;color:#1e293b;font-weight:500;letter-spacing:0.5px;">Mohsin Ibna Hossain</div>
                            <div style="font-size: 10pt; color: #1e293b; font-weight: 600;">Academic Director</div>
                            <div style="font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.8pt; font-weight: 600;">StudyHub Authority</div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Medal SVG -->
                    <div style="width: 95pt; text-align: center; position: relative;">
                        <svg width="70" height="80" viewBox="0 0 80 90" style="display: inline-block;">
                            <defs>
                                <radialGradient id="medalGold">
                                    <stop offset="0%" style="stop-color:#f4e5c2"/>
                                    <stop offset="50%" style="stop-color:#d4af37"/>
                                    <stop offset="100%" style="stop-color:#b8860b"/>
                                </radialGradient>
                                <linearGradient id="ribbon" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#4a90e2"/>
                                    <stop offset="100%" style="stop-color:#2d5a8f"/>
                                </linearGradient>
                            </defs>
                            
                            <!-- Ribbon -->
                            <path d="M 30 0 L 30 35 L 25 45 L 30 38 L 40 55 L 40 42 L 40 0 Z" fill="url(#ribbon)" stroke="#1e3a5f" stroke-width="0.5"/>
                            <path d="M 50 0 L 50 35 L 55 45 L 50 38 L 40 55 L 40 42 L 40 0 Z" fill="url(#ribbon)" stroke="#1e3a5f" stroke-width="0.5"/>
                            
                            <!-- Medal Circle -->
                            <circle cx="40" cy="50" r="28" fill="url(#medalGold)" stroke="#b8860b" stroke-width="2"/>
                            <circle cx="40" cy="50" r="24" fill="none" stroke="#f4e5c2" stroke-width="1.5"/>
                            <circle cx="40" cy="50" r="20" fill="none" stroke="#b8860b" stroke-width="1"/>
                            
                            <!-- Star inside medal -->
                            <path d="M 40 38 L 42 44 L 48 44 L 43 48 L 45 54 L 40 50 L 35 54 L 37 48 L 32 44 L 38 44 Z" fill="#b8860b" opacity="0.6"/>
                            
                            <!-- Number "1" -->
                            <text x="40" y="56" font-family="Georgia, serif" font-size="16" font-weight="bold" fill="#8b6914" text-anchor="middle">1</text>
                        </svg>
                        <div style="font-size: 7pt; color: #b8860b; font-weight: bold; margin-top: 1pt; letter-spacing: 1.3pt;">VERIFIED</div>
                    </div>

                    <div style="width: 220pt; text-align: right;">
                        <div>
                            <div id="pdfDate" style="font-size: 12pt; color: #1e293b; font-weight: bold;"><?php echo date('F d, Y'); ?></div>
                            <div style="font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.8pt; font-weight: 600;">ISSUE DATE</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Core Libraries Separately for Raw Control -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
    <script>


        async function sendAward(userId, type, rank, userName) {
            const confirmResult = await Swal.fire({
                title: 'Bulletproof Certification?',
                text: `Generating SVG-certified high-res credentials for ${userName}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Issue Certificate',
                confirmButtonColor: '#1e293b'
            });

            if (!confirmResult.isConfirmed) return;

            // Prepare Snapshot Environment
            const rankSuffix = (rank === 1) ? "ST" : ((rank === 2) ? "ND" : "RD");
            const roleTitle = (type === 'student') ? "TOP STUDENT" : "TOP CONTRIBUTOR";
            const description = (type === 'student') 
                ? "For demonstrating exceptional academic commitment and outstanding learning performance on the StudyHub platform."
                : "For significant knowledge sharing contributions and support to the global academic community.";
            
            document.getElementById('pdfName').innerText = userName;
            document.getElementById('pdfRank').innerText = rank + rankSuffix + " POSITION";
            document.getElementById('pdfTitle').innerText = roleTitle;
            document.getElementById('pdfDesc').innerText = description;
            
            const overlay = document.getElementById('certGenerator-overlay');
            const element = document.getElementById('certTemplate');

            Swal.fire({
                title: 'Professional Render Engine',
                html: 'Locking SVG coordinates and forcing GPU paint... <br><small class="text-muted">Stay active for 100% reliable capture.</small>',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                // 1. FLASH VISIBLE: Move to front layer (hidden behind Swal)
                overlay.style.zIndex = '999999';
                overlay.style.visibility = 'visible';
                overlay.style.opacity = '1';

                // 2. LEAD TIME: Ensure graphics are fully drawn
                await new Promise(r => setTimeout(r, 1000));

                // 3. RAW CAPTURE: Using html2canvas directly for maximum control
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    scrollX: 0,
                    scrollY: 0,
                    x: 0,
                    y: 0,
                    windowWidth: 842,
                    windowHeight: 595
                });

                // 4. IMAGE-BRIDGE WRAP: Convert to JPEG and embed into PDF
                const imgData = canvas.toDataURL('image/jpeg', 1.0);
                const pdf = new jspdf.jsPDF({
                    orientation: 'landscape',
                    unit: 'pt',
                    format: [842, 595]
                });

                pdf.addImage(imgData, 'JPEG', 0, 0, 842, 595);
                const pdfBlob = pdf.output('blob');

                // Cleanup Overlay
                overlay.style.zIndex = '-99999';
                overlay.style.visibility = 'hidden';
                overlay.style.opacity = '0';

                if (pdfBlob.size < 8000) {
                    throw new Error("Render Check failed: Resulting document is too small (blank).");
                }

                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('type', type);
                formData.append('rank', rank);
                formData.append('certificate', pdfBlob, 'StudyHub_Certified_Award.pdf');

                const response = await fetch('<?php echo url("admin/sendCertificate"); ?>', {
                    method: 'POST',
                    body: formData
                });

                const responseText = await response.text();
                let result;
                try {
                   result = JSON.parse(responseText);
                } catch (e) {
                   console.error('Raw Server Response:', responseText);
                   throw new Error('Server protocol error. Image-bridge check failed.');
                }
                
                if (result.success) {
                    Swal.fire('Award Dispatched!', 'The SVG-certified certificate has been emailed successfully.', 'success');
                } else {
                    Swal.fire('Email Error', result.message || 'Delivery failed.', 'error');
                }
            } catch (err) {
                console.error('Bulletproof Render Error:', err);
                overlay.style.zIndex = '-99999';
                overlay.style.visibility = 'hidden';
                Swal.fire('Process Failed', `<b>Detailed Error:</b> ${err.message}`, 'error');
            }
        }
    </script>
</body>
</html>
