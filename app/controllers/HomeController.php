<?php
require_once '../app/models/Note.php';

class HomeController extends Controller {
    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('home/dashboard');
        }
        $this->view('home/landing');
    }

    public function auth() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('home/dashboard');
        }
        $this->view('home/index');
    }

    public function dashboard() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $search = isset($_GET['q']) ? trim($_GET['q']) : null;
        $isBookmarks = isset($_GET['bookmarks']);

        $noteModel = new Note();
        $notes = $noteModel->getNotes($userId, $isBookmarks, $search);

        $bookmarkedResources = [];
        $bookmarkedSubjects = [];
        if ($isBookmarks) {
            require_once '../app/models/Resource.php';
            $resourceModel = new Resource();
            $bookmarkedResources = $resourceModel->getBookmarkedResources($userId, $search);
            // We ideally merge these or pass separately
            $bookmarkedSubjects = $resourceModel->getBookmarkedSubjects($userId);
            
            // For now, let's keep them separate to render differently or merge if layout allows
            // The view uses $bookmarkedResources for the "Resources" tab.
            // Let's pass both and update view.
        }

        $data = [
            'notes' => $notes,
            'bookmarkedResources' => $bookmarkedResources,
            'bookmarkedSubjects' => $bookmarkedSubjects,
            'searchTerm' => $search,
            'isBookmarksView' => $isBookmarks,
            'role' => $role
        ];

        $this->view('home/dashboard', $data);
    }

    public function certificate() {
        // Publicly accessible with base64 encoded user info
        $userId = isset($_GET['id']) ? intval(base64_decode($_GET['id'])) : null;
        $type = $_GET['type'] ?? 'student';
        $rank = $_GET['rank'] ?? 1;

        if (!$userId) die("Invalid Certificate Link");

        require_once '../app/models/User.php';
        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) die("Awardee not found.");

        $data = [
            'user' => $user,
            'type' => $type,
            'rank' => $rank
        ];

        $this->view('home/certificate', $data);
    }
}
