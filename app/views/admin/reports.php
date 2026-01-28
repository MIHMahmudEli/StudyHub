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
<link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=4.0.2'); ?>">
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
        @page { size: A4; margin: 15mm 10mm; }
        body { background: #fff !important; font-size: 10pt; color: #000 !important; }
        .sidebar, .topbar, .btn-print-hide, .menu-toggle, hr { display: none !important; }
        
        .print-only-header { 
            display: flex !important; 
            justify-content: space-between; 
            align-items: center;
            border-bottom: 3px solid #6366f1;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        
        .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; position: static !important; }
        .container-fluid { padding: 0 !important; margin: 0 !important; }
        
        .row { display: block !important; margin: 0 !important; }
        .col-12, .col-xl-3, .col-md-6, .col-lg-6, .col-lg-4, .col-lg-8 { 
            width: 100% !important; 
            display: block !important;
            margin-bottom: 30px !important;
            padding: 0 !important;
        }

        .stat-card, .analytics-card { 
            display: block !important;
            box-shadow: none !important; 
            border: 1px solid #e2e8f0 !important; 
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            padding: 20px !important;
            margin-bottom: 30px !important;
        }
        
        /* Force charts to a readable size in print */
        .chart-container { height: 280px !important; width: 100% !important; margin-bottom: 10px !important; position: relative !important; }
        canvas { max-width: 100% !important; height: 100% !important; }

        /* Highlight Section Print Fix */
        .bg-primary-subtle { background-color: #f1f5f9 !important; border: 2px solid #6366f1 !important; border-radius: 15px !important; }
        .rounded-pill { border-radius: 10px !important; border: 1px solid #ccc !important; }

        /* Page Breaks */
        #course-resource-section { page-break-before: always; margin-top: 40px !important; }
        .page-break { page-break-before: always; }
        
        .print-footer {
            display: block !important;
            position: fixed;
            bottom: 0px;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #777;
            padding: 10px 0;
            border-top: 1px solid #eee;
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
                <i class="fa fa-print"></i><span class="d-none d-md-inline ms-2">Print PDF</span>
            </button>
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm px-3 ms-2 btn-print-hide d-none d-md-inline-block">Logout</a>
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
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Moderation Status Snapshot</h6>
                    <div class="chart-container"><canvas id="statusChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Note Format Distribution</h6>
                    <div class="chart-container"><canvas id="fileTypeChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">User Role Composition</h6>
                    <div class="chart-container"><canvas id="roleDistChart"></canvas></div>
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

            <!-- New Resource Charts Row -->
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Subject Distribution</h6>
                    <div class="chart-container"><canvas id="resourceDistChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Top Subjects (Downloads)</h6>
                    <div class="chart-container"><canvas id="resourcePerfChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-4">Resource File Formats</h6>
                    <div class="chart-container"><canvas id="resourceFileTypeChart"></canvas></div>
                </div>
            </div>
            
            <?php if(!empty($resourceStats['most_downloaded'])): ?>
            <div class="col-12">
                <div class="analytics-card">
                    <h6 class="fw-bold mb-3">🔥 Highlighted Achievement</h6>
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-4 bg-primary-subtle border border-primary-subtle rounded-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon shadow-sm" style="width: 55px; height: 55px; background: #fff; color: #6366f1;">
                                <i class="fas fa-crown fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($resourceStats['most_downloaded']['title']); ?></h5>
                                <p class="mb-0 text-muted small">
                                    <span class="badge bg-white text-primary border me-1"><?php echo htmlspecialchars($resourceStats['most_downloaded']['subject']); ?></span>
                                    <span class="badge bg-white text-secondary border me-2"><?php echo htmlspecialchars($resourceStats['most_downloaded']['course_code']); ?></span>
                                    • Uploaded by <span class="fw-semibold"><?php echo htmlspecialchars($resourceStats['most_downloaded']['uploader_name'] ?? 'System'); ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="text-md-end bg-white px-4 py-2 rounded-pill shadow-sm border">
                            <div class="fw-800 h4 mb-0 text-primary"><?php echo number_format($resourceStats['most_downloaded']['downloads']); ?></div>
                            <div class="small fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Total Downloads</div>
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
    window.adminCharts = window.adminCharts || [];

    // 1. Status Dist (Pie)
    const statusData = <?php echo json_encode($statusDistribution); ?>;
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statusData.map(s => s.status.toUpperCase()),
            datasets: [{ data: statusData.map(s => s.c), backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }]
        },
        options: { 
            responsive: true, maintainAspectRatio: false, 
            animation: { duration: 2500, animateRotate: true, animateScale: true, easing: 'easeInOutQuart' },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } } 
        }
    });
    window.adminCharts.push(statusChart);

    // 2. File Format Dist (Doughnut)
    const fileData = <?php echo json_encode($fileDistribution); ?>;
    const fileChart = new Chart(document.getElementById('fileTypeChart'), {
        type: 'doughnut',
        data: {
            labels: fileData.map(f => f.file_type.toUpperCase()),
            datasets: [{ data: fileData.map(f => f.c), backgroundColor: palette, borderWidth: 0 }]
        },
        options: { 
            responsive: true, maintainAspectRatio: false, cutout: '75%', 
            animation: { duration: 2500, animateRotate: true, animateScale: true, easing: 'easeInOutQuart' },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } } 
        }
    });
    window.adminCharts.push(fileChart);

    // 3. Monthly Activity (Line - Note vs Resource)
    const monthlyData = <?php echo json_encode($monthlyActivity); ?>;
    const resMonthlyData = <?php echo json_encode($resourceMonthlyActivity); ?>;
    const allLabels = [...new Set([...monthlyData.map(d => d.month), ...resMonthlyData.map(d => d.month)])].sort();
    const formattedLabels = allLabels.map(m => {
        const parts = m.split('-');
        const date = new Date(parts[0], parts[1]-1);
        return date.toLocaleString('default', { month: 'short', year: '2-digit' });
    });

    const monthlyChart = new Chart(document.getElementById('monthlyActivityChart'), {
        type: 'line',
        data: {
            labels: formattedLabels,
            datasets: [{
                label: 'Notebooks',
                data: allLabels.map(m => (monthlyData.find(d => d.month === m)?.count || 0)),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                fill: true, tension: 0.4, pointRadius: 4
            }, {
                label: 'Resources',
                data: allLabels.map(m => (resMonthlyData.find(d => d.month === m)?.count || 0)),
                borderColor: '#a855f7',
                backgroundColor: 'rgba(168, 85, 247, 0.05)',
                fill: true, tension: 0.4, pointRadius: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            animation: { duration: 2000, easing: 'easeInOutQuart' },
            plugins: { legend: { display: true, position: 'bottom' } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });
    window.adminCharts.push(monthlyChart);

    // 4. Subject Popularity (Horizontal Bar)
    const trendData = <?php echo json_encode(array_slice($trendingSubjects, 0, 5)); ?>;
    const subjectChart = new Chart(document.getElementById('subjectPerformanceChart'), {
        type: 'bar',
        data: {
            labels: trendData.map(s => {
                const words = s.subject.trim().split(/\s+/);
                return words.length > 1 ? words.map(w => w[0]).join('').toUpperCase() : s.subject;
            }),
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
    window.adminCharts.push(subjectChart);

    // 5. Resource Distribution (Doughnut)
    const resDistData = <?php echo json_encode(array_slice($resourceDistribution, 0, 5)); ?>;
    const resDistChart = new Chart(document.getElementById('resourceDistChart'), {
        type: 'doughnut',
        data: {
            labels: resDistData.map(s => s.subject),
            datasets: [{ 
                data: resDistData.map(s => s.resource_count),
                backgroundColor: palette,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 10, font: { size: 10 } } } }
        }
    });
    window.adminCharts.push(resDistChart);

    // 6. Resource Performance (Vertical Bar)
    const resPerfData = <?php echo json_encode($resourcePerformance); ?>;
    const resPerfChart = new Chart(document.getElementById('resourcePerfChart'), {
        type: 'bar',
        data: {
            labels: resPerfData.map(s => {
                const words = s.subject.trim().split(/\s+/);
                return words.length > 1 ? words.map(w => w[0]).join('').toUpperCase() : s.subject;
            }),
            datasets: [{
                label: 'Downloads',
                data: resPerfData.map(s => s.total_downloads),
                backgroundColor: '#a855f7',
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false }, ticks: { font: { size: 9 } } } }
        }
    });
    window.adminCharts.push(resPerfChart);

    // 7. User Role Composition (Donut)
    const roleData = <?php echo json_encode($userRoleDistribution); ?>;
    const roleChart = new Chart(document.getElementById('roleDistChart'), {
        type: 'doughnut',
        data: {
            labels: roleData.map(r => r.role.toUpperCase()),
            datasets: [{ data: roleData.map(r => r.count), backgroundColor: ['#ec4899', '#f59e0b', '#6366f1'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } } }
    });
    window.adminCharts.push(roleChart);

    // 8. Resource File Formats (Doughnut)
    const resFileData = <?php echo json_encode($resourceFileDistribution); ?>;
    const resFileChart = new Chart(document.getElementById('resourceFileTypeChart'), {
        type: 'doughnut',
        data: {
            labels: resFileData.map(f => f.file_type.toUpperCase()),
            datasets: [{ data: resFileData.map(f => f.c), backgroundColor: palette.slice().reverse(), borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15 } } } }
    });
    window.adminCharts.push(resFileChart);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
</body>
</html>
