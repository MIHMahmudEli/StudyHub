<?php

require_once '../app/models/User.php';
require_once '../app/models/Note.php';
require_once '../app/models/Resource.php';

class AdminController extends Controller {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('');
        }

        $noteModel = new Note();
        $resourceModel = new Resource();
        $userModel = new User();

        // Queries
        $pendingNotes = $noteModel->countPending();
        $trendingResources = $resourceModel->getTrendingResources(5);
        $totalResources = $resourceModel->countTotal();
        $trendingSubjects = $noteModel->getTrendingSubjects();
        
        // Admin specific
        $userCount = 0;
        $activeUsers = [];
        if ($_SESSION['role'] === 'admin') {
             $userCount = $userModel->countAll();
             $activeUsers = $userModel->getTopActive();
        }

        $data = [
            'pendingNotes' => $pendingNotes,
            'trendingResources' => $trendingResources,
            'totalResources' => $totalResources,
            'trendingSubjects' => $trendingSubjects,
            'userCount' => $userCount,
            'activeUsers' => $activeUsers,
            'role' => $_SESSION['role']
        ];

        $this->view('admin/dashboard', $data);
    }

    public function pendingNotes() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('');
        }
        
        $noteModel = new Note(); 
        $notes = $noteModel->getPending();

        $this->view('admin/pending_notes', ['notes' => $notes, 'role' => $_SESSION['role']]);
    }

    public function approveNote() {
        $this->checkAdmin();
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $noteModel = new Note();
            if ($noteModel->updateStatus($id, 'approved')) {
                // Log event? Maybe?
            }
        }
        $this->redirect('admin/pending_notes');
    }

    public function rejectNote() {
        $this->checkAdmin();
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $noteModel = new Note();
            $noteModel->updateStatus($id, 'rejected');
        }
        $this->redirect('admin/pending_notes');
    }

    private function checkAdmin() {
         if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('');
        }
    }

    public function users() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('admin/dashboard');
        }

        $search = $_GET['q'] ?? null;
        $userModel = new User();
        $users = $userModel->getAllUsers($search);

        $data = [
            'users' => $users,
            'search' => $search,
            'role' => $_SESSION['role']
        ];
        
        // Pass success/error messages if any (stored in session usually, but here we might just redirect with query param or standard flash)
        // Let's stick to standard flash session pattern if used, or just pass empty.
        
        $this->view('admin/users', $data);
    }

    public function updateUserRole() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $userId = intval($_POST['user_id']);
             $newRole = $_POST['role'];
             
             // Validate role
             if (in_array($newRole, ['student', 'moderator', 'admin'])) {
                 $userModel = new User();
                 $userModel->updateRole($userId, $newRole);
             }
        }
        $this->redirect('admin/users');
    }

    public function deleteUser() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') return;

        if (isset($_GET['id'])) {
            $userId = intval($_GET['id']);
            // Prevent deleting self
            if ($userId === $_SESSION['user_id']) {
                 // Error
            } else {
                 $userModel = new User();
                 $userModel->deleteUser($userId);
            }
        }
        $this->redirect('admin/users');
    }

    public function analytics() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('admin/dashboard');
        }

        $noteModel = new Note();
        $trendingSubjects = $noteModel->getTrendingSubjects(10, true);
        
        $data = [
            'trendingSubjects' => $trendingSubjects,
            'role' => $_SESSION['role']
        ];

        $this->view('admin/analytics', $data);
    }

    public function activeUsers() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('admin/dashboard');
        }

        $userModel = new User();
        $allTimeActive = $userModel->getTopActive(20, 'all');
        $todayActive = $userModel->getTopActive(20, 'today');

        $data = [
            'allTimeActive' => $allTimeActive,
            'todayActive' => $todayActive,
            'role' => $_SESSION['role']
        ];

        $this->view('admin/active_users', $data);
    }

    public function reports() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('admin/dashboard');
        }

        $noteModel = new Note();
        $userModel = new User();
        $resourceModel = new Resource();

        // High-level Stats
        $totalNotes = $noteModel->countApproved() + $noteModel->countPending();
        $totalDownloads = $noteModel->getTotalDownloads();
        $totalUsers = $userModel->countAll();
        $platformAvgRating = $noteModel->getPlatformAvgRating();

        // Distributions & Trends
        $fileDistribution = $noteModel->getFileTypeDistribution();
        $statusDistribution = $noteModel->getStatusDistribution();
        $monthlyActivity = $noteModel->getMonthlyActivity();
        $topContributors = $userModel->getTopContributors(5);
        $trendingSubjects = $noteModel->getTrendingSubjects(5, false);
        
        // Resource Statistics
        $resourceStats = $resourceModel->getResourceStats();
        $totalResources = $resourceModel->countTotal();
        
        $data = [
            'stats' => [
                'total_notes' => $totalNotes,
                'total_downloads' => $totalDownloads,
                'total_users' => $totalUsers,
                'avg_rating' => $platformAvgRating
            ],
            'fileDistribution' => $fileDistribution,
            'statusDistribution' => $statusDistribution,
            'monthlyActivity' => $monthlyActivity,
            'topContributors' => $topContributors,
            'trendingSubjects' => $trendingSubjects,
            'resourceStats' => $resourceStats,
            'totalResources' => $totalResources,
            'role' => $_SESSION['role']
        ];

        $this->view('admin/reports', $data);
    }

    public function resourceAnalytics() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('admin/dashboard');
        }

        $resourceModel = new Resource();
        $trendingResources = $resourceModel->getTrendingResources(10, true);

        $data = [
            'trendingResources' => $trendingResources,
            'role' => $_SESSION['role']
        ];

        $this->view('admin/resource_analytics', $data);
    }

    public function manageResources() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('');
        }
        
        $search = $_GET['search'] ?? null;
        $subject = $_GET['subject'] ?? null;
        $term = $_GET['term'] ?? null;
        
        $resourceModel = new Resource();
        $data = [
            'role' => $_SESSION['role'],
            'search' => $search,
            'subject' => $subject,
            'term' => $term
        ];

        if ($search) {
            $data['view_state'] = 'subject';
            $data['subjects'] = $resourceModel->getSubjectsWithCounts($search);
        } elseif ($subject && $term) {
            $data['view_state'] = 'list';
            $data['resources'] = $resourceModel->getResourcesBySubjectAndTerm($subject, $term);
        } elseif ($subject) {
            $data['view_state'] = 'term';
            $data['term_counts'] = $resourceModel->getTermCountsBySubject($subject);
        } else {
            $data['view_state'] = 'subject';
            $data['subjects'] = $resourceModel->getSubjectsWithCounts();
        }

        $this->view('admin/manage_resources', $data);
    }

    public function uploadResource() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $targetDir = ROOT_PATH . "/public/assets/uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES["file"]["name"]);
            $targetFile = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                $resourceModel = new Resource();
                $data = [
                    'uploader_id' => $_SESSION['user_id'],
                    'title'       => $_POST['title'],
                    'description' => $_POST['description'],
                    'subject'     => $_POST['subject'],
                    'course_code' => $_POST['course_code'],
                    'term'        => $_POST['term'],
                    'file_path'   => 'assets/uploads/' . $fileName,
                    'file_type'   => $fileType,
                    'status'      => 'approved' // Admins/Moderators uploads are pre-approved
                ];

                if ($resourceModel->store($data)) {
                    $_SESSION['flash_message'] = "Resource uploaded successfully!";
                    $this->redirect('admin/manage_resources');
                }
            }
        }
    }

    public function updateResourceStatus() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
        }

        $id = $_POST['id'];
        $status = $_POST['status'];

        $resourceModel = new Resource();
        if ($resourceModel->updateStatus($id, $status)) {
            $this->jsonResponse(['success' => true]);
        }
        $this->jsonResponse(['success' => false]);
    }

    public function deleteResource() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
        }

        $id = $_POST['id'];
        $resourceModel = new Resource();
        $resource = $resourceModel->find($id);

        if ($resource) {
            $filePath = ROOT_PATH . "/public/" . $resource['file_path'];
            if (file_exists($filePath)) unlink($filePath);
            
            if ($resourceModel->delete($id)) {
                $this->jsonResponse(['success' => true]);
            }
        }
        $this->jsonResponse(['success' => false]);
    }
}
