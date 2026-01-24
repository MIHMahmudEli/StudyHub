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
            padding: 1.5rem 1rem !important;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .admin-subject-folder:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            border-color: #6366f1;
            color: #6366f1;
        }
        .folder-icon {
            width: 45px; height: 45px;
            background: #f8fafc;
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 8px;
            color: #6366f1;
            transition: all 0.3s ease;
        }
        .admin-subject-folder:hover .folder-icon {
            background: #6366f1;
            color: #fff;
            transform: rotate(5deg);
        }

        .admin-term-card {
            border-radius: 25px;
            border: none;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 2.5rem !important;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .admin-term-card:hover {
            transform: scale(1.03);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .mid-gradient { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .final-gradient { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

        @media (max-width: 768px) {
            .admin-subject-folder {
                padding: 0.85rem !important;
            }
            .folder-icon {
                width: 38px !important; height: 38px !important;
                margin-bottom: 6px !important;
                font-size: 1rem !important;
            }
            .admin-term-card {
                padding: 1.25rem 0.75rem !important;
            }
            .admin-term-card .fa-3x {
                font-size: 1.75rem !important;
            }
            .admin-term-card h2 {
                font-size: 1.25rem !important;
            }
        }
        .breadcrumb-admin {
            padding: 5px 0;
            margin-bottom: 8px;
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



        /* Suggestions Dropdown (Note Upload Style) */
        .suggestions {
            border: 1px solid #e2e8f0;
            background: #fff;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            position: absolute;
            width: 100%;
            top: 100%;
            left: 0;
            z-index: 1060; /* Higher than modal */
            border-radius: 0 0 10px 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .suggestion-item {
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }

        .suggestion-item:last-child { border-bottom: none; }
        .suggestion-item:hover { background: #f8fafc; color: #4f46e5; }
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

                <li class="<?php echo ($activePage === 'awards') ? 'active' : ''; ?>">
                    <a href="<?php echo url('admin/awards'); ?>" class="nav-link"><i class="fa fa-award"></i>Awards & Certificates</a>
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
            <!-- Top Left: Menu & Title -->
            <div class="d-flex align-items-center gap-2">
                <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars fa-lg"></i></button>
                <h5 class="mb-0 fw-bold d-none d-sm-block">Resource Management</h5>
                <h5 class="mb-0 fw-bold d-block d-sm-none fs-6">Resources</h5>
            </div>

            <!-- Top Right: Badge, Mobile Upload, Desktop Logout/Upload -->
            <div class="d-flex align-items-center gap-2">
                <!-- Desktop Upload Button -->
                <button type="button" class="btn btn-upload shadow-sm d-none d-md-block" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fa fa-plus me-1"></i> Upload Resource
                </button>
                
                <!-- Mobile Upload Button (Icon Only) - Replaces Logout on Mobile -->
                <button type="button" class="btn btn-light text-primary btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center d-md-none" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fa fa-plus"></i>
                </button>

                <span class="badge bg-light text-dark text-uppercase shadow-sm" style="font-size: 0.75rem;"><?php echo $role; ?></span>
                
                <!-- Logout (Desktop Only) -->
                <a href="<?php echo url('logout'); ?>" class="btn btn-danger btn-sm">
                    <i class="fa fa-sign-out-alt"></i><span class="d-none d-md-inline ms-1">Logout</span>
                </a>
            </div>
        </header>

        <div class="container-fluid px-lg-4">
            <!-- Integrated Search -->
            <div class="resource-card search-area p-3 p-md-4 mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <form action="<?php echo url('admin/manage_resources'); ?>" method="GET" class="row g-2 align-items-center">
                    <div class="col-12 col-md-9">
                        <div class="input-group bg-white rounded-pill border shadow-sm overflow-hidden p-1">
                            <span class="input-group-text bg-transparent border-0 pe-1 ps-3"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-0 shadow-none ps-2" placeholder="Search by subject name..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="font-size: 0.95rem;">
                             <!-- Mobile Search Button (Icon inside input group) -->
                            <button type="submit" class="btn btn-primary rounded-circle d-md-none m-1 shadow-sm" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                    <div class="col-md-3 d-none d-md-block">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm rounded-pill">Filter Subjects</button>
                    </div>
                </form>
            </div>

            <!-- Breadcrumbs -->
            <div class="breadcrumb-admin overflow-hidden">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-0 flex-nowrap align-items-center">
                    <li class="breadcrumb-item flex-shrink-0"><a href="<?php echo url('admin/manage_resources'); ?>">All Subjects</a></li>
                    <?php if ($subject): ?>
                        <li class="breadcrumb-item text-truncate" style="max-width: 150px; min-width: 50px;">
                            <a href="<?php echo url('admin/manage_resources'); ?>?subject=<?php echo urlencode($subject); ?>" title="<?php echo htmlspecialchars($subject); ?>"><?php echo htmlspecialchars($subject); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($term): ?>
                        <li class="breadcrumb-item active flex-shrink-0"><?php echo ucfirst($term); ?></li>
                    <?php endif; ?>
                    <?php if ($search): ?>
                        <li class="breadcrumb-item active text-truncate" style="max-width: 120px;">"<?php echo htmlspecialchars($search); ?>"</li>
                    <?php endif; ?>
                  </ol>
                </nav>
            </div>

            <?php if ($view_state === 'subject'): ?>
                <!-- SUBJECT VIEW -->
                <div class="row g-2">
                    <?php foreach ($subjects as $sub): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <a href="<?php echo url('admin/manage_resources'); ?>?subject=<?php echo urlencode($sub['subject']); ?>" class="admin-subject-folder p-4">
                                <div class="folder-icon"><i class="fa fa-folder-tree"></i></div>
                                <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($sub['subject']); ?></h5>
                                <div class="text-muted small"><?php echo $sub['resource_count']; ?> Resources</div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($view_state === 'term'): ?>
                <!-- TERM VIEW -->
                <div class="row g-2 justify-content-center py-3">
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
                                    <th class="d-none d-md-table-cell">Term</th>
                                    <th class="d-none d-md-table-cell">Subject</th>
                                    <th class="d-none d-md-table-cell">Uploader</th>
                                    <th class="d-none d-md-table-cell">Format</th>
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
                                                    <div style="min-width: 0;"> <!-- min-width:0 required for flex child truncation -->
                                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.95rem; max-width: 150px;"><?php echo htmlspecialchars($res['title']); ?></div>
                                                        <div class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($res['course_code']); ?></div>
                                                        <!-- Mobile Term Badge -->
                                                        <div class="d-md-none mt-1">
                                                            <span class="badge <?php echo ($res['term'] === 'final') ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?>" style="font-size: 0.65rem;">
                                                                <?php echo strtoupper($res['term'] ?? 'MID'); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <span class="badge-term <?php echo ($res['term'] === 'final') ? 'term-final' : 'term-mid'; ?>">
                                                    <i class="fa <?php echo ($res['term'] === 'final') ? 'fa-check-double' : 'fa-circle-half-stroke'; ?>"></i>
                                                    <?php echo strtoupper($res['term'] ?? 'MID'); ?> 
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell"><span class="badge bg-primary-subtle text-primary border-primary-subtle border px-3 py-2 rounded-3" style="font-size: 0.75rem;"><?php echo htmlspecialchars($res['subject']); ?></span></td>
                                            <td class="d-none d-md-table-cell">
                                                <div class="small fw-bold text-dark"><?php echo htmlspecialchars($res['uploader_name'] ?? 'N/A'); ?></div>
                                                <div class="text-muted small"><?php echo date('M d, Y', strtotime($res['created_at'])); ?></div>
                                            </td>
                                            <td class="d-none d-md-table-cell"><span class="text-muted fw-semibold small text-uppercase"><?php echo htmlspecialchars($ext); ?></span></td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="<?php echo url('preview/note'); ?>?id=<?php echo $res['id']; ?>&type=resource&track=resource_manage&subject=<?php echo urlencode($subject ?? ''); ?>&term=<?php echo urlencode($term ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>" class="btn btn-sm btn-outline-primary border-2 px-3"><i class="fa fa-eye"></i></a>
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
    <!-- Upload Modal -->
    <div class="modal" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 p-4 bg-primary text-white position-relative">
                    
                    <div>
                        <h5 class="modal-title fw-bold fs-4"><i class="fa fa-cloud-upload-alt me-2"></i>Upload Resource</h5>
                        <p class="mb-0 small text-white-50">Share knowledge with the community</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="<?php echo url('admin/resources/upload'); ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
                    <div class="modal-body p-4 bg-light">
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase mb-2"><i class="fa fa-file me-2"></i>Select File</label>
                                <input type="file" name="file" id="fileInput" class="form-control form-control-lg fs-6" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                                <div class="form-text small"><i class="fa fa-info-circle me-1"></i>Supported: PDF, DOC, PPT</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small text-uppercase"><i class="fa fa-heading me-1"></i>Resource Title</label>
                            <input type="text" name="title" id="titleInput" class="form-control py-2 fw-semibold" placeholder="e.g. Chapter 1 Summary" required>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-7 position-relative">
                                <label class="form-label fw-bold text-secondary small text-uppercase"><i class="fa fa-book me-1"></i>Subject Name</label>
                                <input type="text" name="subject" id="subjectInput" class="form-control py-2" placeholder="Start typing course name..." value="<?php echo htmlspecialchars($subject ?? ''); ?>" required autocomplete="off">
                                <div id="resourceCourseSuggestions" class="suggestions"></div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-secondary small text-uppercase"><i class="fa fa-code me-1"></i>Course Code</label>
                                <input type="text" name="course_code" id="codeInput" class="form-control py-2 bg-white" placeholder="e.g. CSE-101" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-bold text-secondary small text-uppercase"><i class="fa fa-calendar-alt me-1"></i>Academic Term</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="term" id="termMid" value="mid" <?php echo ($term !== 'final') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-primary flex-fill rounded-3 border-2 fw-semibold py-2" for="termMid">Mid Term</label>

                                <input type="radio" class="btn-check" name="term" id="termFinal" value="final" <?php echo ($term === 'final') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-success flex-fill rounded-3 border-2 fw-semibold py-2" for="termFinal">Final Term</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 bg-white rounded-bottom-4">
                        <button type="button" class="btn btn-light text-muted fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill"><i class="fa fa-paper-plane me-2"></i>Publish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/admin_dashboard.js?v=3.5'); ?>"></script>
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

        // --- Auto-fill Logic ---
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('fileInput');
            const titleInput = document.getElementById('titleInput');
            const subjectInput = document.getElementById('subjectInput');
            const codeInput = document.getElementById('codeInput');
            const datalist = document.getElementById('courseSuggestions');

            // 1. Auto-fill title from filename
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    let name = this.files[0].name;
                    // Remove extension
                    name = name.replace(/\.[^/.]+$/, "");
                    // Replace underscores/hyphens with spaces if desired, or keep as is.
                    // Let's clean it up a bit:
                    name = name.replace(/[-_]/g, ' ');
                    
                    // Only fill if title is empty or wasn't manually edited (simplified: just fill)
                    titleInput.value = name;
                }
            });

            // 2. Fetch courses and enable suggestion + auto-fill code
            let coursesData = [];
            const suggestionsBox = document.getElementById('resourceCourseSuggestions');
            
            fetch('<?php echo asset("data/courses.json"); ?>')
                .then(response => response.json())
                .then(data => {
                    coursesData = data;
                })
                .catch(err => console.error('Error loading courses:', err));

            // 3. Custom Suggestion Logic
            subjectInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                suggestionsBox.innerHTML = '';

                if (query.length < 2) {
                    suggestionsBox.style.display = 'none';
                    return;
                }

                const matches = coursesData.filter(c => 
                    c.name.toLowerCase().includes(query) || c.code.toLowerCase().includes(query)
                );

                if (matches.length > 0) {
                    suggestionsBox.style.display = 'block';
                    // Limit to 8 suggestions
                    matches.slice(0, 8).forEach(match => {
                        const div = document.createElement('div');
                        div.classList.add('suggestion-item');
                        // Highlight match logic could go here, but keeping simple
                        div.textContent = `${match.code} - ${match.name}`;
                        
                        div.addEventListener('click', () => {
                            subjectInput.value = match.name;
                            codeInput.value = match.code;
                            suggestionsBox.style.display = 'none';
                        });
                        suggestionsBox.appendChild(div);
                    });
                } else {
                    suggestionsBox.style.display = 'none';
                }
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!suggestionsBox.contains(e.target) && e.target !== subjectInput) {
                    suggestionsBox.style.display = 'none';
                }
            });
            
            // Also try reverse: if code is typed, fill subject? (Optional, user didn't ask but helpful)
            codeInput.addEventListener('input', function() {
                const val = this.value;
                const match = coursesData.find(c => c.code.toLowerCase() === val.toLowerCase());
                if (match) {
                    subjectInput.value = match.name;
                }
            });
        });
    </script>
</body>
</html>
