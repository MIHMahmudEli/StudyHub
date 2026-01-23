<?php
require_once '../app/models/User.php';

class LeaderboardController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('login');
        }

        $userModel = new User();
        $search = $_GET['q'] ?? null;
        $players = $userModel->getLeaderboard(30, $search);

        $role = $_SESSION['role'];
        
        $this->view('leaderboard/index', [
            'players' => $players,
            'search' => $search,
            'role' => $role
        ]);
    }
}
