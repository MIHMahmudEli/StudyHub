<?php

class Resource {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($search = null) {
        $query = "SELECT r.*, u.name as uploader_name FROM resources r 
                  LEFT JOIN users u ON r.uploader_id = u.id";
        
        if ($search) {
            $query .= " WHERE r.title LIKE ? OR r.subject LIKE ? OR r.course_code LIKE ?";
            $stmt = $this->db->prepare($query);
            $likeSearch = "%$search%";
            $stmt->bind_param("sss", $likeSearch, $likeSearch, $likeSearch);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        return $this->db->query($query . " ORDER BY r.created_at DESC")->fetch_all(MYSQLI_ASSOC);
    }

    public function store($data) {
        $sql = "INSERT INTO resources (uploader_id, title, description, subject, course_code, term, file_path, file_type, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issssssss", 
            $data['uploader_id'], 
            $data['title'], 
            $data['description'], 
            $data['subject'], 
            $data['course_code'], 
            $data['term'],
            $data['file_path'], 
            $data['file_type'],
            $data['status']
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM resources WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM resources WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE resources SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function countPending() {
        $result = $this->db->query("SELECT COUNT(*) as c FROM resources WHERE status='pending'");
        return $result->fetch_assoc()['c'];
    }

    public function countTotal() {
        $result = $this->db->query("SELECT COUNT(*) as c FROM resources");
        return $result->fetch_assoc()['c'];
    }

    public function getTrendingResources($limit = 10, $includeTopResources = false) {
        $query = "SELECT subject, 
                  COUNT(*) as total_resources,
                  SUM(downloads) as total_downloads
                  FROM resources
                  WHERE status = 'approved'
                  GROUP BY subject
                  ORDER BY total_downloads DESC
                  LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if ($includeTopResources) {
            foreach ($subjects as &$subj) {
                $name = $subj['subject'];
                $resourcesResult = $this->db->query("
                    SELECT r.id, r.title, r.downloads, r.term, r.course_code, u.name AS uploader_name
                    FROM resources r
                    LEFT JOIN users u ON r.uploader_id = u.id
                    WHERE r.subject = '$name' AND r.status = 'approved'
                    ORDER BY r.downloads DESC
                    LIMIT 5
                ");
                $subj['top_resources'] = $resourcesResult->fetch_all(MYSQLI_ASSOC);
            }
        }

        return $subjects;
    }

    public function getResourceStats() {
        $stats = [];
        
        // Total resources by term
        $result = $this->db->query("
            SELECT term, COUNT(*) as count 
            FROM resources 
            WHERE status = 'approved' 
            GROUP BY term
        ");
        $stats['by_term'] = $result->fetch_all(MYSQLI_ASSOC);
        
        // Total downloads
        $result = $this->db->query("SELECT SUM(downloads) as total FROM resources WHERE status = 'approved'");
        $stats['total_downloads'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Most downloaded resource
        $result = $this->db->query("
            SELECT r.*, u.name as uploader_name 
            FROM resources r 
            LEFT JOIN users u ON r.uploader_id = u.id 
            WHERE r.status = 'approved' 
            ORDER BY r.downloads DESC 
            LIMIT 1
        ");
        $stats['most_downloaded'] = $result->fetch_assoc();
        
        return $stats;
    }

    public function getSubjectsWithCounts($search = null) {
        $query = "SELECT subject, COUNT(*) as resource_count 
                  FROM resources 
                  WHERE status = 'approved'";
        
        if ($search) {
            $query .= " AND subject LIKE ?";
            $query .= " GROUP BY subject ORDER BY subject ASC";
            $stmt = $this->db->prepare($query);
            $likeSearch = "%$search%";
            $stmt->bind_param("s", $likeSearch);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        $query .= " GROUP BY subject ORDER BY subject ASC";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getResourcesBySubjectAndTerm($subject, $term) {
        $query = "SELECT r.*, u.name as uploader_name FROM resources r 
                  LEFT JOIN users u ON r.uploader_id = u.id 
                  WHERE r.subject = ? AND r.term = ? 
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $subject, $term);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTermCountsBySubject($subject) {
        $query = "SELECT term, COUNT(*) as count FROM resources WHERE subject = ? GROUP BY term";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $subject);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        $counts = ['mid' => 0, 'final' => 0];
        foreach ($result as $row) {
            if (isset($counts[$row['term']])) {
                $counts[$row['term']] = $row['count'];
            }
        }
        return $counts;
    }

    public function incrementDownloads($id) {
        $stmt = $this->db->prepare("UPDATE resources SET downloads = downloads + 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getFileTypeDistribution() {
        return $this->db->query("SELECT file_type, COUNT(*) as c FROM resources GROUP BY file_type")
                        ->fetch_all(MYSQLI_ASSOC);
    }

    public function getMonthlyActivity($months = 12) {
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                  FROM resources 
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                  GROUP BY month 
                  ORDER BY month ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $months);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Bookmark Methods
    public function isBookmarked($resourceId, $userId) {
        $stmt = $this->db->prepare("SELECT 1 FROM bookmarks WHERE user_id = ? AND resource_id = ?");
        $stmt->bind_param("ii", $userId, $resourceId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function addBookmark($resourceId, $userId) {
        if ($this->isBookmarked($resourceId, $userId)) return false;
        $stmt = $this->db->prepare("INSERT INTO bookmarks (user_id, resource_id, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $resourceId);
        return $stmt->execute();
    }

    public function removeBookmark($resourceId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM bookmarks WHERE user_id = ? AND resource_id = ?");
        $stmt->bind_param("ii", $userId, $resourceId);
        return $stmt->execute();
    }

    public function getBookmarkedResources($userId, $search = null) {
        $query = "SELECT r.*, u.name as uploader_name, 1 as bookmarked, 'file' as item_type
                  FROM resources r
                  INNER JOIN bookmarks b ON r.id = b.resource_id AND b.user_id = ?
                  LEFT JOIN users u ON r.uploader_id = u.id";
        
        if ($search) {
            $query .= " WHERE r.title LIKE ? OR r.subject LIKE ? OR r.course_code LIKE ?";
            $query .= " ORDER BY b.created_at DESC";
            $stmt = $this->db->prepare($query);
            $likeSearch = "%$search%";
            $stmt->bind_param("isss", $userId, $likeSearch, $likeSearch, $likeSearch);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        $query .= " ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Subject Bookmarks
    public function isSubjectBookmarked($subject, $userId) {
        $stmt = $this->db->prepare("SELECT 1 FROM bookmarks WHERE user_id = ? AND subject_name = ?");
        $stmt->bind_param("is", $userId, $subject);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function addSubjectBookmark($subject, $userId) {
        if ($this->isSubjectBookmarked($subject, $userId)) return false;
        $stmt = $this->db->prepare("INSERT INTO bookmarks (user_id, subject_name, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $userId, $subject);
        return $stmt->execute();
    }

    public function removeSubjectBookmark($subject, $userId) {
        $stmt = $this->db->prepare("DELETE FROM bookmarks WHERE user_id = ? AND subject_name = ?");
        $stmt->bind_param("is", $userId, $subject);
        return $stmt->execute();
    }

    public function getBookmarkedSubjects($userId) {
        // We might want to join with resources to get count, but for now just the name
        // Or get counts?
        // Let's get the subject names and we can fetch counts or details if needed.
        // Actually, let's reuse getSubjectsWithCounts logic but filter by bookmark.
        
        $query = "SELECT b.subject_name as subject, COUNT(r.id) as resource_count, 'subject' as item_type
                  FROM bookmarks b
                  LEFT JOIN resources r ON r.subject = b.subject_name AND r.status = 'approved'
                  WHERE b.user_id = ? AND b.subject_name IS NOT NULL
                  GROUP BY b.subject_name
                  ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
