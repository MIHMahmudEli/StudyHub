<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Platform Reports - StudyHub</title>

<!-- Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Charts.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom Styles -->
<link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.5'); ?>">
<link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; }
    
    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        border: none;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .stat-icon {
        width: 60px; height: 60px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
    }
    .icon-users { background: #e0e7ff; color: #4338ca; }
    .icon-notes { background: #dcfce7; color: #15803d; }
    .icon-downloads { background: #fef9c3; color: #a16207; }
    .icon-rating { background: #ffedd5; color: #c2410c; }

    .analytics-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        border: none;
        box-shadow: var(--card-shadow);
        height: 100%;
    }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .chart-container { position: relative; height: 300px; width: 100%; }

    .contributor-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 0; border-bottom: 1px solid #f1f5f9;
    }
    .contributor-item:last-child { border-bottom: none; }
    .contributor-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--primary-gradient); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600; font-size: 0.85rem;
    }

    /* Print Header Styles */
    .print-only-header { display: none; }

    @media print {
        @page { size: A4; margin: 15mm; }
        body { background: #fff !important; font-size: 10pt; overflow: visible !important; }
        .sidebar, .topbar, .btn-print-hide, .menu-toggle { display: none !important; }
        
        .print-only-header { 
            display: flex !important; 
            justify-content: space-between; 
            align-items: center;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .print-only-header h2 { margin: 0; color: #6366f1; font-weight: 700; }
        
        .main-content { 
            margin: 0 !important; 
            padding: 0 !important; 
            width: 100% !important; 
            max-width: 100% !important; 
            position: static !important;
            overflow: visible !important;
        }
        .container-fluid { padding: 0 !important; }
        
        /* Force grid items to stack or behave nicely on A4 */
        .row { display: flex !important; flex-wrap: wrap !important; margin-right: -10px; margin-left: -10px; }
        .col-xl-3, .col-md-6, .col-lg-6, .col-lg-8, .col-lg-4 { 
            width: 100% !important; 
            flex: 0 0 100% !important; 
            max-width: 100% !important; 
            padding-right: 10px; padding-left: 10px;
            margin-bottom: 20px !important;
        }

        /* 2-column layout for small cards might be better if they fit */
        .col-xl-3 { 
            width: 50% !important; 
            flex: 0 0 50% !important; 
            max-width: 50% !important; 
        }

        .stat-card, .analytics-card { 
            box-shadow: none !important; 
            border: 1px solid #dee2e6 !important; 
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            background-color: #fff !important;
            margin-bottom: 25px !important;
        }
        
        /* Specific handling for large charts to ensure they don't get cut */
        .analytics-card canvas { max-height: 250px !important; }
        
        .chart-container { height: 260px !important; }

        /* Ensure text is dark and readable */
        h3, h6, .fw-bold { color: #000 !important; }
        .text-muted { color: #666 !important; }
        
        /* Ensure background colors and fonts are crisp */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        
        .page-break { display: block; page-break-before: always; height: 0; margin: 0; border: none; }
        
        .print-footer {
            display: block !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    }
    .print-footer { display: none; }
</style>
</head>
<body>
<?php $activePage = 'reports'; ?>

<!-- Sidebar -->
<aside class="sidebar admin-sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
    </div>
    <ul class="nav flex-column">
        <li class="<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/dashboard'); ?>" class="nav-link"><i class="fa fa-house"></i>Dashboard</a>
        </li>
        <li class="<?php echo ($activePage === 'pending_notes') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/pending_notes'); ?>" class="nav-link"><i class="fa fa-file-lines"></i>Pending Notes</a>
        </li>
        <li class="<?php echo ($activePage === 'manage_resources') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/manage_resources'); ?>" class="nav-link"><i class="fa fa-folder"></i>Manage Resources</a>
        </li>
        <?php if (isset($role) && $role === 'admin') { ?>
            <li class="<?php echo ($activePage === 'users') ? 'active' : ''; ?>">
                <a href="<?php echo url('admin/users'); ?>" class="nav-link"><i class="fa fa-users"></i>Users</a>
            </li>
        <?php } ?>
        <li class="<?php echo ($activePage === 'active_users') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/active_users'); ?>" class="nav-link"><i class="fa fa-fire"></i>Active Users</a>
        </li>
        <li class="<?php echo ($activePage === 'analytics') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/analytics'); ?>" class="nav-link"><i class="fa fa-chart-column"></i>Analytics</a>
        </li>
        <li class="<?php echo ($activePage === 'reports') ? 'active' : ''; ?>">
            <a href="<?php echo url('admin/reports'); ?>" class="nav-link"><i class="fa fa-file-invoice"></i>Platform Reports</a>
        </li>
        <li class="<?php echo ($activePage === 'browse_notes') ? 'active' : ''; ?>">
            <a href="<?php echo url('home/dashboard'); ?>" class="nav-link"><i class="fa fa-book-open"></i>Browse Notes</a>
        </li>
        <li class="<?php echo ($activePage === 'browse_resources') ? 'active' : ''; ?>">
            <a href="<?php echo url('resources'); ?>" class="nav-link"><i class="fa fa-download"></i>Browse Resources</a>
        </li>
        <li class="<?php echo ($activePage === 'my_notes') ? 'active' : ''; ?>">
            <a href="<?php echo url('note/my_notes'); ?>" class="nav-link"><i class="fa fa-upload"></i>Uploaded Notes</a>
        </li>
        <li class="<?php echo ($activePage === 'settings') ? 'active' : ''; ?>">
            <a href="<?php echo url('settings'); ?>" class="nav-link"><i class="fa fa-gear"></i>Settings</a>
        </li>
    </ul>
</aside>

<!-- Main -->
<main class="main-content flex-grow-1">
    <!-- Topbar -->
    <header class="topbar d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold">Platform Performance Reports</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-outline-light btn-sm px-3 btn-print-hide">
                <i class="fa fa-print me-1"></i> Print PDF
            </button>
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm px-3 ms-2 btn-print-hide">Logout</a>
        </div>
    </header>

    <!-- Content -->
    <section class="container-fluid py-4 px-lg-4">
        
        <!-- Print Only Header -->
        <div class="print-only-header">
            <div>
                <h2>StudyHub Platform Report</h2>
                <p class="text-muted mb-0">Generated on: <?php echo date('F d, Y - h:i A'); ?></p>
            </div>
            <div class="text-end">
                <div class="fw-bold">Administrator Access</div>
                <div class="small">System Status: Active</div>
            </div>
        </div>
        
        <!-- Platform & Notes Analytics Section -->
        <div class="row mb-3">
            <div class="col-12">
                <h5 class="fw-bold text-primary"><i class="fa fa-chart-line me-2"></i>Platform & Notes Analytics</h5>
            </div>
        </div>

        <!-- Summary Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon icon-users"><i class="fas fa-users"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Total Users</div><h3 class="mb-0 fw-bold"><?php echo number_format($stats['total_users']); ?></h3></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon icon-notes"><i class="fas fa-file-alt"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Total Notes</div><h3 class="mb-0 fw-bold"><?php echo number_format($stats['total_notes']); ?></h3></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon icon-downloads"><i class="fas fa-download"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Total Downloads</div><h3 class="mb-0 fw-bold"><?php echo number_format($stats['total_downloads']); ?></h3></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon icon-rating"><i class="fas fa-star"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Platform Rating</div><h3 class="mb-0 fw-bold"><?php echo number_format($stats['avg_rating'], 1); ?></h3></div>
                </div>
            </div>
        </div>

        <!-- Snapshots Grid -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Moderation Status Snapshot</h6>
                    <div class="chart-container"><canvas id="statusChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Note Format Distribution</h6>
                    <div class="chart-container"><canvas id="fileTypeChart"></canvas></div>
                </div>
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="analytics-card">
                    <div class="chart-header">
                        <h6 class="fw-bold mb-0">Monthly Upload Growth</h6>
                        <span class="badge bg-primary-subtle text-primary">Last 12 Months</span>
                    </div>
                    <div class="chart-container" style="height: 350px;"><canvas id="monthlyActivityChart"></canvas></div>
                </div>
            </div>
        </div>

        <!-- Top Contributors & Quick Subject Performance -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Quick Subject Popularity (Downloads)</h6>
                    <div class="chart-container"><canvas id="subjectPerformanceChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Top Community Contributors</h6>
                    <?php if (empty($topContributors)): ?>
                        <p class="text-muted small">No contributors found.</p>
                    <?php else: ?>
                        <?php foreach($topContributors as $user): ?>
                            <div class="contributor-item">
                                <div class="contributor-avatar"><?php echo strtoupper(substr($user['name'],0,1)); ?></div>
                                <div class="flex-grow-1 px-1">
                                    <div class="fw-semibold small text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($user['name']); ?></div>
                                    <div class="text-muted small" style="font-size: 0.73rem;"><?php echo $user['note_count']; ?> notes contributed</div>
                                </div>
                                <i class="fas fa-medal text-warning small"></i>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr class="my-5 opacity-10">

        <!-- Resource Analytics Section -->
        <div class="row g-4 mb-4" id="course-resource-section">
            <div class="col-12">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa fa-folder-open me-2"></i>Course Resource Analytics</h5>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><i class="fas fa-folder"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Total Resources</div><h3 class="mb-0 fw-bold"><?php echo number_format($totalResources); ?></h3></div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"><i class="fas fa-download"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Resource Downloads</div><h3 class="mb-0 fw-bold"><?php echo number_format($resourceStats['total_downloads']); ?></h3></div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"><i class="fas fa-circle-half-stroke"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Mid-Term Resources</div><h3 class="mb-0 fw-bold"><?php 
                        $midCount = 0;
                        foreach($resourceStats['by_term'] as $term) {
                            if($term['term'] === 'mid') $midCount = $term['count'];
                        }
                        echo number_format($midCount);
                    ?></h3></div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);"><i class="fas fa-check-double"></i></div>
                    <div><div class="text-muted small fw-medium text-uppercase">Final-Term Resources</div><h3 class="mb-0 fw-bold"><?php 
                        $finalCount = 0;
                        foreach($resourceStats['by_term'] as $term) {
                            if($term['term'] === 'final') $finalCount = $term['count'];
                        }
                        echo number_format($finalCount);
                    ?></h3></div>
                </div>
            </div>
            
            <?php if(!empty($resourceStats['most_downloaded'])): ?>
            <div class="col-12">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-3"><i class="fa fa-trophy text-warning me-2"></i>Most Downloaded Resource</h6>
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                        <div class="flex-shrink-0">
                            <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($resourceStats['most_downloaded']['title']); ?></h6>
                            <p class="mb-0 text-muted small">
                                <span class="badge bg-primary me-2"><?php echo htmlspecialchars($resourceStats['most_downloaded']['subject']); ?></span>
                                <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($resourceStats['most_downloaded']['course_code']); ?></span>
                                <span class="text-dark fw-semibold"><?php echo number_format($resourceStats['most_downloaded']['downloads']); ?> downloads</span>
                                • Uploaded by <?php echo htmlspecialchars($resourceStats['most_downloaded']['uploader_name'] ?? 'System'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Print Footer -->
    <div class="print-footer">
        © <?php echo date('Y'); ?> StudyHub Administrative Intelligence System • Confidential Report • Page 1 of 2
    </div>
</main>

<script>
    const palette = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];

    // 1. Status Dist (Pie)
    const statusData = <?php echo json_encode($statusDistribution); ?>;
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statusData.map(s => s.status.toUpperCase()),
            datasets: [{ data: statusData.map(s => s.c), backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }]
        },
        options: { 
            responsive: true, maintainAspectRatio: false, 
            animation: { 
                duration: 2500, 
                animateRotate: true, 
                animateScale: true,
                easing: 'easeInOutQuart' 
            },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } } 
        }
    });

    // 2. File Format Dist (Doughnut)
    const fileData = <?php echo json_encode($fileDistribution); ?>;
    new Chart(document.getElementById('fileTypeChart'), {
        type: 'doughnut',
        data: {
            labels: fileData.map(f => f.file_type.toUpperCase()),
            datasets: [{ data: fileData.map(f => f.c), backgroundColor: palette, borderWidth: 0 }]
        },
        options: { 
            responsive: true, maintainAspectRatio: false, cutout: '75%', 
            animation: { 
                duration: 2500, 
                animateRotate: true, 
                animateScale: true,
                easing: 'easeInOutQuart' 
            },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } } 
        }
    });

    // 3. Monthly Activity (Line)
    const monthlyData = <?php echo json_encode($monthlyActivity); ?>;
    new Chart(document.getElementById('monthlyActivityChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => {
                const parts = d.month.split('-');
                const date = new Date(parts[0], parts[1]-1);
                return date.toLocaleString('default', { month: 'short', year: '2-digit' });
            }),
            datasets: [{
                label: 'Notes Uploaded',
                data: monthlyData.map(d => d.count),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                fill: true, tension: 0.4, pointRadius: 5
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            animation: { duration: 2000, easing: 'easeInOutQuart' },
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });

    // 4. Subject Popularity (Horizontal Bar)
    const trendData = <?php echo json_encode(array_slice($trendingSubjects, 0, 5)); ?>;
    new Chart(document.getElementById('subjectPerformanceChart'), {
        type: 'bar',
        data: {
            labels: trendData.map(s => s.subject.length > 25 ? s.subject.substring(0, 22) + '...' : s.subject),
            datasets: [{
                data: trendData.map(s => s.total_downloads),
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            animation: { duration: 1500, easing: 'easeOutBack' },
            plugins: { legend: { display: false } },
            scales: { x: { grid: { display: false } }, y: { grid: { display: false } } }
        }
    });

    // Sidebar toggle logic
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.menu-toggle');
    const toggleIcon = toggleBtn?.querySelector('i');

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        document.body.classList.toggle('no-scroll');
        
        // Toggle icon
        if (sidebar.classList.contains('open')) {
            toggleIcon?.classList.replace('fa-bars', 'fa-times');
        } else {
            toggleIcon?.classList.replace('fa-times', 'fa-bars');
        }
    });

    // Close sidebar if clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (
            window.innerWidth <= 992 &&
            sidebar.classList.contains('open') &&
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)
        ) {
            sidebar.classList.remove('open');
            document.body.classList.remove('no-scroll');
            toggleIcon?.classList.replace('fa-times', 'fa-bars');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
