<?php
require_once '../app/models/Resource.php';
// Resource Controller to handle resource-related actions for students
class ResourceController extends Controller {
    public function index() {
        $resourceModel = new Resource();
        $searchTerm = $_GET['q'] ?? null;
        $subjects = $resourceModel->getSubjectsWithCounts($searchTerm);
        
        $userId = $_SESSION['user_id'] ?? 0;
        foreach ($subjects as &$sub) {
            $sub['bookmarked'] = $resourceModel->isSubjectBookmarked($sub['subject'], $userId);
        }

        $this->view('resources/index', [
            'subjects' => $subjects,
            'searchTerm' => $searchTerm,
            'role' => $_SESSION['role'] ?? 'student'
        ]);
    }

    public function subject() {
        $subject = $_GET['subject'] ?? '';
        if (empty($subject)) {
            $this->redirect('resources');
        }

        $this->view('resources/subject', [
            'subject' => $subject,
            'role' => $_SESSION['role'] ?? 'student'
        ]);
    }

    public function list() {
        $subject = $_GET['subject'] ?? '';
        $term = $_GET['term'] ?? 'mid';
        
        if (empty($subject)) {
            $this->redirect('resources');
        }

        $resourceModel = new Resource();
        $resources = $resourceModel->getResourcesBySubjectAndTerm($subject, $term);

        // Check bookmarks
        $userId = $_SESSION['user_id'] ?? 0;
        foreach ($resources as &$res) {
            $res['bookmarked'] = $resourceModel->isBookmarked($res['id'], $userId);
        }

        $this->view('resources/list', [
            'subject' => $subject,
            'term' => $term,
            'resources' => $resources,
            'role' => $_SESSION['role'] ?? 'student'
        ]);
    }

    public function toggleBookmark() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo "unauthorized";
            return;
        }

        $userId = $_SESSION['user_id'];
        $resourceId = $_POST['resource_id'] ?? 0;
        $subjectName = $_POST['subject_name'] ?? null;

        $resourceModel = new Resource();

        if ($subjectName) {
            // Subject Bookmark
             if ($resourceModel->isSubjectBookmarked($subjectName, $userId)) {
                if ($resourceModel->removeSubjectBookmark($subjectName, $userId)) {
                    echo "removed";
                } else {
                    echo "error";
                }
            } else {
                if ($resourceModel->addSubjectBookmark($subjectName, $userId)) {
                    echo "added";
                } else {
                    echo "error";
                }
            }
            return;
        }

        if (!$resourceId) {
            echo "invalid";
            return;
        }

        // File Bookmark
        if ($resourceModel->isBookmarked($resourceId, $userId)) {
            if ($resourceModel->removeBookmark($resourceId, $userId)) {
                echo "removed";
            } else {
                echo "error";
            }
        } else {
            if ($resourceModel->addBookmark($resourceId, $userId)) {
                echo "added";
            } else {
                echo "error";
            }
        }
    }
}
