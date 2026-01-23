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

        $data = [
            'notes' => $notes,
            'searchTerm' => $search,
            'isBookmarksView' => $isBookmarks,
            'role' => $role
        ];

        $this->view('home/dashboard', $data);
    }
}
