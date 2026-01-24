<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, name, password, role, verified, points FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($name, $email, $password) {
        // Assume password is NOT hashed yet, hash it here? 
        // Or assume it IS hashed? 
        // consistency: let's hash it inside create for safety.
        // Wait, otp.php hashed it. logic: AuthController will hash it before passing to User::create OR User::create hashes it.
        // Let's pass the raw password and hash it here.
        
        // Actually, otp.php hashed it right before insert.
        // Replicating logic:
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $created_at = date('Y-m-d H:i:s');
        
        // Default verified = 1 because we only call this after OTP verification
        $stmt = $this->db->prepare("INSERT INTO users (`name`, email, `password`, verified, created_at) VALUES (?, ?, ?, 1, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $created_at);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function logEvent($userId, $type) {
        $timestamp = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO events (user_id, `type`, `at`) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $type, $timestamp);
        $stmt->execute();
    }

    public function countAll() {
        $result = $this->db->query("SELECT COUNT(*) as c FROM users");
        return $result->fetch_assoc()['c'];
    }

    public function getLeaderboard($limit = 30, $search = null) {
        if ($search) {
             $query = "SELECT id, name, points FROM users WHERE name LIKE ? ORDER BY points DESC LIMIT ?";
             $stmt = $this->db->prepare($query);
             $likeTerm = "%$search%";
             $stmt->bind_param("si", $likeTerm, $limit);
        } else {
             $query = "SELECT id, name, points FROM users ORDER BY points DESC LIMIT ?";
             $stmt = $this->db->prepare($query);
             $stmt->bind_param("i", $limit);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getMonthlyLeaderboard($limit = 30, $monthOffset = 0) {
        // monthOffset: 0 = current month, 1 = last month
        $start = date('Y-m-01 00:00:00', strtotime("-$monthOffset months"));
        $end = date('Y-m-t 23:59:59', strtotime("-$monthOffset months"));

        $query = "
            SELECT u.id, u.name, 
                   SUM(CASE 
                        WHEN e.type = 'upload' THEN 5 
                        WHEN e.type = 'download' THEN 1 
                        WHEN e.type = 'someone_download_your_note' THEN 2 
                        ELSE 0 
                   END) as points
            FROM users u
            JOIN events e ON u.id = e.user_id
            WHERE e.at >= ? AND e.at <= ?
            GROUP BY u.id
            HAVING points > 0
            ORDER BY points DESC
            LIMIT ?
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $start, $end, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTopActive($limit = 10, $period = 'all') {
        $query = "SELECT u.name, COUNT(e.id) as activity, MAX(e.at) as last_active 
                  FROM events e
                  LEFT JOIN users u ON e.user_id = u.id";
                 
        if ($period === 'today') {
            $today = date('Y-m-d');
            $query .= " WHERE DATE(e.at) = '$today'";
        }
        
        $query .= " GROUP BY e.user_id 
                   ORDER BY activity DESC 
                   LIMIT $limit";
                   
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, name, email, role, points, password, verified FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateName($id, $name) {
        $stmt = $this->db->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $hashedPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    public function updatePasswordByEmail($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        return $stmt->execute();
    }

    public function getModerators() {
        $result = $this->db->query("SELECT id, name, email FROM users WHERE role = 'moderator'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function demoteModerator($id) {
        $stmt = $this->db->prepare("UPDATE users SET role = 'student' WHERE id = ? AND role = 'moderator'");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAllUsers($search = null) {
        $query = "SELECT id, name, email, role, points, verified, created_at FROM users";
        if ($search) {
            $query .= " WHERE name LIKE ? OR email LIKE ?";
        }
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($query);
        if ($search) {
             $term = "%$search%";
             $stmt->bind_param("ss", $term, $term);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateRole($id, $role) {
        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $id);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getRecentStats($days = 30) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as c FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)");
        $stmt->bind_param("i", $days);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['c'];
    }

    public function getTopContributors($limit = 5) {
        $query = "SELECT u.id, u.name, COUNT(n.id) as note_count 
                  FROM users u 
                  INNER JOIN notes n ON u.id = n.uploader_id 
                  WHERE n.status = 'approved'
                  GROUP BY u.id 
                  ORDER BY note_count DESC 
                  LIMIT $limit";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getRoleDistribution() {
        return $this->db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role")
                        ->fetch_all(MYSQLI_ASSOC);
    }
}
