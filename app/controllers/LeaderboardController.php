<?php
require_once '../app/models/User.php';

class LeaderboardController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
             $this->redirect('login');
        }

        $userModel = new User();
        $search = $_GET['q'] ?? null;
        $period = $_GET['period'] ?? 'current';

        if ($search) {
            $players = $userModel->getLeaderboard(30, $search);
            $period = 'search';
        } else {
            if ($period === 'last') {
                $players = $userModel->getMonthlyLeaderboard(30, 1);
            } elseif ($period === 'all') {
                $players = $userModel->getLeaderboard(30);
            } else {
                $players = $userModel->getMonthlyLeaderboard(30, 0);
                $period = 'current';
            }
        }

        $role = $_SESSION['role'];
        
        $this->view('leaderboard/index', [
            'players' => $players,
            'search' => $search,
            'role' => $role,
            'period' => $period
        ]);
    }
}
