<?php

class Note {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getNotes($userId, $isBookmarks = false, $search = null) {
        $params = [];
        $types = "";
        
        if ($isBookmarks) {
            $query = "
                SELECT n.id, n.title, n.subject, n.avg_rating, n.file_type, u.name AS author_name,
                       b.user_id AS bookmarked
                FROM notes n
                INNER JOIN bookmarks b ON n.id = b.note_id AND b.user_id = ?
                LEFT JOIN users u ON n.uploader_id = u.id
            ";
            $types .= "i";
            $params[] = $userId;

            if ($search) {
                $query .= " AND (n.title LIKE ? OR n.subject LIKE ? OR u.name LIKE ?)";
                $types .= "sss";
                $likeTerm = "%$search%";
                $params[] = $likeTerm;
                $params[] = $likeTerm;
                $params[] = $likeTerm;
            }

            $query .= " ORDER BY n.created_at DESC";

        } else {
            $query = "
                SELECT n.id, n.title, n.subject, n.avg_rating, n.file_type, u.name AS author_name,
                       (SELECT 1 FROM bookmarks WHERE user_id=? AND note_id=n.id) AS bookmarked
                FROM notes n
                LEFT JOIN users u ON n.uploader_id = u.id
                WHERE n.status='approved'
            ";
            $types .= "i";
            $params[] = $userId;

            if ($search) {
                $query .= " AND (n.title LIKE ? OR n.subject LIKE ? OR u.name LIKE ?)";
                $types .= "sss";
                $likeTerm = "%$search%";
                $params[] = $likeTerm;
                $params[] = $likeTerm;
                $params[] = $likeTerm;
            }

            $query .= " ORDER BY n.created_at DESC";
        }

        $stmt = $this->db->prepare($query);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countPending() {
        $result = $this->db->query("SELECT COUNT(*) as c FROM notes WHERE status='pending'");
        return $result->fetch_assoc()['c'];
    }

    public function countApproved() {
        $result = $this->db->query("SELECT COUNT(*) as c FROM notes WHERE status='approved'");
        return $result->fetch_assoc()['c'];
    }
    
    public function getTrendingSubjects($limit = 10, $includeTopNotes = false) {
        $result = $this->db->query("
            SELECT subject, COUNT(*) as total_notes, SUM(downloads) as total_downloads
            FROM notes 
            WHERE status='approved'
            GROUP BY subject 
            ORDER BY total_downloads DESC 
            LIMIT $limit
        ");
        $subjects = $result->fetch_all(MYSQLI_ASSOC);

        if ($includeTopNotes) {
            foreach ($subjects as &$subj) {
                $name = $subj['subject'];
                $notesResult = $this->db->query("
                    SELECT n.id, n.title, n.downloads, n.avg_rating, u.name AS uploader_name
                    FROM notes n
                    LEFT JOIN users u ON n.uploader_id = u.id
                    WHERE n.subject = '$name' AND n.status = 'approved'
                    ORDER BY n.downloads DESC
                    LIMIT 5
                ");
                $subj['top_notes'] = $notesResult->fetch_all(MYSQLI_ASSOC);
            }
        }

        return $subjects;
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO notes (uploader_id, title, description, subject, course_code, file_path, file_type, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        // Data extraction
        $uploaderId = $data['uploader_id'];
        $title = $data['title'];
        $description = $data['description'];
        $subject = $data['subject'];
        $courseCode = $data['course_code'];
        $filePath = $data['file_path'];
        $fileType = $data['file_type'];
        $createdAt = date('Y-m-d H:i:s'); // Assuming timezone is set globally or in config, otherwise set here
        
        $createdAt = date('Y-m-d H:i:s');

        $stmt->bind_param("isssssss", $uploaderId, $title, $description, $subject, $courseCode, $filePath, $fileType, $createdAt);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT n.*, u.name AS author_name 
            FROM notes n
            LEFT JOIN users u ON n.uploader_id = u.id
            WHERE n.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function incrementDownloads($id) {
        $stmt = $this->db->prepare("UPDATE notes SET downloads = downloads + 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getPending() {
        $query = "
            SELECT n.*, u.name AS uploader_name 
            FROM notes n
            LEFT JOIN users u ON n.uploader_id = u.id
            WHERE n.status = 'pending'
            ORDER BY n.created_at DESC
        ";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE notes SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function getUserNotes($userId) {
        $stmt = $this->db->prepare("SELECT id, title, subject, course_code, description, status, created_at, file_type FROM notes WHERE uploader_id=? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function delete($id, $userId) {
         $stmt = $this->db->prepare("DELETE FROM notes WHERE id=? AND uploader_id=?");
         $stmt->bind_param("ii", $id, $userId);
         return $stmt->execute();
    }
    
    public function update($id, $userId, $title, $description, $subject, $courseCode) {
        $stmt = $this->db->prepare("UPDATE notes 
                                    SET title=?, description=?, subject=?, course_code=? 
                                    WHERE id=? AND uploader_id=?");
        $stmt->bind_param("ssssii", $title, $description, $subject, $courseCode, $id, $userId);
        return $stmt->execute();
    }

    public function isBookmarked($noteId, $userId) {
        $stmt = $this->db->prepare("SELECT 1 FROM bookmarks WHERE user_id = ? AND note_id = ?");
        $stmt->bind_param("ii", $userId, $noteId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function addBookmark($noteId, $userId) {
        if ($this->isBookmarked($noteId, $userId)) return false;
        $stmt = $this->db->prepare("INSERT INTO bookmarks (user_id, note_id, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $noteId);
        return $stmt->execute();
    }

    public function removeBookmark($noteId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM bookmarks WHERE user_id = ? AND note_id = ?");
        $stmt->bind_param("ii", $userId, $noteId);
        return $stmt->execute();
    }

    public function getFileTypeDistribution() {
        $result = $this->db->query("SELECT file_type, COUNT(*) as c FROM notes GROUP BY file_type");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStatusDistribution() {
        $result = $this->db->query("SELECT status, COUNT(*) as c FROM notes GROUP BY status");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMonthlyActivity() {
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                  FROM notes 
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  GROUP BY month
                  ORDER BY month ASC";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentStats($days = 30) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as c FROM notes WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)");
        $stmt->bind_param("i", $days);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['c'];
    }

    public function getTopDownloadedNotes($limit = 5) {
        $result = $this->db->query("SELECT n.title, n.downloads, u.name as uploader_name FROM notes n LEFT JOIN users u ON n.uploader_id = u.id ORDER BY n.downloads DESC LIMIT $limit");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalDownloads() {
        return $this->db->query("SELECT SUM(downloads) as c FROM notes")->fetch_assoc()['c'] ?? 0;
    }

    public function getPlatformAvgRating() {
        return $this->db->query("SELECT AVG(avg_rating) as c FROM notes WHERE status='approved'")->fetch_assoc()['c'] ?? 0;
    }

    public function getRating($noteId, $userId) {
        $stmt = $this->db->prepare("SELECT rating FROM reviews WHERE user_id = ? AND note_id = ?");
        $stmt->bind_param("ii", $userId, $noteId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['rating'] : 0;
    }

    public function saveRating($noteId, $userId, $rating) {
        // Check if already rated
        $current = $this->getRating($noteId, $userId);
        if ($current > 0) {
            $stmt = $this->db->prepare("UPDATE reviews SET rating = ?, updated_at = NOW() WHERE user_id = ? AND note_id = ?");
            $stmt->bind_param("iii", $rating, $userId, $noteId);
        } else {
            $stmt = $this->db->prepare("INSERT INTO reviews (note_id, user_id, rating, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iii", $noteId, $userId, $rating);
        }
        
        if ($stmt->execute()) {
            // Update average rating
            $avgStmt = $this->db->prepare("SELECT ROUND(AVG(rating), 1) AS avg_rating FROM reviews WHERE note_id = ?");
            $avgStmt->bind_param("i", $noteId);
            $avgStmt->execute();
            $avgRow = $avgStmt->get_result()->fetch_assoc();
            $newAvg = $avgRow['avg_rating'] ?? 0.0;

            $updateNoteStmt = $this->db->prepare("UPDATE notes SET avg_rating = ? WHERE id = ?");
            $updateNoteStmt->bind_param("di", $newAvg, $noteId);
            $updateNoteStmt->execute();
            
            return $newAvg;
        }
        return false;
    }
}
