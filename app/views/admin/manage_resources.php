<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Resources - StudyHub Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/admin_dashboard.css?v=3.5'); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/favicon.svg'); ?>">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        .resource-card { 
            border: 1px solid var(--glass-border); 
            border-radius: 20px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.05); 
            background: var(--glass-bg); 
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .resource-card:hover {
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .search-area {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-left: 5px solid #6366f1;
        }

        .btn-upload { 
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); 
            color: #fff; 
            border: none; 
            padding: 10px 24px; 
            border-radius: 12px; 
            font-weight: 600; 
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        }
        
        .btn-upload:hover { 
            opacity: 1; 
            color: #fff; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.35); 
        }

        /* Admin Hierarchical Styles */
        .admin-subject-folder {
            border-radius: 25px;
            background: #fff;
            border: 1px solid #eef2f6;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            color: #334155;
            text-decoration: none;
            display: block;
            height: 100%;
        }
        .admin-subject-folder:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            border-color: #6366f1;
            color: #6366f1;
        }
        .folder-icon {
            width: 60px; height: 60px;
            background: #f8fafc;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #6366f1;
            transition: all 0.3s ease;
        }
        .admin-subject-folder:hover .folder-icon {
            background: #6366f1;
            color: #fff;
            transform: rotate(5deg);
        }

        .admin-term-card {
            border-radius: 30px;
            border: none;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            color: #fff;
            text-decoration: none;
            display: block;
        }
        .admin-term-card:hover {
            transform: scale(1.03);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .mid-gradient { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .final-gradient { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

        .breadcrumb-admin {
            padding: 10px 0;
            margin-bottom: 25px;
            display: flex;
        }
        .breadcrumb-admin a { color: #6366f1; text-decoration: none; font-weight: 600; }
        .breadcrumb-admin .breadcrumb-item.active { color: #1e293b !important; font-weight: 700 !important; background: none !important; padding: 0 !important; border: none !important; box-shadow: none !important; }
        .breadcrumb-admin .breadcrumb-item + .breadcrumb-item::before { color: #94a3b8; }

        .table thead th { 
            background: rgba(241, 245, 249, 0.5) !important; 
            border-bottom: 2px solid #e2e8f0; 
            color: #475569; 
            font-weight: 700; 
            text-transform: uppercase; 
            font-size: 0.7rem; 
            letter-spacing: 1.2px;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-term { 
            padding: 6px 14px; 
            border-radius: 20px; 
            font-size: 0.65rem; 
            font-weight: 800; 
            text-transform: uppercase; 
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .term-mid { background: #fee2e2; color: #dc2626; }
        .term-final { background: #dcfce7; color: #16a34a; }

        .format-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <?php $activePage = 'manage_resources'; ?>
    
    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar">
        <div class="logo"><i class="fa fa-graduation-cap"></i> <span>StudyHub</span></div>
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

    <main class="main-content flex-grow-1">
        <header class="topbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
                <h5 class="mb-0 fw-bold">Resource Management</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-upload shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fa fa-plus me-1"></i> Upload Resource
                </button>
                <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm px-3 ms-2">Logout</a>
            </div>
        </header>

        <div class="container-fluid px-lg-4">
            <!-- Integrated Search -->
            <div class="resource-card search-area p-4 mb-4">
                <form action="<?php echo url('admin/manage_resources'); ?>" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 py-2 ms-2"><i class="fa fa-search text-primary" style="opacity: 0.7;"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-1 py-2 shadow-none" placeholder="Search by subject name only..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-600 shadow-sm" style="border-radius: 10px;">Filter Subjects</button>
                    </div>
                </form>
            </div>

            <!-- Breadcrumbs -->
            <div class="breadcrumb-admin">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo url('admin/manage_resources'); ?>">All Subjects</a></li>
                    <?php if ($subject): ?>
                        <li class="breadcrumb-item"><a href="<?php echo url('admin/manage_resources'); ?>?subject=<?php echo urlencode($subject); ?>"><?php echo htmlspecialchars($subject); ?></a></li>
                    <?php endif; ?>
                    <?php if ($term): ?>
                        <li class="breadcrumb-item active"><?php echo ucfirst($term); ?></li>
                    <?php endif; ?>
                    <?php if ($search): ?>
                        <li class="breadcrumb-item active">Search: "<?php echo htmlspecialchars($search); ?>"</li>
                    <?php endif; ?>
                  </ol>
                </nav>
            </div>

            <?php if ($view_state === 'subject'): ?>
                <!-- SUBJECT VIEW -->
                <div class="row g-4">
                    <?php foreach ($subjects as $sub): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <a href="<?php echo url('admin/manage_resources'); ?>?subject=<?php echo urlencode($sub['subject']); ?>" class="admin-subject-folder p-4">
                                <div class="folder-icon"><i class="fa fa-folder-tree"></i></div>
                                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($sub['subject']); ?></h5>
                                <div class="text-muted small"><?php echo $sub['resource_count']; ?> Resources</div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($view_state === 'term'): ?>
                <!-- TERM VIEW -->
                <div class="row g-4 justify-content-center py-4">
                    <div class="col-md-5">
                        <a href="<?php echo url('admin/manage_resources'); ?>?subject=<?php echo urlencode($subject); ?>&term=mid" class="admin-term-card mid-gradient p-5 shadow-lg">
                            <i class="fa fa-calendar-alt fa-3x mb-3 opacity-50"></i>
                            <h2 class="fw-bold">Mid Term</h2>
                            <p class="mb-0"><?php echo $term_counts['mid']; ?> Approved Resources</p>
                        </a>
                    </div>
                    <div class="col-md-5">
                        <a href="<?php echo url('admin/manage_resources'); ?>?subject=<?php echo urlencode($subject); ?>&term=final" class="admin-term-card final-gradient p-5 shadow-lg">
                            <i class="fa fa-trophy fa-3x mb-3 opacity-50"></i>
                            <h2 class="fw-bold">Final Term</h2>
                            <p class="mb-0"><?php echo $term_counts['final']; ?> Approved Resources</p>
                        </a>
                    </div>
                </div>

            <?php elseif ($view_state === 'list'): ?>
                <!-- LIST VIEW -->
                <div class="resource-card p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Resource Details</th>
                                    <th>Term</th>
                                    <th>Subject</th>
                                    <th>Uploader</th>
                                    <th>Format</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($resources)): ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No resources found in this selection.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($resources as $res): ?>
                                        <tr id="row-<?php echo $res['id']; ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="format-icon <?php 
                                                            $ext = strtolower($res['file_type']);
                                                            if ($ext == 'pdf') echo 'bg-danger-subtle text-danger';
                                                            elseif (in_array($ext, ['doc','docx'])) echo 'bg-primary-subtle text-primary';
                                                            elseif (in_array($ext, ['ppt','pptx'])) echo 'bg-warning-subtle text-warning';
                                                            else echo 'bg-secondary-subtle text-secondary';
                                                         ?>">
                                                        <i class="fa <?php 
                                                            if ($ext == 'pdf') echo 'fa-file-pdf';
                                                            elseif (in_array($ext, ['doc','docx'])) echo 'fa-file-word';
                                                            elseif (in_array($ext, ['ppt','pptx'])) echo 'fa-file-powerpoint';
                                                            else echo 'fa-file';
                                                         ?>"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($res['title']); ?></div>
                                                        <div class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($res['course_code']); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-term <?php echo ($res['term'] === 'final') ? 'term-final' : 'term-mid'; ?>">
                                                    <i class="fa <?php echo ($res['term'] === 'final') ? 'fa-check-double' : 'fa-circle-half-stroke'; ?>"></i>
                                                    <?php echo strtoupper($res['term'] ?? 'MID'); ?> 
                                                </span>
                                            </td>
                                            <td><span class="badge bg-primary-subtle text-primary border-primary-subtle border px-3 py-2 rounded-3" style="font-size: 0.75rem;"><?php echo htmlspecialchars($res['subject']); ?></span></td>
                                            <td>
                                                <div class="small fw-bold text-dark"><?php echo htmlspecialchars($res['uploader_name'] ?? 'N/A'); ?></div>
                                                <div class="text-muted small"><?php echo date('M d, Y', strtotime($res['created_at'])); ?></div>
                                            </td>
                                            <td><span class="text-muted fw-semibold small text-uppercase"><?php echo htmlspecialchars($ext); ?></span></td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="<?php echo url('preview/note'); ?>?id=<?php echo $res['id']; ?>&type=resource&track=resource" target="_blank" class="btn btn-sm btn-outline-primary border-2 px-3"><i class="fa fa-eye"></i></a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger border-2 px-3" onclick="deleteResource(<?php echo $res['id']; ?>)"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Upload Modal stays globally accessible -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h5 class="modal-title fw-bold">Upload Course Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo url('admin/resources/upload'); ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
                    <div class="modal-body px-4 pb-2">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Select File</label>
                                <input type="file" name="file" id="fileInput" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" id="titleInput" class="form-control" placeholder="Resource Title" required>
                            </div>
                            <div class="col-7">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                            </div>
                            <div class="col-5">
                                <label class="form-label fw-semibold">Code</label>
                                <input type="text" name="course_code" class="form-control" placeholder="CSE-101" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Term</label>
                                <select name="term" class="form-select">
                                    <option value="mid" <?php echo $term === 'mid' ? 'selected' : ''; ?>>Mid Term</option>
                                    <option value="final" <?php echo $term === 'final' ? 'selected' : ''; ?>>Final Term</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-3">
                        <button type="submit" class="btn btn-primary w-100 py-3" style="border-radius:12px; font-weight:700;">Publish Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteResource(id) {
            if (confirm('Delete this resource?')) {
                fetch('<?php echo url("admin/resources/delete"); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id
                }).then(res => res.json()).then(data => {
                    if (data.success) document.getElementById('row-' + id).remove();
                });
            }
        }
    </script>
</body>
</html>
