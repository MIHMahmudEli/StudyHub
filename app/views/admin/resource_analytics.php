<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Resource Analytics - StudyHub</title>

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
    
    .term-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .term-mid { background: #fff1f2; color: #e11d48; }
    .term-final { background: #f0fdf4; color: #16a34a; }

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
<?php $activePage = 'resource_analytics'; ?>

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

                <li class="<?php echo ($activePage === 'active_users') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/active_users'); ?>" class="nav-link"><i class="fa fa-fire"></i>Active Users</a>
                </li>

                <li class="<?php echo ($activePage === 'analytics') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/analytics'); ?>" class="nav-link"><i class="fa fa-chart-column"></i>Analytics</a>
                </li>

                <li class="<?php echo ($activePage === 'reports') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/reports'); ?>" class="nav-link"><i class="fa fa-file-invoice"></i>Platform Reports</a>
                </li>

            <?php } ?>
            
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
            <h5 class="mb-0 fw-semibold">Resource Breakdown Analytics</h5>
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
                <h2>StudyHub Resource Analytics</h2>
                <p class="text-muted mb-0">Generated on: <?php echo date('F d, Y - h:i A'); ?></p>
            </div>
            <div class="text-end">
                <div class="fw-bold">Subject Resource Performance Overview</div>
                <div class="small">Admin Intelligence Module</div>
            </div>
        </div>
        
        <!-- Subject Popularity Chart -->
        <div class="analytics-card">
            <h6 class="fw-bold mb-4">Top 5 Resource Subjects by Popularity (Downloads)</h6>
            <div class="chart-container"><canvas id="resourcePerformanceChart"></canvas></div>
        </div>

        <!-- Subject Breakdown Details -->
        <div class="analytics-card">
            <h6 class="fw-bold mb-4"><i class="fa fa-list-ul text-primary me-2"></i>Subject Wise Resource Performance</h6>
            <?php if (empty($trendingResources)): ?>
                <p class="text-muted text-center py-5">No detailed data available.</p>
            <?php else: ?>
                <div class="accordion" id="trendingAccordion">
                    <?php foreach ($trendingResources as $index => $sub): ?>
                        <div class="accordion-item shadow-none">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?> fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                    <span class="trend-rank"><?php echo $index + 1; ?></span>
                                    <div class="flex-grow-1">
                                        <span><?php echo htmlspecialchars($sub['subject']); ?></span>
                                        <div class="small fw-normal text-muted mt-1"><?php echo $sub['total_downloads']; ?> Downloads • <?php echo $sub['total_resources']; ?> Resources</div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#trendingAccordion">
                                <div class="accordion-body px-3 pb-3">
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0" style="font-size: 0.85rem;">
                                            <thead class="table-light">
                                                <tr class="text-muted">
                                                    <th class="ps-3">Top Resources</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Term</th>
                                                    <th class="text-center pe-3">DLs</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($sub['top_resources'] as $resource): ?>
                                                    <tr>
                                                        <td class="ps-3 py-2 text-dark fw-medium"><?php echo htmlspecialchars($resource['title']); ?></td>
                                                        <td class="text-center small text-muted"><?php echo htmlspecialchars($resource['course_code']); ?></td>
                                                        <td class="text-center">
                                                            <span class="term-badge <?php echo ($resource['term'] === 'final') ? 'term-final' : 'term-mid'; ?>" style="zoom: 0.85;">
                                                                <?php echo strtoupper($resource['term'] ?? 'MID'); ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center pe-3 fw-bold text-primary font-monospace"><?php echo $resource['downloads']; ?></td>
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
        © <?php echo date('Y'); ?> StudyHub Administrative Intelligence System • Resource Performance Report
    </div>
</main>

<script>
    // Resource Popularity (Horizontal Bar)
    const trendData = <?php echo json_encode(array_slice($trendingResources, 0, 5)); ?>;
    new Chart(document.getElementById('resourcePerformanceChart'), {
        type: 'bar',
        data: {
            labels: trendData.map(s => s.subject.length > 30 ? s.subject.substring(0, 27) + '...' : s.subject),
            datasets: [{
                data: trendData.map(s => s.total_downloads),
                backgroundColor: 'rgba(168, 85, 247, 0.8)', // Slightly different color (purple) for resources
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
