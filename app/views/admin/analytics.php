<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Subject Analytics - StudyHub</title>

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
    
    .analytics-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        border: none;
        box-shadow: var(--card-shadow);
        height: 100%;
        margin-bottom: 24px;
    }
    .chart-container { position: relative; height: 350px; width: 100%; }

    .trend-rank {
        width: 32px; height: 32px; border-radius: 8px;
        background: #f1f5f9; color: #475569;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; margin-right: 15px; flex-shrink: 0;
    }
    .accordion-item { border: none; margin-bottom: 10px; border-radius: 12px !important; overflow: hidden; background: #f8fafc; }
    .accordion-button { padding: 14px 20px; box-shadow: none !important; background: transparent; }
    .accordion-button:not(.collapsed) { background: #eff6ff; color: #2563eb; }

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
        
        .row { display: flex !important; flex-wrap: wrap !important; }
        .col-12, .col-lg-12 { 
            width: 100% !important; 
            flex: 0 0 100% !important; 
            max-width: 100% !important; 
            margin-bottom: 20px !important;
        }

        .analytics-card { 
            box-shadow: none !important; 
            border: 1px solid #dee2e6 !important; 
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            background-color: #fff !important;
            margin-bottom: 25px !important;
        }
        .accordion-collapse { display: block !important; }
        .accordion-button::after { display: none !important; }
        .trend-rank { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
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
<?php $activePage = 'analytics'; ?>

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
    <header class="topbar d-flex justify-content-between align-items-center mb-0">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold">Subject Breakdown Analytics</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-outline-light btn-sm px-3 btn-print-hide">
                <i class="fa fa-print"></i><span class="d-none d-md-inline ms-2">Print PDF</span>
            </button>
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <section class="container-fluid pt-4 pb-4 px-3 px-lg-4">

        <!-- Print Only Header -->
        <div class="print-only-header">
            <div>
                <h2>StudyHub Subject Analytics</h2>
                <p class="text-muted mb-0">Generated on: <?php echo date('F d, Y - h:i A'); ?></p>
            </div>
            <div class="text-end">
                <div class="fw-bold">Subject Performance Overview</div>
                <div class="small">Admin Intelligence Module</div>
            </div>
        </div>
        
        <!-- Subject Popularity Chart -->
        <div class="analytics-card">
            <h6 class="fw-bold mb-4">Top 5 Subjects by Popularity (Downloads)</h6>
            
            <!-- Desktop Chart View -->
            <div class="chart-container d-none d-md-block">
                <canvas id="subjectPerformanceChart"></canvas>
            </div>

            <!-- Mobile List-Bar View (Optimized for small screens) -->
            <div class="d-block d-md-none">
                <?php 
                $top5 = array_slice($trendingSubjects, 0, 5);
                $maxDLs = !empty($top5) ? $top5[0]['total_downloads'] : 1;
                foreach($top5 as $index => $sub): 
                    $pct = ($sub['total_downloads'] / ($maxDLs ?: 1)) * 100;
                ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                                <span class="badge rounded-circle bg-light text-primary border" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0;"><?php echo $index + 1; ?></span>
                                <span class="small fw-semibold text-dark text-truncate"><?php echo htmlspecialchars($sub['subject']); ?></span>
                            </div>
                            <span class="small fw-800 text-primary ms-2" style="flex-shrink: 0;"><?php echo number_format($sub['total_downloads']); ?> <span class="fw-normal text-muted" style="font-size: 0.65rem;">DLs</span></span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px; background: #f1f5f9; overflow: hidden;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $pct; ?>%; background: linear-gradient(90deg, #6366f1 0%, #a855f7 100%);" aria-valuenow="<?php echo $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($top5)): ?>
                    <p class="text-muted small text-center py-3">No activity data available.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Subject Breakdown Details -->
        <div class="analytics-card">
            <h6 class="fw-bold mb-4"><i class="fa fa-list-ul text-primary me-2"></i>Subject Wise Note Performance</h6>
            <?php if (empty($trendingSubjects)): ?>
                <p class="text-muted text-center py-5">No detailed data available.</p>
            <?php else: ?>
                <div class="accordion" id="trendingAccordion">
                    <?php foreach ($trendingSubjects as $index => $sub): ?>
                        <div class="accordion-item shadow-none">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?> fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                    <span class="trend-rank"><?php echo $index + 1; ?></span>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <span class="d-block text-truncate"><?php echo htmlspecialchars($sub['subject']); ?></span>
                                        <div class="small fw-normal text-muted mt-1 text-truncate"><?php echo $sub['total_downloads']; ?> Downloads • <?php echo $sub['total_notes']; ?> Notes</div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#trendingAccordion">
                                <div class="accordion-body px-3 pb-3">
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0" style="font-size: 0.85rem;">
                                            <thead class="table-light">
                                                <tr class="text-muted">
                                                    <th class="ps-3">Top Notes</th>
                                                    <th class="text-center">DLs</th>
                                                    <th class="text-center pe-3">Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($sub['top_notes'] as $note): ?>
                                                    <tr>
                                                        <td class="ps-3 py-2 text-dark fw-medium text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($note['title']); ?></td>
                                                        <td class="text-center"><?php echo $note['downloads']; ?></td>
                                                        <td class="text-center pe-3 text-warning font-monospace"><i class="fas fa-star small"></i> <?php echo number_format($note['avg_rating'], 1); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Print Footer -->
    <div class="print-footer">
        © <?php echo date('Y'); ?> StudyHub Administrative Intelligence System • Subject Performance Report
    </div>
</main>

<script>
    // Initialize global chart tracking
    window.adminCharts = window.adminCharts || [];

    // Subject Popularity (Horizontal Bar)
    const trendData = <?php echo json_encode(array_slice($trendingSubjects, 0, 5)); ?>;
    const ctx = document.getElementById('subjectPerformanceChart');
    if (ctx) {
        const subjectChart = new Chart(ctx, {
            type: 'bar',
            data: {
                // Shorten labels: Initials for multi-word, full for single-word
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
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/admin_dashboard.js?v=4.0.1'); ?>"></script>
</body>
</html>
